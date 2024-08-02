<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rooms;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric',
            'room_type' => 'required|string', // Ensure rooms is validated
            'contact' => 'required|string|max:255',
            'looking_for' => 'nullable|string|max:255',
            'occupancy' => 'nullable|string|max:255',
            'highlighted_features' => 'nullable|json',
            'amenities' => 'nullable|json',
            'description' => 'nullable|string',
            'listing_type' => 'required|string|max:255',
            'looking_for_gender' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imagePath = $image->store('images', 'public');
            $validatedData['photo'] = $imagePath;
        }

        // Decode JSON strings back to arrays
        $validatedData['highlighted_features'] = isset($validatedData['highlighted_features'])
            ? json_decode($validatedData['highlighted_features'], true)
            : [];
        $validatedData['amenities'] = isset($validatedData['amenities'])
            ? json_decode($validatedData['amenities'], true)
            : [];

        // Create a new listing
        $listing = Rooms::create($validatedData);

        return response()->json($listing, 201);
    }

    public function show($id)
    {
        $listing = Rooms::find($id);

        if (!$listing) {
            return response()->json(['message' => 'Listing not found'], 404);
        }

        return response()->json($listing);
    }

    public function update(Request $request, $id)
    {
        $listing = Rooms::find($id);

        if (!$listing) {
            return response()->json(['message' => 'Listing not found'], 404);
        }

        // Validate request data
        $request->validate([
            'title' => 'required|string',
            'location' => 'required|string',
            'price' => 'required|numeric',
            'rooms' => 'required|numeric',
            'contact' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'highlighted_features' => 'nullable|json',
            'amenities' => 'nullable|json',
            'description' => 'required|string',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($listing->photo) {
                Storage::disk('public')->delete($listing->photo);
            }

            $image = $request->file('photo');
            $imagePath = $image->store('images', 'public');
            $request->merge(['photo' => $imagePath]);
        }

        // Update listing data
        $listing->update($request->except('photo'));

        return response()->json(['message' => 'Listing updated successfully', 'data' => $listing]);
    }

    public function destroy($id)
    {
        $listing = Rooms::find($id);

        if (!$listing) {
            return response()->json(['message' => 'Listing not found'], 404);
        }

        // Delete the image file from storage if it exists
        if ($listing->photo) {
            Storage::disk('public')->delete($listing->photo);
        }

        $listing->delete();

        return response()->json(['message' => 'Listing deleted successfully']);
    }
}
