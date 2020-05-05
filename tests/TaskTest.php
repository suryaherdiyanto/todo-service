<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Task;

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

    public function testGetTask()
    {
        $user = factory(App\User::class)->create();
        $task = factory(Task::class)->create();

        $this->actingAs($user)
            ->json('get', '/api/tasks/'.$task->id)
            ->seeJsonEquals([
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'deadline' => $task->deadline->format('j F Y'),
                    'is_completed' => $task->isCompleted(),
                    'progress_integer' => $task->progress,
                    'progress_percentage' => $task->progressInPercentage(),
                    'created_at' => $task->created_at,
                    'updated_at' => $task->updated_at
                ]
            ])
            ->seeJsonStructure([
                'data' => $this->structure
            ]);
        
        $this->seeStatusCode(200);
    }

}