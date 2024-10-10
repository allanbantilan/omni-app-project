<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\VoiceController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\CallNewController;
use App\Http\Controllers\EmailSendController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// chat routes
Route::get('/chat', [PusherController::class, 'index']);
Route::post('/broadcast', [PusherController::class, 'broadcast']);
Route::post('/recieve', [PusherController::class, 'recieve']);


// sms routes
Route::get('/send-sms', [SmsController::class, 'index'])->name('show.sms.form');
Route::post('/send-sms', [SmsController::class, 'sendSms'])->name('send.sms');
// Route for receiving SMS from Twilio (Webhook)
Route::post('/receive-sms', [SmsController::class, 'receiveSms'])->name('receive.sms');
Route::post('/sms/status', [SmsController::class, 'handleStatusUpdate'])->name('sms.status');


// call routes
Route::post('/make-call', [CallController::class, 'makeCall']); // Route to initiate a call
Route::get('/twiml-response', [CallController::class, 'twimlResponse'])->name('twimlResponse');
Route::get('/call', [CallController::class, 'index'])->name('call.view');
//incoming call
Route::post('/incoming-call', [CallController::class, 'incomingCall']);
Route::get('/twilio/token', [CallController::class, 'generateToken']);
Route::post('/make-call-self', [CallController::class, 'callSelf']);
Route::post('/initiate-call', [CallController::class, 'initiateCall']);
Route::get('/twiml', [CallController::class, 'twiml'])->name('twilio.twiml');


//sending email

//auth
Route::get('/inbox', [EmailSendController::class, 'fetchEmails'])->name('inbox');
Route::get('/auth/google', [EmailSendController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/callback', [EmailSendController::class, 'handleGoogleCallback'])->name('auth.callback');

// Sending email routes
Route::get('/check-email', function () {
    return view('email.send');
});

Route::get('/send-email', [EmailSendController::class, 'index'])->name('send.email.form');
Route::post('/send-email', [EmailSendController::class, 'sendEmail'])->name('send.email');


//new call 
Route::get('new-call', [CallNewController::class, 'index'])->name('new-call');

// access token for the front end
Route::get('/phone/token', [CallNewController::class, 'getAccessToken']);
Route::get('/generate-token', [CallController::class, 'generateToken']);
Route::post('/voice', [CallController::class, 'handleIncomingCall']);


// // Route to check for incoming calls
// Route::get('/check-incoming-call', [CallController::class, 'checkIncomingCall']);
// // Route to simulate an incoming call (for testing)
// Route::post('/simulate-incoming-call', [CallController::class, 'simulateIncomingCall']);
// // Route to handle calls to your website (not using TwiML)
// Route::post('/call-website', [CallController::class, 'callWebsite'])->name('call.website');
// Route::get('/reset-incoming-call', [CallController::class, 'resetIncomingCall']);
// // Route to generate a Twilio token for the Voice SDK
// Route::get('/twilio/token', [CallController::class, 'generateToken']);
