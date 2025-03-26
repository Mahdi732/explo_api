<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\itineraryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', [UserController::class, 'index']);


Route::get('/getAll/Itineraries', [itineraryController::class, 'index']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router){
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/create/itineraries', [itineraryController::class, 'store']);
    Route::put('/edit/itineraries/{id}', [itineraryController::class, 'update']);
    Route::delete('/delete/itineraries/{id}', [itineraryController::class, 'destroy']);
    Route::get('/filter/itineraries', [itineraryController::class, 'filter']);

    Route::get('itineraries/{id}', [ItineraryController::class, 'show']);
    Route::post('/itineraries/{id}/destinations', [DestinationController::class, 'store']);
    Route::delete('/destinations/{id}', [DestinationController::class, 'destroy']);

    Route::post('/itineraries/{id}/favorite', [FavoriteController::class, 'store']);
});

    


