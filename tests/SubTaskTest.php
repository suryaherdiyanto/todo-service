<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Transformers\SubTaskTransformer;

class SubTaskTest extends TestCase {

    use DatabaseMigrations;

    private $structure = [
        'id',
        'name',
        'is_completed'
    ];

    public function testGetSubTasksForUser()
    {
        $transformer = new SubTaskTransformer();

        $user = factory(App\User::class)->create();
        $task = factory(App\Task::class)->create([
            'user_id' => $user->id
        ]);

        $task->subtasks()->createMany(factory(App\SubTask::class, 10)->make()->toArray());
        $subtasksArray = $task->subtasks->map(function($item) use($transformer) {
            return $transformer->transform($item);
        });

        $this->actingAs($user)
            ->json('get', '/api/tasks/'.$task->id.'/subtasks')
            ->seeJsonEquals([
                'data' => $subtasksArray
            ])
            ->seeJsonStructure([
                'data' => [
                    '*' => $this->structure
                ]
            ]);
        $this->seeStatusCode(200);
    }

}