<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task; 

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())->paginate(10);
        return $tasks;
    }
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

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tasks,id',
        ]);
    
        $task = Task::find($request->input('id'));
    
        if (!$task) {
            return redirect()->route('homepage')->withErrors(['error' => 'Task not found']);
        }
    
        if ($task->user_id != Auth::id()) {
            return redirect()->route('homepage')->withErrors(['error' => 'You do not have permission to delete this task']);
        }
    
        if ($task->delete()) {
            return redirect()->route('homepage')->withSuccess('Task deleted successfully');
        } else {
            return redirect()->route('homepage')->withErrors(['error' => 'Unable to delete task']);
        }
    }
    
}
