<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Task;
use App\Transformers\TaskTransformer;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use Dingo\Api\Exception\StoreResourceFailedException;
use Validator;

class TaskController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $task = new Task();

        if ($request->filled('include')) {
            $includes = explode($request->include);

            for ($i=0; $i < count($includes); $i++) { 
                $task = $task->with($includes[$i]);
            }
            unset($includes);
        }

        if ($request->filled('q')) {
            $task = $task->where('title', 'like', '%'.$request->q.'%');
        }

        if ($request->filled('user_id')) {
            $task = $task->whereHas('user', function($q) use($request) {
                $q->where('id', $request->user_id);
            });
        }

        return $this->response->collection($task->get(), new TaskTransformer);
    }

    public function show($id)
    {
        return $this->response->item(Task::findOrFail($id), new TaskTransformer());
    }

    public function store(Request $request)
    {
        $data = $request->all();
        validateRequest($data, [
            'title'     => 'required|string|max:50',
            'deadline'  => 'required'
        ]);

        $task = Task::create($data);

        return response()->json([
            'status' => 'ok',
            'message' => 'Task has been created!',
            'data' => $task
        ], 201);
    }

    public function update($id, Request $request)
    {
        $data = $request->all();
        validateRequest($data, [
            'title' => 'nullable|string|max:50',
            'deadline' => 'nullable'
        ]);

        $task = Task::find($id)->update($data);

        return response()->json([
            'status' => 'ok',
            'message' => 'Task has been updated!',
            'data' => $task
        ]);
    }

    public function delete($id)
    {
        $task = Task::with('subtasks')->findOrFail($id);
    
        if ($task->subtasks->count() > 0) {
            foreach ($$task->subtasks as $item) {
                $item->delete();
            }        
        }

        $task->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Task has been deleted!'
        ]);

    }
}
