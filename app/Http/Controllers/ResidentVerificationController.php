<?php

namespace App\Http\Controllers;

use App\Models\ResidentVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ResidentVerificationController extends Controller
{
    public function userShow(Request $request)
    {
        return Inertia::render('User/Verification/Show', [
            'verification' => $request->user()->residentVerification,
        ]);
    }

    public function store(Request $request)
    {
        $verification = $request->user()->residentVerification;

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|string|max:50',
            'civil_status' => 'required|string|max:100',
            'contact_number' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'purok' => 'nullable|string|max:100',
            'years_of_residency' => 'nullable|string|max:100',
            'valid_id_type' => 'required|string|max:255',
            'valid_id_number' => 'nullable|string|max:255',
            'valid_id' => [$verification ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'face_photo' => [$verification ? 'nullable' : 'required', 'string'],
        ]);

        $validIdPath = $verification?->valid_id_path;
        $facePhotoPath = $verification?->face_photo_path;

        if ($request->hasFile('valid_id')) {
            if ($validIdPath) {
                Storage::disk('public')->delete($validIdPath);
            }

            $validIdPath = $request->file('valid_id')->store('resident-verifications/valid-ids', 'public');
        }

        if ($request->filled('face_photo')) {
            if ($facePhotoPath) {
                Storage::disk('public')->delete($facePhotoPath);
            }

            $facePhotoPath = $this->storeFacePhoto($validated['face_photo'], $request->user()->id);
        }

        unset($validated['valid_id'], $validated['face_photo']);

        $request->user()->residentVerification()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                ...$validated,
                'valid_id_path' => $validIdPath,
                'face_photo_path' => $facePhotoPath,
                'status' => 'Submitted',
                'admin_notes' => null,
                'submitted_at' => now(),
            ],
        );

        return redirect()->route('user.verification.show')->with('message', 'Verification submitted successfully.');
    }

    public function adminIndex()
    {
        return Inertia::render('Admin/Verifications/Index', [
            'verifications' => ResidentVerification::with('user:id,name,email')->latest()->get(),
        ]);
    }

    public function update(Request $request, ResidentVerification $residentVerification)
    {
        $validated = $request->validate([
            'status' => 'required|in:Submitted,Verified,Rejected',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $residentVerification->update($validated);

        return redirect()->route('admin.verifications.index')->with('message', 'Verification updated successfully.');
    }

    private function storeFacePhoto(string $facePhoto, int $userId): string
    {
        abort_unless(str_starts_with($facePhoto, 'data:image/'), 422, 'Face photo must be captured from the camera.');

        [$metadata, $encodedImage] = explode(',', $facePhoto, 2);
        preg_match('/data:image\/(\w+);base64/', $metadata, $matches);

        $extension = $matches[1] ?? 'png';
        $path = 'resident-verifications/face-photos/user-'.$userId.'-'.time().'.'.$extension;

        Storage::disk('public')->put($path, base64_decode($encodedImage));

        return $path;
    }
}
