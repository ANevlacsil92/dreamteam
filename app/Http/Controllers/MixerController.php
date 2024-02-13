<?php

namespace App\Http\Controllers;

use App\Models\Light;
use App\Models\Sound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use stdClass;

class MixerController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($playId)
    {
        $params = new stdClass();
        $params->playId = $playId;
        return view('home')->with(['component' => 'mixer-component', 'params' => $params]);
    }

    /**
     * Sounds
     */
    public function getSounds(){
        return Sound::all();
    }

    /**
     * Lights
     */
    public function getLights(){
        return Light::all();
    }


    /**
     * SPOTIFY
     */

    public function getOAuth2Authentication(){
            
            return redirect('https://accounts.spotify.com/authorize?client_id=' . Config::get('spotify.clientID') . '&redirect_uri=' . Config::get('spotify.redirectURI') . '&response_type=code&scope=app-remote-control,user-modify-playback-state');
    }

    public function spotifyCallback(Request $request){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://accounts.spotify.com/api/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'code='. $request->code . '&grant_type=authorization_code&redirect_uri=' . Config::get('spotify.redirectURI'),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . base64_encode(Config::get('spotify.clientID') . ':' . Config::get('spotify.clientSecret'))
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        session(['spotify_access_token' => json_decode($response)->access_token]);

        return json_decode($response)->access_token;
    }

    public function getSpotifyAccessToken(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://accounts.spotify.com/api/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=client_credentials&client_id=' . Config::get('spotify.clientID') . '&client_secret=' . Config::get('spotify.clientSecret'),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Cookie: __Host-device_id=AQB_fFHGSQ-goZAn6arfC2y00q6gWy6sUw48t8GReCKjXVvUu-o3TestJ1qv7i6QgeabTT92nlHedWHAp2YTBiJ0uYa5CyfvPPE; sp_tr=false'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        session(['spotify_access_token' => json_decode($response)->access_token]);

        return json_decode($response)->access_token;
    }

    public function getCurrentToken(){
        return session('spotify_access_token');
    }

    public function getPlaylistTracks(Request $request){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.spotify.com/v1/playlists/' . $request->playlist_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $this->getCurrentToken()
        ),
        ));

        $response =  json_decode(curl_exec($curl));
        curl_close($curl);
        $tracks = [];
        

        if(isset($response->error)){
            return response($response->error->message, $response->error->status);
        }

        foreach($response->tracks->items as $key => $item){
            $track = new stdClass();
            $track->id = $item->track->id;
            $track->name = $item->track->name;
            $track->artist = $item->track->artists[0]->name;
            $track->context_uri = "spotify:playlist:" . $request->playlist_id;
            $track->image = $item->track->album->images[0]->url;
            $track->position = $key;
            array_push($tracks, $track);
        }
        
        
        return $tracks;
    }

    public function playTrack(Request $request){

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.spotify.com/v1/me/player/play',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'PUT',
          CURLOPT_POSTFIELDS =>'{
            "context_uri": "' . $request->context_uri .'",
            "offset": {
                "position": ' . $request->position .'
            }
        }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $this->getCurrentToken(),
            'Content-Type: application/json'
          ),
        ));
        
        $response =  json_decode(curl_exec($curl));
        
        curl_close($curl);

        if(isset($response->error)){
            return response($response->error->message, $response->error->status);
        }
        return $response;
        
    }

    public function pauseTrack(){
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.spotify.com/v1/me/player/pause',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'PUT',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $this->getCurrentToken(),
            'Content-Length: 0'
          ),
        ));
        
        $response =  json_decode(curl_exec($curl));
        
        curl_close($curl);

        if(isset($response->error)){
            return response($response->error->message, $response->error->status);
        }
        return $response;
        
    }

    
}
