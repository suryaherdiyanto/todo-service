<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Task;
use App\Transformers\TaskTransformer;

class TaskTest extends TestCase {

    use DatabaseMigrations;

    private $structure = [
        'id',
        'title',
        'deadline',
        'is_completed',
        'progress_integer',
        'progress_percentage',
        'created_at',
        'updated_at'
    ];

    public function testUnauthorizeIfUnauthenticated()
    {
        $this->json('get', '/api/tasks');
        $this->seeStatusCode(401);
    }

    public function testGetTasks()
    {
        $transformer = new TaskTransformer();

        $taskTransformed = [];
        $tasks = factory(Task::class, 5)->create()->map(function($task) use($transformer) {
            return $transformer->transform($task);
        });
        $user = factory(App\User::class)->create();

        $this->actingAs($user)
            ->json('get', '/api/tasks')
            ->seeJsonEquals([
                'data' => $tasks
            ])
            ->seeJsonStructure([
                'data' => [
                    '*' => $this->structure
                ]
            ]);
        $this->seeStatusCode(200);
    }

    public function testSearchTask()
    {
        $user = factory(App\User::class)->create();
        $task = factory(Task::class, 10)->create([
            'title' => 'the-title'
        ]);

        $this->actingAs($user)
            ->json('get', '/api/tasks?q=the')
            ->seeJsonStructure([
                'data' => [
                    '*' => $this->structure
                ]
            ]);
        $this->seeStatusCode(200);
        
    }

    public function testTaskPerUser()
    {
        $user = factory(App\User::class)->create();
        $tasks = factory(App\Task::class, 20)->make();

        $user->tasks()->createMany($tasks->toArray());

        $this->actingAs($user)
            ->json('get', '/api/tasks?user_id='.$user->id)
            ->seeJsonStructure([
                'data' => [
                    '*' => $this->structure
                ]
            ]);

        $this->seeStatusCode(200);
    }

    public function testGetTask()
    {
        $user = factory(App\User::class)->create();
        $task = factory(Task::class)->create();
        $transformer = new TaskTransformer();

        $this->actingAs($user)
            ->json('get', '/api/tasks/'.$task->id)
            ->seeJsonEquals([
                'data' => $transformer->transform($task)
            ])
            ->seeJsonStructure([
                'data' => $this->structure
            ]);
        
        $this->seeStatusCode(200);
    }

    public function testCreateTask()
    {
        $user = factory(App\User::class)->create();
        $task = factory(App\Task::class)->make([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('post', '/api/tasks', $task->toArray())
            ->seeJson([
                'meta' => [
                    'status' => 'ok',
                    'message' => 'Task has been created!'
                ]
            ]);
        $this->seeStatusCode(201);
        $this->seeInDatabase('tasks', $task->toArray());
    }

    public function testCreateValidationTask()
    {
        $data = [
            'note' => 'abcd'
        ];
        $user = factory(App\User::class)->create();

        $this->actingAs($user)
            ->json('post', '/api/tasks', $data)
            ->seeJson([
                'message' => 'Error sending request'
            ]);
        $this->seeStatusCode(422);

    }

    public function testUpdateTask()
    {
        $user = factory(App\User::class)->create();
        $task = factory(App\Task::class)->create([
            'user_id' => $user->id
        ]);

        $taskUpdateTo = factory(App\Task::class)->make([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('put', '/api/tasks/'.$task->id.'/update', $taskUpdateTo->toArray())
            ->seeJson([
                'status' => 'ok'
            ]);
        $this->seeStatusCode(200);
        $this->seeInDatabase('tasks', $taskUpdateTo->toArray());
    }

    public function testDeleteTaskWithoutSubTask()
    {
        $user = factory(App\User::class)->create();
        $task = factory(App\Task::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('delete', '/api/tasks/'.$task->id.'/delete', $task->toArray())
            ->seeJson([
                'status' => 'ok'
            ]);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('tasks', $task->toArray());
    }

}