<?php

namespace App\Http\Controllers;

use App\Models\itinerary;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DestinationController extends Controller
{
    public function store(Request $request, $itineraryId)
    {
        try {
            $itinerary = Itinerary::where('user_id', Auth::id())->findOrFail($itineraryId);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'lodging' => 'nullable|string',
                'places_to_visit' => 'nullable|array',
            ]);

            $destination = $itinerary->destinations()->create($validated);

            return response()->json($destination, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Itinerary not found or invalid data'], 400);
        }
    }

    public function destroy($id)
    {
        $destination = Destination::find($id);

        if (!$destination || optional($destination->itinerary)->user_id !== Auth::id()) {
            return response()->json(['message' => "Unauthorized or destination not found"], 403);
        }

        $destination->delete();

        return response()->json(['message' => 'Destination deleted successfully']);
    }
}
