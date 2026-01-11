<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $query = ActivityLog::query()->latest();

        if ($action = $request->query('action')) {
            $query->where('action', $action);
        }
        if ($userId = $request->query('user_id')) {
            $query->where('user_id', $userId);
        }

        $logs = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => $logs->items(),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ]
        ]);
    }
}
