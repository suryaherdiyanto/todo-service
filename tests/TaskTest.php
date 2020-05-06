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

    public function testGetTasks()
    {
        $tasks = factory(Task::class, 5)->create();
        $user = factory(App\User::class)->create();

        $this->actingAs($user)
            ->json('get', '/api/tasks')
            ->seeJsonStructure([
                'data' => [
                    '*' => $this->structure
                ]
            ]);

        $this->assertEquals(5, count($tasks));
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

}