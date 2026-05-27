<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceRequestController extends Controller
{
    public function userIndex(Request $request)
    {
        return Inertia::render('User/Requests/Index', [
            'requests' => ServiceRequest::where('user_id', $request->user()->id)->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_type' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'details' => 'nullable|string|max:2000',
        ]);

        $request->user()->serviceRequests()->create($validated);

        return redirect()->route('user.requests.index')->with('message', 'Request submitted successfully.');
    }

    public function adminIndex()
    {
        return Inertia::render('Admin/Requests/Index', [
            'requests' => ServiceRequest::with('user:id,name,email')->latest()->get(),
        ]);
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,In Review,Approved,Released,Rejected',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $serviceRequest->update($validated);

        return redirect()->route('admin.requests.index')->with('message', 'Service request updated successfully.');
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        $serviceRequest->delete();

        return redirect()->route('admin.requests.index')->with('message', 'Service request removed successfully.');
    }
}
