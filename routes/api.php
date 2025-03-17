    <?php

    use App\Http\Controllers\LitineraireController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\UserController;

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

    Route::post('/register', [UserController::class, 'register']);

    Route::get('/getAllItineraries', [LitineraireController::class, 'getAllItineraries']);

    Route::post('/createItinerary', [LitineraireController::class, 'createItinerary']);

    Route::post('/itineraries/{itineraryId}/add-to-visit-list', [LitineraireController::class, 'addToVisitList']);

    Route::group([
        'middleware' => 'api',
        'prefix' => 'auth'
    ], function ($router){
        Route::post('/login', [UserController::class, 'login']);
    });

