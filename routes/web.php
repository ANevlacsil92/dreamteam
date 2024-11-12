<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\MixerController;
use App\Models\Play;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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

Route::get("/test", function() {
    return "OK";

});
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
    return Redirect::to("https://www.theatercenterforum.com/nichts-fuer-ungut-reservierung/");
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

Route::get('/produktionen', function () {
    return view('home')->with(['component' => 'plays-component']);
});

Route::get('/produktionen/{shortlink}', function (Request $request) {

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

    Route::get('/members/temperature', function () {
        // get current user
        $user = Auth::user();
        // check if user has right email
        if($user->email != "a.nevlacsil@hotmail.com" && $user->email != "alexander@nevlacsil.at") {
            return redirect('/members');
        }
        return view('home')->with(['component' => 'members-temperature-component']);
    });

    
    Route::get('/api/play-textbook', [ApiController::class, 'getPlayTextbook']);
    Route::get('/api/play-sections', [ApiController::class, 'getSections']);
    Route::get('/api/play-schedule', [ApiController::class, 'getScheduleData']);
    Route::post('/api/textbook/change-line', [ApiController::class, 'changeLine']);
    Route::post('/api/textbook/toggle-line-delete', [ApiController::class, 'toggleLineDelete']);
    Route::post('/api/schedule/change-appointment', [ApiController::class, 'changeAppointment']);
    Route::delete('/api/schedule/change-appointment', [ApiController::class, 'deleteAppointment']);

    Route::get('/api/temperature', [ApiController::class, 'getTemperatureData']);
    Route::post('/api/temperature', [ApiController::class, 'setTemperature']);

    
    Route::get('/mixer/get-sounds', [MixerController::class, 'getSounds']);
    Route::get('/mixer/get-lights', [MixerController::class, 'getLights']);
    Route::get('/mixer/get-oauth2-authentication', [MixerController::class, 'getOAuth2Authentication']);
    Route::get('/mixer/get-spotify-access-token', [MixerController::class, 'getSpotifyAccessToken']);
    Route::get('/mixer/get-playlist-tracks', [MixerController::class, 'getPlaylistTracks']);
    Route::get('/mixer/play-track', [MixerController::class, 'playTrack']);
    Route::get('/mixer/pause-track', [MixerController::class, 'pauseTrack']);

    Route::get('/mixer/get-current-token', [MixerController::class, 'getCurrentToken']);


    Route::get('/mixer/{playId}', [MixerController::class, 'index']);

    Route::get('/callbacks/spotify', [MixerController::class, 'spotifyCallback']);
    
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