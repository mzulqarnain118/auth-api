<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class MyUserController extends Controller
{
    public function getById(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function getAll(Request $request)
    {
        $users = User::all();

        return response()->json($users);
    }

    public function updateById(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        return response()->json($user);
    }
}
