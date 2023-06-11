<?php

namespace App\Http\Controllers;

use App\services\wialonSystemService;
use Illuminate\Http\Request;

class WialonController extends Controller
{
    public function liveTracking(Request $request){
        $items = wialonSystemService::getData();
        $items = collect($items["items"]);
        return view("liveTrack",compact("items"));
    }
    public function liveTrackingJson(Request $request){
        $items = wialonSystemService::getData();
        $items = collect($items["items"]);
        return response()->json(["items"=>$items],200);
    }
}
