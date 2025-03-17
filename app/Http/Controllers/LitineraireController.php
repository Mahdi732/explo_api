<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\VisitList;
use Illuminate\Http\Request;

class LitineraireController extends Controller
{

    public function getAllItineraries()
    {
        $itineraries = Itinerary::with('destination')->get();
        return response()->json($itineraries);
    }

   public function createItinerary(Request $request) {
    $request->validate([
        'title' => 'required|string|max:250|min:5',
        'category' => 'required|string',
        'duration' => 'required|integer',
        'image' => 'required|url',
        'destinations' => 'required|array|min:2',
        'destinations.*.name' => 'required|string|max:255',
        'destinations.*.categorie' => 'required|string|max:255', 
        'destinations.*.activities' => 'nullable|string'
    ]);

    $itinerary = Itinerary::create([
        'title' => $request->title,
        'category' => $request->category,
        'duration' => $request->duration,
        'image' => $request->image,
        'user_id' => auth()->id(),
    ]);

    foreach ($request->destinations as $destination) {
        $itinerary->destination()->create([
            'name' => $destination["name"],
            'categorie' => $destination['categorie'] ?? 'wa33333333',
            'activities' => $destination['activities'] ?? null
        ]);
    }
    $response = [
        'itinerary' => [
            $itinerary,
            $request->destinations
        ]
    ];
    return response($response, 201);
   }

   public function addToVisitList($itineraryId)
    {
        $itinerary = Itinerary::findOrFail($itineraryId);

        if ($itinerary->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        VisitList::create([
            'user_id' => auth()->id(),
            'itinerary_id' => $itinerary->id,
        ]);

        return response()->json(['message' => 'Added to visit list'], 200);
    }

}
