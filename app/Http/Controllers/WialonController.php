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
        return view("liveTrack",compact("items","data"));
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
}
