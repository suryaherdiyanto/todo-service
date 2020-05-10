<?php

namespace App\Events;

class UserRegistered extends Event
{
    
    public $user;

    public function __construct(\App\User $user)
    {
        $this->user = $user;
        \Log::info('Event fired!');
    }

}
