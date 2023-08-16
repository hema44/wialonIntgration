<?php

namespace App\Http\Controllers;

use Agence104\LiveKit\AccessToken;
use Agence104\LiveKit\AccessTokenOptions;
use Agence104\LiveKit\VideoGrant;
use Illuminate\Http\Request;

class liveKitCheckController extends Controller
{
    public function test(){
        // If this room doesn't exist, it'll be automatically created when the first
        // client joins.
        $roomName = 'ibrahem';
        // The identifier to be used for participant.
        $participantName = 'ibrahem';

        // Define the token options.
        $tokenOptions = (new AccessTokenOptions())
            ->setIdentity($participantName);

        // Define the video grants.
        $videoGrant = (new VideoGrant())
//            ->setRoomJoin();
        ->setRoomName($roomName);

        // Initialize and fetch the JWT Token.
        $token = (new AccessToken(env("LIVEKIT_API_KEY"), env("LIVEKIT_API_SECRET")))
            ->init($tokenOptions)
            ->setGrant($videoGrant)
            ->toJwt();
        dd($token);
//        return view("auth.live",compact("token"));
    }
    public function test1(){
        return view("live");
    }
}
