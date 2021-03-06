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
    Route::get('/play-textbook', [ApiController::class, 'getPlayTextbook']);
    Route::get('/play-sections', [ApiController::class, 'getSections']);
    Route::get('/play-schedule', [ApiController::class, 'getSchedule']);
    Route::post('/textbook/change-line', [ApiController::class, 'changeLine']);
    Route::post('/schedule/change-appointment', [ApiController::class, 'changeAppointment']);
    Route::delete('/schedule/change-appointment', [ApiController::class, 'deleteAppointment']);
});


Route::get('/play-schedule-test', [ApiController::class, 'getSchedule']);


Route::get('/plays/{id?}', [ApiController::class, 'getPlays']);

Route::get('/extended-users', [ApiController::class, 'getExtendedUsers']);
Route::post('/member-settings/save-data', [ApiController::class, 'saveExtendedUserData']);