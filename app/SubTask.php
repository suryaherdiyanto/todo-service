<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubTask extends Model {

    protected $fillable = [
        'name',
        'is_completed',
    ];

    public function isCompleted(): boolean 
    {
        return $this->is_completed ?? false;
    }

}