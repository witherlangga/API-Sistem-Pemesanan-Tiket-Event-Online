<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $query = User::query()->latest();

        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        $users = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => \App\Http\Resources\UserResource::collection($users)->response()->getData(true),
            'meta' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }
}
