<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiResponse;

class OrganizerController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $organizers = User::where('role', 'organizer')->select(['id','name','email'])->paginate(10);

        return $this->success('Daftar organizer', $organizers);
    }
}
