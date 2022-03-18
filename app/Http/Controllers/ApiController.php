<?php

namespace App\Http\Controllers;

use App\Models\Play;
use App\Models\PlayScenes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function getPlays(Request $request)
    {
        if(isset($request->playId)){
            $ret = Play::find($request->playId);
        } else {
            $ret = Play::all();
        }

        return $ret;
    }

    public function getPlayTextbook(Request $request)
    {
        return PlayScenes::where('play_id', $request->playId)->with('playTextbook')->get();
    }
}
