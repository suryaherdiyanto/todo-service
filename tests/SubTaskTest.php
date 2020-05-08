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

    public function testUnauthorizeIfUnauthenticated()
    {
        $this->json('get', '/api/tasks');
        $this->seeStatusCode(401);
    }

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

    public function testEditSubTask()
    {
        $user = factory(App\User::class)->create();
        $task = factory(App\Task::class)->create([
            'user_id' => $user->id
        ]);

        $subtask = $task->subtasks()->create(factory(App\SubTask::class)->make(['task_id' => $task->id])->toArray());

        $updatedTo = factory(App\SubTask::class)->make([
            'task_id' => $task->id
        ]);

        $this->actingAs($user)
            ->json('put', '/api/tasks/subtasks/'.$subtask->id.'/update', $updatedTo->toArray())
            ->seeJson([
                'status' => 'ok'
            ]);
        $this->seeStatusCode(200);
        $this->seeInDatabase('sub_tasks', $updatedTo->toArray());
    }

    public function testDeleteSubTask()
    {
        $user = factory(App\User::class)->create();
        $task = factory(App\Task::class)->create([
            'user_id' => $user->id
        ]);

        $subtask = $task->subtasks()->create(factory(App\SubTask::class)->make(['task_id' => $task->id])->toArray());

        $this->actingAs($user)
            ->json('delete', '/api/tasks/subtasks/'.$subtask->id.'/delete')
            ->seeJson([
                'status' => 'ok'
            ]);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('sub_tasks', $subtask->toArray());
    }

}