<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class ItineraryTest extends TestCase
{
    /**
     * A basic unit test Itenerary.
     */
    public function test_check_if_store_itinerary_is_correct(): void
    {
        $user = User::where('email', 'wissam@gmail.com')->first();

        $this->actingAs($user);

        $response = $this->postJson('/api/itineraries', [
            'title' => 'test unit',
            'categorie' => 'imensi',
            'duration' => 8,
            'image' => 'test.png',
            "destinations" => [
                    [
                        "name" => "test",
                        "lodging" => "test",
                        "places_to_visit" => [
                                "Plage Foum el Oued",
                                "Centre ville"
                        ]
                    ]
                ]
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('itineraries', [
            'title' => 'test unit',
            'categorie' => 'imensi',
            'duration' => 8,
            'image' => 'test.png',
        ]);
    }

    public function test_check_if_update_itinerary_is_correct(): void
    {
        $user = User::where('email', 'wissam@gmail.com')->first();

        $this->actingAs($user);

        $response = $this->putJson('/api/itineraries/11', [
            'title' => 'update title 2',
            'categorie' => 'update categorie 2',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('itineraries', [
            'title' => 'update title 2',
            'categorie' => 'update categorie 2',
        ]);
    }

    public function test_if_delete_itinerary_is_correct(){
        $user = User::where('email', 'wissam@gmail.com')->first();

        $this->actingAs($user);

        $response = $this->deleteJson('api/itineraries/1');

        $response->assertStatus(201);
    }
}
