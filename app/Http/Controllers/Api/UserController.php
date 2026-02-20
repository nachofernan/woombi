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

    public function setCampeon(Request $request)
    {
        if (now() >= \Carbon\Carbon::parse('2026-06-11')) {
            return response()->json(['error' => 'Ya no se puede modificar el pronóstico de campeón'], 403);
        }

        $data = $request->validate([
            'champion_team_id' => 'required|integer|exists:teams,id',
        ]);

        $request->user()->update($data);

        return response()->json(['message' => 'Pronóstico de campeón guardado']);
    }
}