<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store($id){

        $itinerary = DB::table('itineraries')->where('id', $id)->exists();

        if(!$itinerary){
            return response([
                'message' => "itinerary doesn't exist!"
            ]);
        }

        $is_exists = DB::table('favorites')->where('itineraries_id', $id)->where('user_id', Auth::id())->exists();

        if($is_exists){
            return response([
                'message' => "already added!"
            ]);
        }

        $favori = Auth::user()->favorites()->create(['itineraries_id' => $id]);

        return response([
            'message' => 'the itinerary added to you favorite list successfuly'
        ]);
    }
}
