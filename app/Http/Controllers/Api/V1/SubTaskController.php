<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Transformers\SubTaskTransformer;
use App\SubTask;
use App\Task;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

class SubTaskController extends Controller
{

    use Helpers;


    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index($taskId, Request $request)
    {
        $task = Task::with('subtasks')->orderBy('created_at')->findOrFail($taskId);
        
        return $this->response->collection($task->subtasks, new SubTaskTransformer);
    }

    public function store($taskId, Request $request)
    {
        $data = $request->all();
        validateRequest($data, [
            'name' => 'required|string|max:50',
            'task_id' => 'required|integer'
        ]);

        if (!isset($data['is_completed'])) {
            $data['is_completed'] = 0;
        }

        $subtask = SubTask::create($data);
        unset($data);

        return $this->response->item($subtask, new SubTaskTransformer)
                    ->setMeta(['status' => 'ok', 'message' => 'Subtask created!'])
                    ->setStatusCode(201);
    }

    public function update($id, Request $request)
    {
        $data = $request->all();
        validateRequest($data, [
            'name' => 'required|string|max:50'
        ]);

        $subtask = SubTask::findOrFail($id)->update($request->only(['name', 'is_completed']));
        unset($data);

        return response()->json([
            'status' => 'ok',
            'message' => 'Subtask updated!'
        ], 200);
    }

    public function delete($id, Request $request)
    {
        SubTask::findOrFail($id)->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Subtask updated!'
        ], 200);
    }
}
