<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

class UserTransformer extends TransformerAbstract {

    protected $availableIncludes = ['tasks', 'profile'];

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
    }

    public function includeTasks(User $user)
    {
        if ($user->tasks->count() > 0) {
            return $this->collection($user->tasks, new TaskTransformer);
        }
    }

    public function includeProfile(User $user)
    {
        return $this->item($user->profile, new ProfileTransformer);
    }

}