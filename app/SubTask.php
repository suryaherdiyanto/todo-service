<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubTask extends Model {

    protected $fillable = [
        'id',
        'name',
        'is_completed',
        'task_id',
        'created_at',
        'updated_at'
    ];

    public function isCompleted(): bool 
    {
        return $this->is_completed ?? false;
    }

}