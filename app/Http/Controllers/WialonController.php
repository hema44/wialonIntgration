<?php

namespace App\Http\Controllers;

use App\services\wialonSystemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WialonController extends Controller
{
    private $data;

    public function __construct(){
//        $ip = request()->ip();
        dd(Cache::get("data"));
        $this->data = \Location::get("156.196.241.251");
    }
    public function liveTracking(Request $request){
        $items = wialonSystemService::getData();
        $data = $this->data;
        $items = collect($items["items"]);
        $trips = $this->getTrips($items->pluck("id")->toArray());
        return view("liveTrack",compact("items","data","trips"));
    }
    public function liveTrackingJson(Request $request){
        $items = wialonSystemService::getData();
        $items = collect($items["items"]);
        return response()->json(["items"=>$items],200);
    }
    public function getZones(Request $request){
        $items = wialonSystemService::getZone();
        $data = $this->data;
        return view("Gefonce",compact("items","data"));
    }

    public function createZones(Request $request){
        $items = wialonSystemService::createZone();
        $data = $this->data;
        return $items;
    }

    public function checkUpdates(Request $request){
        $items = wialonSystemService::checkUpdates();
        return $items;
    }

    public function getTrips($items){
        $trips = [];
        foreach ($items as $item){
            $trips +=wialonSystemService::getTrips($item);
        }
        return $trips;
    }
    public function getReportTrips($items){
        $trips = [];
        foreach ($items as $item){
            $trips +=[$item =>wialonSystemService::getReportTrips($item)];
        }
        return $trips;
    }

    public function powerExitStatic()
    {
        $items = wialonSystemService::getData();
        $data = $this->data;
        $items = collect($items["items"]);
        $items = $items->pluck("id");
        $out =[];
        foreach ($items as $item){
            $response = wialonSystemService::getMassage($item,1690269310,1691483431);

            $daysHaveProblem = array_filter($response["messages"], function ($value)  {
                if (isset($value["p"]["pwr_ext"]) && $value["p"]["pwr_ext"] < 8)
                    return $value;
            });
            $countDaysHaveProblem = count($daysHaveProblem);
            if ($countDaysHaveProblem>= 3)
                $out += [$item =>["massage"=>"power is reduction than 8 three time","countDaysHaveProblem"=>$countDaysHaveProblem,"data"=>$daysHaveProblem]];
            $out += [$item =>["massage"=>"power is stable","countDaysHaveProblem"=>$countDaysHaveProblem,"data"=>$daysHaveProblem]];

        }
        return ["massage"=>"done Calculation","data"=>$out];

    }

    public function internetDiscount()
    {
        $items = wialonSystemService::getData();
        $data = $this->data;
        $items = collect($items["items"]);
        $items = $items->pluck("id");
        $out =[];
        foreach ($items as $item){
            $responses = wialonSystemService::getMassage($item,1690269310,1691483431)["messages"];
            $length = count($responses) -1 ;
            $outInside =[];
            for($i=0;$i < $length;$i++){
                $nextKey=$i+1;
                if(($responses[$nextKey]["t"] - $responses[$i]["t"] )> 28800){
                    $outInside += [["time"=>$responses[$i]["t"],"difference"=>$responses[$nextKey]["t"] - $responses[$i]["t"] ]];
                }
            }
            $internetDisconnectCounted = count($outInside);
            if ($internetDisconnectCounted > 2)
                $out += [$item=>["massage"=>"interDisconnected more than or equal 2 times","internetDisconnectCounted"=>$internetDisconnectCounted,"data"=>$outInside]];
            $out += [$item=>["massage"=>"internet stable","internetDisconnectCounted"=>$internetDisconnectCounted,"data"=>$outInside]];
        }
        return ["massage"=>"done","data"=>$out];
    }

    public function testGpsSignal()
    {
        $items = wialonSystemService::getData();
        $items_responses = collect($items["items"]);
        $items = $items_responses->pluck("id");
        $out =[];
        foreach ($items as $key =>$item){
            $responses = wialonSystemService::getMassage($item,1691182800,1691483431)["messages"];

            $sensorName= "";
            foreach ($items_responses[$key]["sens"] as $sns){
                if (isset($sns["m"]) && $sns["m"] == "On/Off"){
                    $sensorName = $sns["p"];
                    break;
                }
            }
            $length = count($responses);
            $outInside =[];
            if ($sensorName){
                $i=0;
                while ($i < $length){
                    for($x=$i+1;$x < $length;$x++){
                        if (!$responses[$i]["pos"]["x"] && !$responses[$x]["pos"]["x"]){
                            if ($responses[$i]["p"][$sensorName] && $responses[$x]["p"][$sensorName]){
                                $time =$responses[$x]["pos"]["x"] - $responses[$i]["pos"]["x"];
                                if ($time >= 3600){
                                    $outInside += [$i=>["start"=>$responses[$i],"end"=>$responses[$x]]];
                                    $i = $x;
                                    break;
                                }
                            }else{
                                $i = $x;
                                break;
                            }
                        }else{
                            $i = $x;
                            break;
                        }
                    }
                    $i++;
                }
                $gpsSignalDisconnectCount = count($outInside);
                if ($gpsSignalDisconnectCount >= 1)
                    $out += [$item=>["massage"=>"gps signal disconnected more than or equal 1 times","gpsSignalDisconnectCount"=>$gpsSignalDisconnectCount,"data"=>$outInside]];
                $out += [$item=>["massage"=>"gps signal stable","gpsSignalDisconnectCount"=>$gpsSignalDisconnectCount,"data"=>$outInside]];
            }
            $out += [$item=>["massage"=>"No Engin sensor For this item","gpsSignalDisconnectCount"=>null,"data"=>null]];
        }
        return ["massage"=>"done","data"=>$out];
    }

}
