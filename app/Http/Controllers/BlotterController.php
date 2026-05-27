<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use App\Models\Resident;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlotterController extends Controller
{
    public function index()
    {
        return Inertia::render('Blotters/Index', [
            // Fetch blotters and include the related resident's name
            'blotters' => Blotter::with('resident:id,first_name,last_name')->latest()->get(),
            // Pass residents to the frontend to populate the dropdown menu
            'residents' => Resident::select('id', 'first_name', 'last_name')->orderBy('last_name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'incident_type' => 'required|string|max:255',
            'date_of_incident' => 'required|date',
            'narrative' => 'required|string',
            'status' => 'required|in:Active,Settled,Scheduled for Hearing',
        ]);

        Blotter::create($validated);

        return redirect()->route('blotters.index')->with('message', 'Blotter recorded successfully.');
    }

    public function update(Request $request, Blotter $blotter)
    {
        $validated = $request->validate([
            'status' => 'required|in:Active,Settled,Scheduled for Hearing',
        ]);

        $blotter->update($validated);

        return redirect()->route('blotters.index')->with('message', 'Blotter status updated successfully.');
    }

    public function destroy(Blotter $blotter)
    {
        $blotter->delete();

        return redirect()->route('blotters.index')->with('message', 'Blotter record removed successfully.');
    }
}
