<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('owner', 'users')->where('owner_id', Auth::id())->get();

        return TeamResource::collection($teams);
    }

    public function store(CreateTeamRequest $request)
    {
        $team = Team::create([
            'owner_id' => Auth::id(),
            'name' => $request->input('name'),
        ]);

        $team->load('owner', 'users');

        return response()->json([
            new TeamResource($team),
        ], 201);
    }

    public function show($id)
    {
        // Logic to show a specific team
    }

    public function update(Request $request, $id)
    {
        // Logic to update a specific team
    }

    public function destroy($id)
    {
        // Logic to delete a specific team
    }
}
