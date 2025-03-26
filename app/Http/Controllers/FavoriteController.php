<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store($id)
    {
        $itinerary = DB::table('itineraries')->find($id);

        if (!$itinerary) {
            return response()->json(['message' => "Itinerary doesn't exist!"], 404);
        }

        $favorite = Auth::user()->favorites()->firstOrCreate(['itineraries_id' => $id]);

        if (!$favorite->wasRecentlyCreated) {
            return response()->json(['message' => "Already added!"], 200);
        }

        return response()->json(['message' => 'The itinerary was successfully added to your favorite list.'], 201);
    }
}

