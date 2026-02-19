<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function leaderboard()
    {
        return User::orderByDesc('total_points')
            ->select('name', 'total_points')
            ->get();
    }

    public function buscar(Request $request)
    {
        $data = $request->validate(['query' => 'required|string|min:2']);

        return User::where('name', 'like', '%' . $data['query'] . '%')
            ->select('id', 'name', 'total_points')
            ->limit(10)
            ->get();
    }
}