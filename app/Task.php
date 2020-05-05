<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

    protected $fillable = [
        'title',
        'deadline',
        'note',
        'is_completed',
        'progress',
        'user_id'
    ];

    protected $dates = ['deadline'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subtasks()
    {
        return $this->hasMany(SubTask::class);
    }

    public function isCompleted(): bool 
    {
        return $this->is_completed == 1 ? true : false;
    }

    public function hasSubtask(): bool 
    {
        if ($this->subtasks->count() === 0) {
            return false;
        }

        return true;
    }

    public function progressInPercentage(): string 
    {
        return $this->progress . '%';
    }

}