<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

    protected $fillable = [
        'title',
        'deadline',
        'note',
        'is_completed',
        'progress'
    ];

    protected $dates = ['deadline'];

    public function subtasks()
    {
        return $this->hasMany(SubTask::class);
    }

    public function isCompleted(): boolean 
    {
        return $this->is_completed ?? false;
    }

    public function hasSubtask(): boolean 
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