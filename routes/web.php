<?php

use App\Http\Controllers\ApiController;
use App\Models\Play;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', function () {
    return view('home')->with(['component' => 'home-component']);
});

Route::get('/impressum', function () {
    return view('imprint');
});

//Route::get('/test-token', function () {
//    return env('FACEBOOK_GRAPH_TOKEN');
//});

Route::get('/tickets', function () {
    return Redirect::to("https://shop.eventjet.at/ef36f9d1-1870-4f89-8806-8a003f7d316a/event-group/df29f27f-da47-4133-a1d9-c6ed55872371");
});

/*Route::get('/christmas-announcement', function () {
    return view('home')->with(['component' => 'announcement-component']);
});*/

Route::get('/das-team', function () {
    return view('home')->with(['component' => 'team-component']);
});


Route::get('/das-team/{shortlink}', function (Request $request) {
    
    $member = User::where('shortlink_url', $request->shortlink)->with(['extendedUserProperty', 'roles.play'])->firstOrFail();

    $params = new stdClass();
    $params->member = $member;
    
    return view('home')->with(['component' => 'team-member-component','params' => $params]);
});

Route::get('/bisher-gespielt', function () {
    return view('home')->with(['component' => 'plays-component']);
});

Route::get('/bisher-gespielt/{shortlink}', function (Request $request) {

    $play = Play::where('shortlink_url', $request->shortlink)->firstOrFail();

    $params = new stdClass();
    $params->play = $play;
    
    return view('home')->with(['component' => 'play-detail-component','params' => $params]);
});

Route::middleware(['auth'])->group(function () {

    Route::get('/members', function () {
        return view('home')->with(['component' => 'members-home-component']);
    });

    Route::get('/members/settings', function () {
        
        $params = new stdClass();
        $params->user = User::with('extendedUserProperty')->find(Auth::id());
        return view('home')->with(['component' => 'members-user-settings-component', 'params' => $params]);
    });

    Route::get('/members/play-downloads/{playId}', function (Request $request) {
        $params = new stdClass();
        $params->playId = $request->playId;
        return view('home')->with(['component' => 'members-play-download-component','params' => $params]);
    });

    Route::get('/members/textbook/{playId}', function (Request $request) {
        $params = new stdClass();
        $params->playId = $request->playId;
        return view('home')->with(['component' => 'members-textbook-component','params' => $params]);
    });

    Route::get('/members/videos/{playId}', function (Request $request) {
        $params = new stdClass();
        $params->playId = $request->playId;
        return view('home')->with(['component' => 'members-video-component','params' => $params]);
    });

    Route::get('/members/schedule/{playId}', function (Request $request) {
        $params = new stdClass();
        $params->playId = $request->playId;
        return view('home')->with(['component' => 'members-schedule-component','params' => $params]);
    });

    
    Route::get('/api/play-textbook', [ApiController::class, 'getPlayTextbook']);
    Route::get('/api/play-sections', [ApiController::class, 'getSections']);
    Route::get('/api/play-schedule', [ApiController::class, 'getScheduleData']);
    Route::post('/api/textbook/change-line', [ApiController::class, 'changeLine']);
    Route::post('/api/schedule/change-appointment', [ApiController::class, 'changeAppointment']);
    Route::delete('/api/schedule/change-appointment', [ApiController::class, 'deleteAppointment']);

    
});

Route::get('/api/trigger-ics-generation/{playId}', [ApiController::class, 'updateICS']);


Route::get('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});


Route::get("/runmig", function() {
    Artisan::call('migrate');

});
Route::get("/runstl", function() {
    Artisan::call('storage:link');

});