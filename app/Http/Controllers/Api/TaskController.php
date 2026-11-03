<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;


class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = $request->user()
            ->tasks()
            ->latest()
            ->paginate(10);

        return TaskResource::collection($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'sometimes|in:todo,done'
        ]);

        $task = $request->user()->tasks()->create($validated);
        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    public function show(Request $request, int $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        return new TaskResource($task);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'sometimes|in:todo,done'
        ]);

        $task = $request->user()->tasks()->findOrFail($id);
        $task->update($validated);
        return new TaskResource($task->refresh());
    }

    public function destroy(Request $request, int $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        $task->delete();
        return response()->json([
            'message' => 'Task berhasil dihapus',
        ], 200);
    }
    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,done',
        ]);

        $task = $request->user()
            ->tasks()
            ->findOrFail($id);

        $task->update([
            'status' => $validated['status'],
        ]);

        return new TaskResource($task->refresh());
    }
}
