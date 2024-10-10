<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;

class VoiceController extends Controller
{
    protected $client;

    public function index() {
        return view('Call.call');
    }



}
