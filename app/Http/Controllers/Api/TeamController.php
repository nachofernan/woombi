<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;

class TeamController extends Controller
{
    public function index()
    {
        return Team::orderBy('name')->get();
    }

    public function show($id)
    {
        return Team::findOrFail($id);
    }
}
