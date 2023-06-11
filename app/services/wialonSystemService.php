<?php

namespace App\services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class wialonSystemService
{
    public static function getAccessToken(){
        return Redirect::to('http://gps.tawasolmap.com/login.html?lang=en&client_id=task&access_type=0x100&activation_time=0&duration=259200000&response_type=token&redirect_uri='.route("login_back"));
    }

    public static function login($token)
    {
        $response = Http::get('http://gps.tawasolmap.com/wialon/ajax.html', 'svc=token/login&params={"token":"' . $token . '"}');
        Cache::put("data",$response->json());
    }

    public static function getData(){
        $authData = Cache::get("data");
        $response = Http::get('http://gps.tawasolmap.com/wialon/ajax.html','svc=core/search_items&params=
        {"spec":{"itemsType":"avl_unit","propName":"sys_user_creator","propValueMask":"","sortType":"creatortree"},
        "force":1,"flags":4611686018427387903,"from":0,"to":1000}&sid='.$authData["eid"]);
        $response = $response->json();
        if (isset($response["error"]) && $response["error"] == 1)
            self::login(Cache::get("access_token"));
        return $response;
    }
}