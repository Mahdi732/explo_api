<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class itineraryController extends Controller
{
    public function index()
    {
        $itineraries =  DB::table('itineraries')
                ->leftJoin('destinations', 'destinations.itinerary_id', '=', 'itinerary_id')
                ->select('destinations.*', 'itineraries.*')
                ->get();
        if(count($itineraries) > 0){
            return $itineraries;
        }else{
            return response([
                'message' => 'No itineraries found!'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'categorie' => 'required|string',
            'duration' => 'required|integer|min:1',
            'image' => 'nullable|string',
            'destinations' => 'required|array',
            'destinations.*.name' => 'required|string',
            'destinations.*.lodging' => 'nullable|string',
            'destinations.*.places_to_visit' => 'nullable|array',
        ]);

        $itinerary = Auth::user()->itineraries()->create($validated);

        foreach ($validated['destinations'] as $destinationData) {
            $itinerary->destinations()->create($destinationData);
        }

        return response([
            'message' => 'success'
        ], 201);
    }

    public function show($id)
    {
        $itinerary = Itinerary::with('destinations')->find($id);

        if (!$itinerary) {
            return response()->json([
                'message' => "Itinerary not found!"
            ], 404);
        }

        return response()->json($itinerary);
    }

    public function filter(Request $request){
        $query = Itinerary::query();

        if($request->has('categorie')){
            $query->where('categorie', 'ILIKE', $request->categorie);
        }

        $itineraries = $query->get();

        if(count($itineraries) == 0){
            return response()->json([
                'message' => 'there is no itinerary with this categorie!'
            ], 401);
        }

        return response()->json($itineraries);
    }

    public function update(Request $request, $id)
    {
        $itinerary = Itinerary::find($id);

        if (!$itinerary) {
            return response()->json([
                'message' => "Itinerary not found!"
            ], 404);
        }

        if (Auth::id() != $itinerary->user_id) {
            return response()->json(
                [
                    'message' => "You are not authorized to edit this itinerary!"
                ],
                403
            );
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'categorie' => 'string',
            'duration' => 'integer|min:1',
            'image' => 'string|nullable',
        ]);

        $itinerary->update($validated);

        return response()->json([
            'message' => 'Itinerary updated successfully!',
            'itinerary' => $itinerary
        ], 201);
    }

    public function destroy($id)
    {
        $itinerary = Itinerary::find($id);

        if (!$itinerary) {
            return response()->json([
                'message' => "Itinerary not found!"
            ], 404);
        }

        if (Auth::id() != $itinerary->user_id) {
            return response()->json([
                'message' => "You are not authorized to delete this itinerary!"
            ], 403);
        }

        $itinerary->delete();

        return response()->json([
            'message' => 'Itinerary deleted successfully!'
        ], 201);
    }

    public function search($title)
    {
        $itineraries = Itinerary::where('title', 'LIKE', '%' . $title . '%')->with('destinations')->get();

        return response()->json($itineraries);
    }
}
