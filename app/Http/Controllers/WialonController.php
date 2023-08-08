<?php

namespace App\Http\Controllers;

use App\services\wialonSystemService;
use Illuminate\Http\Request;

class WialonController extends Controller
{
    private $data;

    public function __construct(){
//        $ip = request()->ip();
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
                if ($value["p"]["pwr_ext"] < 8)
                    return $value;
            });
            if (count($daysHaveProblem) >= 3)
                $out += [$item =>["massage"=>"power is reduction than three time","data"=>$daysHaveProblem]];
            $out += [$item =>["massage"=>"power is stable","data"=>$daysHaveProblem]];

        }
        return ["massage"=>"done Calculation","data"=>$out];

    }
}
