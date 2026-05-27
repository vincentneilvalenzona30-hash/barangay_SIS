<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\NewsEventController;
use App\Http\Controllers\ResidentVerificationController;
use App\Http\Controllers\ServiceRequestController;
use App\Models\Blotter;
use App\Models\Resident;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Return the public landing page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/news-events', [NewsEventController::class, 'publicIndex'])->name('news-events');

Route::get('/dashboard', function () {
    if (request()->user()->role !== 'admin') {
        $requests = ServiceRequest::where('user_id', request()->user()->id)->latest()->get();
        $verification = request()->user()->residentVerification;

        return Inertia::render('User/Dashboard', [
            'stats' => [
                'totalRequests' => $requests->count(),
                'pendingRequests' => $requests->where('status', 'Pending')->count(),
                'approvedRequests' => $requests->whereIn('status', ['Approved', 'Released'])->count(),
            ],
            'recentRequests' => $requests->take(4)->values(),
            'verification' => $verification,
        ]);
    }

    $residentCount = Resident::count();
    $blotterCount = Blotter::count();
    $activeBlotters = Blotter::where('status', 'Active')->count();
    $settledBlotters = Blotter::where('status', 'Settled')->count();
    $pendingRequests = ServiceRequest::where('status', 'Pending')->count();

    return Inertia::render('Dashboard', [
        'stats' => [
            'residents' => $residentCount,
            'blotters' => $blotterCount,
            'activeBlotters' => $activeBlotters,
            'settledBlotters' => $settledBlotters,
            'pendingRequests' => $pendingRequests,
        ],
        'recentResidents' => Resident::latest()->take(5)->get(),
        'recentBlotters' => Blotter::with('resident:id,first_name,last_name')->latest()->take(5)->get(),
    ]);
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/my-requests', [ServiceRequestController::class, 'userIndex'])->name('user.requests.index');
    Route::post('/my-requests', [ServiceRequestController::class, 'store'])->name('user.requests.store');
    Route::get('/my-verification', [ResidentVerificationController::class, 'userShow'])->name('user.verification.show');
    Route::post('/my-verification', [ResidentVerificationController::class, 'store'])->name('user.verification.store');

    Route::middleware('admin')->group(function () {
        Route::get('/service-requests', [ServiceRequestController::class, 'adminIndex'])->name('admin.requests.index');
        Route::patch('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'update'])->name('admin.requests.update');
        Route::delete('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'destroy'])->name('admin.requests.destroy');
        Route::get('/resident-verifications', [ResidentVerificationController::class, 'adminIndex'])->name('admin.verifications.index');
        Route::patch('/resident-verifications/{residentVerification}', [ResidentVerificationController::class, 'update'])->name('admin.verifications.update');

        Route::get('/admin/news-events', [NewsEventController::class, 'index'])->name('admin.news-events.index');
        Route::post('/admin/news-events', [NewsEventController::class, 'store'])->name('admin.news-events.store');
        Route::patch('/admin/news-events/{newsEvent}', [NewsEventController::class, 'update'])->name('admin.news-events.update');
        Route::delete('/admin/news-events/{newsEvent}', [NewsEventController::class, 'destroy'])->name('admin.news-events.destroy');

        Route::get('/residents', [ResidentController::class, 'index'])->name('residents.index');
        Route::post('/residents', [ResidentController::class, 'store'])->name('residents.store');
        Route::patch('/residents/{resident}', [ResidentController::class, 'update'])->name('residents.update');
        Route::delete('/residents/{resident}', [ResidentController::class, 'destroy'])->name('residents.destroy');

        Route::get('/blotters', [BlotterController::class, 'index'])->name('blotters.index');
        Route::post('/blotters', [BlotterController::class, 'store'])->name('blotters.store');
        Route::patch('/blotters/{blotter}', [BlotterController::class, 'update'])->name('blotters.update');
        Route::delete('/blotters/{blotter}', [BlotterController::class, 'destroy'])->name('blotters.destroy');

        Route::get('/clearances', function () {
            return Inertia::render('Clearances/Index', [
                'residents' => Resident::select('id', 'first_name', 'last_name', 'address', 'birth_date', 'gender', 'civil_status')
                    ->orderBy('last_name')
                    ->get(),
            ]);
        })->name('clearances.index');

        Route::get('/reports', function () {
            $residents = Resident::all();
            $blotters = Blotter::with('resident:id,first_name,last_name')->latest()->get();

            return Inertia::render('Reports/Index', [
                'summary' => [
                    'residents' => $residents->count(),
                    'maleResidents' => $residents->where('gender', 'Male')->count(),
                    'femaleResidents' => $residents->where('gender', 'Female')->count(),
                    'blotters' => $blotters->count(),
                    'activeBlotters' => $blotters->where('status', 'Active')->count(),
                    'settledBlotters' => $blotters->where('status', 'Settled')->count(),
                    'scheduledBlotters' => $blotters->where('status', 'Scheduled for Hearing')->count(),
                ],
                'civilStatus' => $residents->groupBy('civil_status')->map->count(),
                'incidentTypes' => $blotters->groupBy('incident_type')->map->count(),
                'recentBlotters' => $blotters->take(8)->values(),
            ]);
        })->name('reports.index');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
