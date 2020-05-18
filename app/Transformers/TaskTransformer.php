<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Task;

class TaskTransformer extends TransformerAbstract {

    protected $availableIncludes = ['subtasks', 'user'];

    public function transform(Task $task)
    {
        return [
            'id' => $task->id,
            'title' => $task->title,
            'deadline' => $task->deadline->format('j F Y'),
            'is_completed' => $task->isCompleted(),
            'progress_integer' => $task->progress,
            'progress_percentage' => $task->progressInPercentage(),
            'note' => $task->note,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at
        ];
    }

    public function includeUser(Task $task)
    {
        return $this->item($task->user, new UserTransformer);
    }

    public function includeSubtasks(Task $task)
    {
        if ($task->subtasks->count() > 0) {
            return $this->collection($task->subtasks, new SubTaskTransformer);
        }
    }

}