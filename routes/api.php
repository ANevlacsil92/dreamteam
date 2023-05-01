<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\QuestionaireController;
use App\Http\Controllers\XALController;
use App\Models\QuestionaireModels\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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



Route::middleware(['auth:sanctum'])->group(function () {
});


Route::get('/play-schedule-test', [ApiController::class, 'getSchedule']);


Route::get('/plays/{id?}', [ApiController::class, 'getPlays']);

Route::get('/the-team', [ApiController::class, 'getExtendedUsers']);
Route::get('/advent-calendar', [ApiController::class, 'getAdventCalendar']);
Route::get('/image-carousel', [ApiController::class, 'getAdventCalendar']);
Route::post('/member-settings/save-data', [ApiController::class, 'saveExtendedUserData']);