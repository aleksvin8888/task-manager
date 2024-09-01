<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\TeamResource;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller
{

    public function index(): array
    {
       $teams = Auth::user()->teams()->get();
       return TeamResource::collection($teams)->resolve();
    }

    public function store(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|min:1|max:255',
        ]);

        $team = Team::create($data);

        return TeamResource::make($team)->resolve();
    }


    public function addUser($teamId, $userId): JsonResponse
    {
        $team = Team::find($teamId);
        if (!$team) {
            return response()->json([
                'message' => 'your team was not found',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'unknown user',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $team->users()->attach($user);
            return response()->json([
                'message' => 'user ' .$user->name. ' Added to the team ' . $team->name,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function excludeUser($teamId, $userId)
    {
        $team = Team::find($teamId);
        if (!$team) {
            return response()->json([
                'message' => 'your team was not found',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'unknown user',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $team->users()->detach($user);
            return response()->json([
                'message' => 'user ' .$user->name. ' is removed from the team ' . $team->name,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
