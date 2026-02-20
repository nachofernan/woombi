<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matche;
use App\Models\Prediction;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()
            ->predictions()
            ->with('match.homeTeam', 'match.awayTeam')
            ->get();
    }

    public function update(Request $request, $match_id)
    {
        $match = Matche::findOrFail($match_id);

        if ($match->match_date <= now()) {
            return response()->json(['error' => 'El partido ya comenzó'], 403);
        }

        $data = $request->validate([
            'predicted_home_score' => 'required|integer|min:0|max:20',
            'predicted_away_score' => 'required|integer|min:0|max:20',
            'predicted_winner_team_id' => 'nullable|integer|exists:teams,id',
        ]);

        $prediction = Prediction::updateOrCreate(
            ['user_id' => $request->user()->id, 'match_id' => $match_id],
            $data
        );

        return response()->json($prediction);
    }
}