<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model {

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'country',
        'state',
        'address',
        'user_id'
    ];

}