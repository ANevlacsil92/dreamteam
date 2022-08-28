<?php

namespace App\Http\Controllers;

use App\Models\ExtendedUserProperty;
use App\Models\Play;
use App\Models\PlayScenes;
use App\Models\PlaySchedule;
use App\Models\PlayTextbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use stdClass;

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
        //Log::debug($request);
        if (filter_var($request->isNew, FILTER_VALIDATE_BOOLEAN)){
            $schedule = new PlaySchedule();
            $schedule->play_id = $request->appointment["play_id"];
        } else {
            $schedule = PlaySchedule::find($request->appointment["id"]);
        }
        
        $schedule->practice_date = $request->appointment["practice_date"];
        $schedule->start_line = $request->appointment["start_line"];
        $schedule->end_line = $request->appointment["end_line"];
        $schedule->comment = $request->appointment["comment"];
        $schedule->save();
        return response(DB::select('call sp_get_participants_by_schedule(' . $request->appointment["play_id"] .')'));
    }

    public function deleteAppointment(Request $request)
    {
        $schedule = PlaySchedule::find(intval($request->id));   
        $schedule->delete();
        return response(DB::select('call sp_get_participants_by_schedule(' . $request->play_id .')'));
    }

    public function getExtendedUsers(Request $request)
    {
        $users = User::with('extendedUserProperty')->get();

        $ret = [];

        foreach($users as $user){
            $u = new stdClass();
            $u->id = $user->id;
            $u->name = $user->name;
            $u->about_me = $user->extendedUserProperty->about_me;
            $u->photo_url = $user->extendedUserProperty->photo_url;
            array_push($ret, $u);
        }

        return $ret;
    }

    public function saveExtendedUserData(Request $request)
    {
        $user = User::find($request->id);
        $property = ExtendedUserProperty::where('user_id', $user->id)->first();

        $file = $request->file("file");
        if( $file != ""){

            $filename = 'imgs/profilepictures/' . str_replace(" ", "-", strToLower($user->name)) . "." . explode("/", $file->getClientMimeType())[1];
            
            if (Storage::exists($file)) {
                Storage::delete($file);
            }
            
            $file->storeAs("public/",  $filename);
            $property->photo_url = $filename;
        }

        $property->about_me = $request->description;
        $property->save();

        return response("OK");
    }
}
