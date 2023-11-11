<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\ChatController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(UtilisateurController::class)->group(function () {
    Route::post('/compteadmin', 'verifier');
    Route::post('/seconnecter', 'login');
    Route::post('/savenomid', 'savenomID');
    Route::post('/savenomutil', 'savenomUTIL');
    Route::post('/checkpass', 'checking');
    Route::post('/allutilisateur', 'allutilisateur');
    Route::post('/all_disc', 'all_disc');
    Route::post('/token', 'loginWithToken');
});

Route::apiResource('chat', ChatController::class)->only(['index', 'store', 'show']);

// Route::controller(ChatController::class)->group(function () {
//     Route::apiResource('chat')->only(['index','store','show']);
// });