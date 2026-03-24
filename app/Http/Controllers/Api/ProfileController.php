<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user()->only('id', 'name', 'email', 'role', 'dietary_tags'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $request->user()->update(['dietary_tags' => $request->dietary_tags]);
        return response()->json($request->user()->fresh()->only('id', 'name', 'email', 'role', 'dietary_tags'));
    }
}
