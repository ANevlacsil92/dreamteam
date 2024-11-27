<?php

namespace App\Http\Controllers;

use App\Models\ExtendedUserProperty;
use App\Models\Play;
use App\Models\PlayScenes;
use App\Models\PlaySchedule;
use App\Models\PlayTextbook;
use App\Models\SetTemperature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;
use stdClass;

class ApiController extends Controller
{
    public function getPlays(Request $request)
    {
        if(isset($request->playId)){
            $ret = Play::find($request->playId);
        } else {
            $ret = Play::all();

            // remove property download_link
            $ret = $ret->makeHidden(['downloads_link']);
        }

        return $ret;
    }

    public function getPlayTextbook(Request $request)
    {    
        $playScenes = PlayScenes::where('play_id', $request->playId)->with('playTextbook')->get();

        // Iterate over each scene and apply the regex to format the text
        foreach ($playScenes as $scene) {
            foreach ($scene->playTextbook as $line) {
                $line->formattedText = $this->formatText($line->text);
            }
        }
    
        return $playScenes;
    
    }

    /**
     * Function to format text by wrapping content inside parentheses with <em> tags
     *
     * @param string $text
     * @return string
     */
    protected function formatText($text)
    {
        // Regular expression to match and replace text inside parentheses
        return preg_replace('/\(([^()]*|\([^()]*\))*\)/', '<span class="no-bg"><em>$0</em></span>', $text);
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
        $line->following_stage_direction = $request->line["following_stage_direction"];
        $line->save();
        return response($line);
    }

    public function toggleLineDelete(Request $request)
    {
        
        $line = PlayTextbook::where("id", $request->line["id"])->withTrashed()->first();
        if($line->deleted_at == null){
            $line->delete();
        } else {
            $line->restore();
        }
        $line->save();
        return response($line);
    }

