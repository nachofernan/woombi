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
            ->select('id', 'name', 'total_points')
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

    public function buscarPorMail(Request $request)
    {
        $data = $request->validate(['email' => 'required|string|min:2']);

        return User::where('email', 'like', '%' . $data['email'] . '%')
            ->select('id', 'name', 'email')
            ->limit(5)
            ->get();
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function setCampeon(Request $request)
    {
        $match1   = \App\Models\Matche::where('match_number', config('prode.match_kickoff'))->first();
        $match73  = \App\Models\Matche::where('match_number', config('prode.match_16avos'))->first();
        $match101 = \App\Models\Matche::where('match_number', config('prode.match_semis'))->first();

        if ($match101 && now() >= $match101->match_date) {
            return response()->json(['error' => 'Ya no se puede modificar el pronóstico de campeón'], 403);
        }

        $data = $request->validate([
            'champion_team_id' => 'required|integer|exists:teams,id',
        ]);

        $request->user()->update([
            'champion_team_id'    => $data['champion_team_id'],
            'champion_updated_at' => now(),
        ]);

        return response()->json(['message' => 'Pronóstico de campeón guardado']);
    }
}