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
        $itinerary = itinerary::where('user_id', Auth::id())->find($itineraryId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lodging' => 'nullable|string',
            'places_to_visit' => 'nullable|array',
        ]);

        $destination = $itinerary->destinations()->create($validated);

        return response()->json($destination, 201);
    }

    public function destroy($id)
    {
        $destination = Destination::find($id);
        if ($destination->itinerary->user_id !== Auth::id()) {
            return response()->json(['message' => "Unauthorized"], 403);
        }

        $destination->delete();

        return response()->json(['message' => 'Destination deleted']);
    }
}
