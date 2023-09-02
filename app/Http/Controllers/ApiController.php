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

    public function getScheduleData(Request $request)
    {
        $schedule = DB::select('call sp_get_participants_by_schedule(' . $request->playId .')');
        $lines = DB::select('SELECT linenumber, said_by FROM play_textbooks where play_textbooks.play_id =' . $request->playId .';');

        $ret = new stdClass();
        $ret->schedule = $schedule;
        $ret->textlines = $lines;

        return $ret;
    }

    public function changeLine(Request $request)
    {
        //Log::debug();
        $line = PlayTextbook::find($request->line["id"]);
        $line->text = $request->line["text"];
        $line->save();
        return response($line);
    }

    public function updateICS($playId=null){


        $play = Play::find($playId);
        $dates = PlaySchedule::where("play_id", $playId)->get();
        

        $ics = "BEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//hacksw/handcal//NONSGML v1.0//EN\nMETHOD:PUBLISH\nX-WR-CALNAME:Probenplan\n";
        foreach($dates as $date){
            $ics .= "BEGIN:VEVENT\n";
            $ics .= "DTSTART:" . date("Ymd", strtotime($date->practice_date)) . "T190000\n";
            $ics .= "DTEND:" . date("Ymd", strtotime($date->practice_date)) . "T220000\n";
            $ics .= "SUMMARY:" . $play->name . " Probe\n";
            $ics .= "DESCRIPTION: Zeilen " . $date->start_line . "-" . $date->end_line . "\n";
            $ics .= "LOCATION: Margaretenguertel 38-40, 1050 Wien\n";
            $ics .= "END:VEVENT\n";
        }
        $ics .= "END:VCALENDAR";

        Storage::disk('public')->put('calendar.ics', $ics);
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

        $this->updateICS($request->appointment["play_id"]);

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
        $users = User::with('extendedUserProperty')->orderBy('name')->get();

        $ret = [];

        foreach($users as $user){
            $u = new stdClass();
            $u->id = $user->id;
            $u->name = $user->name;
            $u->about_me = $user->extendedUserProperty->about_me ?? "Ich stelle mich bald vor!";
            $u->photo_url = $user->extendedUserProperty->photo_url ?? "imgs/profilepictures/logo_klein.png";
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

    public function getAdventCalendar(Request $request)
    {
       return DB::select('SELECT * FROM vw_advent_calendar');
    }
}
