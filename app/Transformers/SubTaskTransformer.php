<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\SubTask;

class SubTaskTransformer extends TransformerAbstract {

    protected $availableIncludes = ['task'];

    public function transform(SubTask $subtask)
    {
        return [
            'id' => $subtask->id,
            'name' => $subtask->name,
            'is_completed' => $subtask->isCompleted(),
            'created_at' => $subtask->created_at,
            'updated_at' => $subtask->updated_at
        ];
    }

    public function includeSubtasks(SubTask $subtask)
    {
        if ($subtask->tasks->count() > 0) {
            return $this->item(new TaskTransformer($task->subtasks));
        }
    }

}