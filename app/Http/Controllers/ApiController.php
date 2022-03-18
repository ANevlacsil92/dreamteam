<?php

namespace App\Http\Controllers;

use App\Models\Play;
use App\Models\PlayScenes;
use App\Models\PlayTextbook;
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

    public function changeLine(Request $request)
    {
        //Log::debug();
        $line = PlayTextbook::find($request->line["id"]);
        $line->text = $request->line["text"];
        $line->save();
        return response($line);
    }
}
