<?php

namespace App\Http\Controllers;

use App\User;
use App\Jobs\SendSMSMessages;
use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon;

class SMSController extends Controller
{
    public function fill($message = 'test', $id)
    {
        $user = User::find($id);
        $message =  $message . ' ' . Carbon::now()->timestamp;
        $job = (new SendSMSMessages($user, $message))->delay(60);
        $this->dispatch($job);
    }
    public function send($message){
        echo $message;
    }
}
