<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\CommentsResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request): array
    {
        $status = $request->query('status');

        $query = Auth::user()->tasks();

        if ($status) {
            $query->where('status', $status);
        }

        $tasks = $query->get();

        return TaskResource::collection($tasks)->resolve();
    }

    public function show($id): JsonResponse|array
    {
        $task = Task::with([
            'comments',
            'comments.user',
            'user',
            'team',
            ])->whereId($id)->first();

        if ($task) {
            return response()->json([
                'task' => TaskResource::make($task)->resolve(),
                'comments' => CommentsResource::collection($task->comments)->resolve()
            ]);
        } else {
            return response()->json([
                'message' => 'information not found',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function store(TaskStoreRequest $request): JsonResponse|array
    {
        $data = $request->validated();
        $task = $this->taskService->create($data);

        if ($task) {
              return TaskResource::make($task)->resolve();
        } else {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function update(TaskUpdateRequest $request, $id): JsonResponse|array
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'message' => 'information not found',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->validated();

        $res = $this->taskService->update($task, $data);

        if ($res) {
            return TaskResource::make($task)->resolve();
        } else {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id): JsonResponse
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'message' => 'information not found',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $task->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
