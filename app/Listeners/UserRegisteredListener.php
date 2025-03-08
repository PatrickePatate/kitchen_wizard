<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\WelcomeAboardMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class UserRegisteredListener
{

    public function __construct()
    {
        //
    }

    public function handle(UserRegistered $event): void
    {
        Mail::to($event->user->email)->queue(new WelcomeAboardMail($event->user));
    }
}
