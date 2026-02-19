<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matche;

class MatcheController extends Controller
{
    public function index()
    {
        return Matche::with(['homeTeam', 'awayTeam', 'tournamentGroup'])->get();
    }

    public function show($id)
    {
        return Matche::with(['homeTeam', 'awayTeam', 'tournamentGroup'])->findOrFail($id);
    }

    public function porGrupo($grupo)
    {
        return Matche::with(['homeTeam', 'awayTeam'])
            ->whereHas('tournamentGroup', fn($q) => $q->where('name', strtoupper($grupo)))
            ->get();
    }

    public function porStage($stage)
    {
        return Matche::with(['homeTeam', 'awayTeam'])->where('stage', $stage)->get();
    }
}