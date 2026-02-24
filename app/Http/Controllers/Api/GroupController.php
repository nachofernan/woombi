<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = $request->user()->groups()->with('users')->get();

        return $groups->map(fn($group) => [
            'id'          => $group->id,
            'name'        => $group->name,
            'owner_id'    => $group->owner_id,
            'invite_code' => $group->invite_code,
            'users'       => $group->users->map(fn($user) => [
                'id'           => $user->id,
                'name'         => $user->name,
                'total_points' => $user->total_points,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);

        $group = Group::create([
            'name'        => $data['name'],
            'owner_id'    => $request->user()->id,
            'invite_code' => Str::random(8),
        ]);

        $group->users()->attach($request->user()->id);

        return response()->json($group, 201);
    }

    public function show($id)
    {
        $group = Group::with('users')->findOrFail($id);

        return response()->json([
            'id'          => $group->id,
            'name'        => $group->name,
            'owner_id'    => $group->owner_id,
            'invite_code' => $group->invite_code,
            'users'       => $group->users->map(fn($user) => [
                'id'           => $user->id,
                'name'         => $user->name,
                'total_points' => $user->total_points,
            ]),
        ]);
    }

    public function unirse(Request $request)
    {
        $data  = $request->validate(['invite_code' => 'required|string']);
        $group = Group::where('invite_code', $data['invite_code'])->firstOrFail();
        $user  = $request->user();

        if ($group->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Ya sos miembro de este grupo'], 409);
        }

        $group->users()->attach($user->id);

        return response()->json($group);
    }

    public function posiciones($id)
    {
        $group = Group::findOrFail($id);

        $posiciones = $group->users()
            ->orderByDesc('total_points')
            ->get()
            ->map(fn($user) => [
                'name'         => $user->name,
                'total_points' => $user->total_points,
            ]);

        return response()->json($posiciones);
    }

    public function agregarUsuario(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        if ($group->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Solo el administrador puede agregar usuarios'], 403);
        }

        $data = $request->validate(['user_id' => 'required|integer|exists:users,id']);

        if ($group->users()->where('user_id', $data['user_id'])->exists()) {
            return response()->json(['error' => 'El usuario ya es miembro del grupo'], 409);
        }

        $group->users()->attach($data['user_id']);

        return response()->json(['message' => 'Usuario agregado al grupo']);
    }

    public function agregarPorMail(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        if ($group->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Solo el administrador puede agregar usuarios'], 403);
        }

        $data = $request->validate(['email' => 'required|email']);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json(['error' => 'No existe ningún usuario registrado con ese mail'], 404);
        }

        if ($group->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'El usuario ya es miembro del grupo'], 409);
        }

        $group->users()->attach($user->id);

        return response()->json(['message' => 'Usuario agregado al grupo', 'user' => ['id' => $user->id, 'name' => $user->name]]);
    }

    public function quitarUsuario(Request $request, $id, $user_id)
    {
        $group = Group::findOrFail($id);

        if ($group->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Solo el administrador puede quitar usuarios'], 403);
        }

        if ($group->owner_id == $user_id) {
            return response()->json(['error' => 'El administrador no puede quitarse a sí mismo'], 422);
        }

        $group->users()->detach($user_id);

        return response()->json(['message' => 'Usuario eliminado del grupo']);
    }

    public function salir(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $user  = $request->user();

        if ($group->owner_id === $user->id) {
            return response()->json(['error' => 'El administrador no puede abandonar el grupo. Eliminalo si ya no lo necesitás.'], 422);
        }

        $group->users()->detach($user->id);

        return response()->json(['message' => 'Saliste del grupo']);
    }

    public function destroy(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        if ($group->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Solo el administrador puede eliminar el grupo'], 403);
        }

        $group->users()->detach();
        $group->delete();

        return response()->json(['message' => 'Grupo eliminado']);
    }
}