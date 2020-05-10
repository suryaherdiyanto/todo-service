<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendAccountActivation 
{
    

    public function __construct()
    {

    }

    public function handle(UserRegistered $event)
    {
        \Log::info('Listener fired!');
        Mail::send('mails.user-activation', ['user' => $event->user], function($m) use($event) {
            $m->to($event->user->email);

            $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))->subject('User Activation');
        });
    }

}
