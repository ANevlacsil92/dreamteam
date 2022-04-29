<?php

namespace App\Http\Controllers;

use App\Models\Play;
use App\Models\PlayScenes;
use App\Models\PlaySchedule;
use App\Models\PlayTextbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function getSections(Request $request)
    {
        $scenes = PlayScenes::where('play_id', $request->playId)->with('sections')->get();

        $sections = [];
        foreach($scenes as $scene){
            foreach($scene->sections as $section){
                array_push($sections, $section);
            }
        }

        return $sections;
    }

    public function getSchedule(Request $request)
    {
        $schedule = DB::select('call sp_get_participants_by_schedule(' . $request->playId .')');
       

        return $schedule;
    }

    public function changeLine(Request $request)
    {
        //Log::debug();
        $line = PlayTextbook::find($request->line["id"]);
        $line->text = $request->line["text"];
        $line->save();
        return response($line);
    }

    public function changeAppointment(Request $request)
    {
        Log::debug($request);
        if (filter_var($request->isNew, FILTER_VALIDATE_BOOLEAN)){
            $schedule = new PlaySchedule();
            $schedule->play_id = $request->appointment["play_id"];
        } else {
            $schedule = PlaySchedule::find($request->appointment["id"]);
        }
        
        Log::debug($schedule);

        $schedule->practice_date = $request->appointment["practice_date"];
        $schedule->start_line = $request->appointment["start_line"];
        $schedule->end_line = $request->appointment["end_line"];
        $schedule->comment = $request->appointment["comment"];
        $schedule->save();
        return response(DB::select('call sp_get_participants_by_schedule(' . $request->appointment["play_id"] .')'));
    }
}
