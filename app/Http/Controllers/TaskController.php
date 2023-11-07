<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task; 

class TaskController extends Controller
{
    public function addtask(Request $request)
    {

      
        $request->validate([
            'task' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $task = new Task([
            'user_id' => $request->input('user_id'),
            'task' => $request->input('task'),
        ]);
        if($task->save()){
            return response()->json([
                'task' => $task,
                'status' => 1,
                'message' => 'Successfully created a task',
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Task Creation Failed',
            ]);
        }
    }

    public function changestatus(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|in:pending,done',
        ]);
    
        $task = Task::find($request->input('task_id'));
    
        if ($task->user_id == null) {
            return response()->json([
                'status' => 0,
                'message' => 'You do not have permission to update this task',
            ], 403);
        }
    
        if ($task->status === 'pending' && $request->input('status') === 'done') {
            $task->status = $request->input('status');
            
            if ($task->save()) {
                return response()->json([
                    'status' => 1,
                    'message' => 'Marked task as done',
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Unable to Update',
                ]);
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Task is not pending, status remains unchanged.',
            ]);
        }
    }
    
}