    public function updateICS($playId=null){


        $play = Play::find($playId);
        $dates = PlaySchedule::where("play_id", $playId)->get();
        

        $ics = "BEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//hacksw/handcal//NONSGML v1.0//EN\nMETHOD:PUBLISH\nX-WR-CALNAME:Probenplan\n";
        foreach($dates as $date){
            $ics .= "BEGIN:VEVENT\n";
            $ics .= "DTSTART;TZID=Europe/Vienna:" . date("Ymd", strtotime($date->practice_date)) . "T190000\n";
            $ics .= "DTEND;TZID=Europe/Vienna:" . date("Ymd", strtotime($date->practice_date)) . "T220000\n";
            $ics .= "SUMMARY:" . $play->name . " Probe\n";
            $ics .= "DESCRIPTION: Zeilen " . $date->start_line . "-" . $date->end_line . "\\n\\nKommentare:\\n" . $date->comment . "\n";
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

        $activeUsers = [];
        $oldUsers = [];

        foreach($users as $user){
            $u = new stdClass();
            $u->id = $user->id;
            $u->name = $user->name;
            $u->shortlink_url = $user->shortlink_url;
            $u->about_me = $user->extendedUserProperty->about_me ?? "Ich stelle mich bald vor!";
            $u->photo_url = $user->extendedUserProperty->photo_url ?? "imgs/profilepictures/logo_klein.png";
            if($user->extendedUserProperty->is_active){
                array_push($activeUsers, $u);
            } else {
                array_push($oldUsers, $u);
            }
        }

        $ret["active"] = $activeUsers;
        $ret["old"] = $oldUsers;

        return $ret;
    }

    public function getTeamMember(Request $request)
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

    public function getImageCarousel(Request $request)
    {
        // return name of files in public/images/carousel
        $files = Storage::files('public/imgs/carousel');
        $ret = [];
        foreach($files as $file){
            array_push($ret, explode("/", $file)[3]);
        }
        return $ret;
    }

    public function getTemperatureDataOld(Request $request){
        if(isset($request->from) && isset($request->to)){
            // return data between from and to while partitioning the data into 15 parts
            $data = DB::select('SELECT temperature.temperature,humidity.humidity, temperature.created_at FROM `temperature`
                    LEFT JOIN humidity 
                    ON CONCAT(YEAR(humidity.created_at),MONTH(humidity.created_at),DAY(humidity.created_at),HOUR(humidity.created_at),MINUTE(humidity.created_at)) = CONCAT(YEAR(temperature.created_at),MONTH(temperature.created_at),DAY(temperature.created_at),HOUR(temperature.created_at),MINUTE(temperature.created_at))
                    WHERE temperature.created_at BETWEEN ? AND ? ORDER BY temperature.created_at ASC', [$request->from, $request->to]);
        } else {
            // else return last hour and partition into 15 parts
            $data = DB::select('SELECT temperature.temperature,humidity.humidity, temperature.created_at FROM `temperature`
                    LEFT JOIN humidity 
                    ON CONCAT(YEAR(humidity.created_at),MONTH(humidity.created_at),DAY(humidity.created_at),HOUR(humidity.created_at),MINUTE(humidity.created_at)) = CONCAT(YEAR(temperature.created_at),MONTH(temperature.created_at),DAY(temperature.created_at),HOUR(temperature.created_at),MINUTE(temperature.created_at))
                    WHERE temperature.created_at > DATE_SUB(NOW(), INTERVAL 3 HOUR)');
        }

        $partitionsize = ceil(count($data) / 60);

        $partitions = [];
        // loop over data and take the average of each partition
        for($i = 0; $i < count($data); $i += $partitionsize){
            $sum = 0;
            $sumHum = 0;
            $sumCount = 0;
            $sumHumCount = 0;
            for($j = 0; $j < $partitionsize; $j++){
                if($i + $j < count($data)){
                    $sum += $data[$i + $j]->temperature;
                    $sumCount++;
                    if($data[$i + $j]->humidity != null){
                        $sumHum += $data[$i + $j]->humidity;
                        $sumHumCount++;
                    } else {
                        $sumHum += 0;
                        $sumHumCount++;
                    }
                }
            }
            $avg = $sum / $sumCount;
            $avgHum = $sumHum / $sumHumCount;
            $timestamp = $data[$i]->created_at;

            $entry = new stdClass();
            $entry->temperature = $avg;
            $entry->humidity = $avgHum;
            $entry->created_at = $timestamp;

            array_push($partitions, $entry);

        }

        $minDate = DB::table('temperature')->min('created_at');
        $maxDate = DB::table('temperature')->max('created_at');
        $currentTemp = DB::table('temperature')->orderBy('created_at', 'desc')->first();
        // humidityData
        $currentHumidity = DB::table('humidity')->orderBy('created_at', 'desc')->first();
        
        // setData is the set temperature with the right timezones
        $setData = DB::select('SELECT * FROM set_temperature order by created_at desc');
        $currentSetTemp = $setData[0];

        // if currentTemp older than 5 minutes, return null
        if(strtotime($currentTemp->created_at) < strtotime('-5 minutes')){
            $currentTemp = null;
        }

        // if currentHumidity older than 5 minutes, return null
        if(strtotime($currentHumidity->created_at) < strtotime('-5 minutes')){
            $currentHumidity = null;
        }

        $ret = new stdClass();
        $ret->data =  $partitions;
        $ret->setData = $setData;
        $ret->currentHumidity = $currentHumidity;
        $ret->currentTemp = $currentTemp;
        $ret->currentSetTemp = $currentSetTemp;
        $ret->minDate = $minDate;
        $ret->maxDate = $maxDate;

        return $ret;

    }

    public function getTemperatureData(Request $request){
        // call stored proceedure sp_get_sensor_data with parameters of minutes
        $data = DB::select('call sp_get_sensor_data(?)', [$request->mins]);

        $ret = new stdClass();
        $ret->data =  $data;
        $ret->currentSetTemp = DB::select('SELECT * FROM set_temperature order by created_at desc')[0];

        return $ret;
    }

    public function setTemperature(Request $request){
        // set timezone to Vienna
        date_default_timezone_set('Europe/Vienna');

        $temperature = new SetTemperature();
        $temperature->temperature = $request->temperature;
        $temperature->ack = false;
        $temperature->save();

        $server   = Config::get('mqtt.mqttServer');
        $username = Config::get('mqtt.mqttUsername');
        $password = Config::get('mqtt.mqttPassword');
        $port     = Config::get('mqtt.mqttPort');

        $mqtt = new MqttClient($server, $port, 'dreamteam' . rand(0, 1000));

        
        $connectionSettings = (new ConnectionSettings)
        ->setUsername($username)
        ->setPassword($password);

        $mqtt->connect($connectionSettings, true);
        $mqtt->publish('probenlokal/temp/ctrl/target', $request->temperature, 0, 1);

        return response("OK");
    }
}


/*
SELECT * FROM `temperature`
JOIN humidity 
ON CONCAT(YEAR(humidity.created_at),MONTH(humidity.created_at),DAY(humidity.created_at),HOUR(humidity.created_at),MINUTE(humidity.created_at)) = CONCAT(YEAR(temperature.created_at),MONTH(temperature.created_at),DAY(temperature.created_at),HOUR(temperature.created_at),MINUTE(temperature.created_at))
order by temperature.created_at desc;*/ 