<?php

namespace App\services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class wialonSystemService
{
    public static function getAccessToken(){
        return Redirect::to('https://gps.tawasolmap.com/login.html?lang=en&client_id=task&access_type=0x100&activation_time=0&duration=259200000&response_type=token&redirect_uri='.route("login_back"));
    }

    public static function login($token)
    {
        $response = Http::get('https://gps.tawasolmap.com/wialon/ajax.html', 'svc=token/login&params={"token":"' . $token . '"}');
        Cache::put("data",$response->json());
    }

    public static function getData(){
        $authData = Cache::get("data");
        $response = Http::get('https://gps.tawasolmap.com/wialon/ajax.html','svc=core/search_items&params=
        {"spec":{"itemsType":"avl_unit","propName":"sys_user_creator","propValueMask":"","sortType":"creatortree"},
        "force":1,"flags":4611686018427387903,"from":0,"to":1000}&sid='.$authData["eid"]);
        $response = $response->json();
        if (isset($response["error"]) ){
            if ($response["error"] == 1){
                self::login(Cache::get("access_token"));
                self::getData();
            }else{
                dd($response);
            }
        }
        return $response;
    }

    public static function getTrips($id,$d=0){
        $authData = Cache::get("data");
        $time = time();
        $url = 'https://gps.tawasolmap.com/wialon/ajax.html?svc=messages/load_interval&params={"itemId":'.$id.',"timeFrom":1688720521,"timeTo":'.$time.',"flags":0,"flagsMask":0,"loadCount":4294967295}&sid='.$authData["eid"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, []);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);
        curl_close($ch);

        $url = 'https://gps.tawasolmap.com/wialon/ajax.html?svc=unit/get_trips&params={"itemId":'.$id.',"timeFrom":0,"timeTo":'.$time.',"msgsSource":1}&sid='.$authData["eid"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, []);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response1 = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response1, true);
        $length = count($response);

        if ($length == 0){
            return [
                "from" => [
                    "i" => 0,
                    "t" => 0,
                    "p" => [
                        "y" => 0,
                        "x" => 0
                     ]
                ],
                "to" => [
                    "i" => 0,
                    "t" => 0,
                    "p" => [
                      "y" => 0,
                      "x" => 0
                    ],
              ],
                "m" => 0
            ];
        }
        return $response[$length-1];
    }

    public static function checkUpdates(){
        $authData = Cache::get("data");
        $url = 'https://gps.tawasolmap.com/wialon/ajax.html?svc=events/check_updates&params={"lang":"en","measure":'.$authData["user"]["mu"].',"detalization":1}&sid='.$authData["eid"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, []);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response1 = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response1, true);

        if (isset($response["error"]) ){
            if ($response["error"] == 1){
                self::login(Cache::get("access_token"));
                self::checkUpdates();
            }else{
                dd($response);
            }
        }

        return $response;
    }

    public static function getZone(){
        $authData = Cache::get("data");
        $response = Http::get('https://gps.tawasolmap.com/wialon/ajax.html','svc=resource/get_zone_data&params=
        {"itemId":"'.$authData["user"]["bact"].'","col":"","flags":"4611686018427387903"}&sid='.$authData["eid"]);
        $response = $response->json();
//        dd($response);
        if (isset($response["error"]) ){
            if ($response["error"] == 1){
                self::login(Cache::get("access_token"));
                self::getZone();
            }else{
                dd($response);
            }
        }
        return $response;
    }

    public static function createZone(){

        $authData = Cache::get("data");
//        $response = Http::get('https://gps.tawasolmap.com/wialon/ajax.html','svc=resource/update_zone&params=
//        {"itemId":"'.$authData["user"]["bact"].'","id":0,"callMode":"create","n":"test",
//        "d":"test add new zone","t":3,"w":50,"f":112,"c":2145942128,"tc":2145942128,"ts":10,"min":0,"max":18,"path":"library/poi/A_19.png","libId":0,
//        "p":["x":46.550095158667816,"y":24.72331063396075,"r":5000]}&sid='.$authData["eid"]);
//        $response = $response->json();
//        .'"b":["min_x":46.550095158667816,"min_y":24.72331063396075,"min_x":46.550095158667816,"min_y":24.72331063396075,"cen_x":46.550095158667816,"cen_y":24.72331063396075,]'

        $url = 'https://gps.tawasolmap.com/wialon/ajax.html?svc=resource/update_zone&params={'
            .'"itemId":'.$authData["user"]["bact"].',"id":0,"callMode":"create","n":"test",'
            .'"d":"test add new zone","t":3,"w":50,"f":112,"c":2145942128,"tc":2145942128,"ts":10,"min":0,"max":18,"path":"library/poi/A_4.png","libId":0,'
            .'"p":["x":46.550095158667816,"y":24.72331063396075,"r":5000],'
//            .'"b":["min_x":46.301254836297915,"min_y":24.761788863418065,"max_x":46.16016667773903,"max_y":24.814835702424705,"cen_x":46.550095158667816,"cen_y":24.72331063396075,]'
            .'}&sid='.$authData["eid"];
//        dd($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, []);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response1 = curl_exec($ch);
        curl_close($ch);

        return json_decode($response1, true);
    }


}
