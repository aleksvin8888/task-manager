<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\CommentsResource;
use App\Models\Comment;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskCommentController extends Controller
{

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function show($taskId): JsonResponse|array
    {
        $task = Task::with([
            'comments',
            'comments.user',
        ])->whereId($taskId)
            ->first();

        if (!$task) {
            return response()->json([
                'message' => 'information not found',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return CommentsResource::collection($task->comments)->resolve();
    }

    public function store(Request $request, $taskId): JsonResponse|array
    {
        $task = Task::find($taskId);
        if (!$task) {
            return response()->json([
                'message' => 'information not found',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->validate([
            'content' => 'required|string|min:1|max:65535',
        ]);

        $comment = $this->taskService->addComment($task, $data);

        if ($comment) {
            return CommentsResource::make($comment)->resolve();
        } else {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id): JsonResponse
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json([
                'message' => 'information not found',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $comment->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
