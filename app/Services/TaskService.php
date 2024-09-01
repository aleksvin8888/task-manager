<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\Statuses;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class TaskService
{

    /**
     * @throws Exception
     */
    public function create(array $data): ?Task
    {
        DB::beginTransaction();
        try {

            $data['user_id'] = Auth::user()->id;
            $data['status'] = Statuses::New->value;
            $task = Task::create($data);

            DB::commit();

            return $task;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw new Exception($exception->getMessage(), 500, $exception);
        }
    }

    /**
     * @throws Exception
     */
    public function update(Task $task, array $data): bool
    {
        DB::beginTransaction();
        try {

            $res = $task->update($data);

            DB::commit();

            return $res;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw new Exception($exception->getMessage(), 500, $exception);
        }
    }


    /**
     * @throws Exception
     */
    public function addComment(Task $task, array $data): ?Comment
    {
        DB::beginTransaction();
        try {
            $comment = Comment::create([
                'content' => $data['content'],
                'task_id' => $task->id,
                'user_id' => Auth::user()->id
            ]);

            DB::commit();

            return $comment;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw new Exception($exception->getMessage(), 500, $exception);
        }
    }

}
