<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = Users::get();
        if ($users->count() > 0) {
            return UserResource::collection($users);
        } else {
            return response()->json(['message' => 'No record available'], 200);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'skills' => 'string|max:255',
            'sub_skills' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $user = Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'location' => $request->location,
            'status' => $request->status,
            'skills' => $request->skills,
            'sub_skills' => $request->sub_skills,
        ]);

        return response()->json([
            'message' => 'User Created Successfully',
            'data' => new UserResource($user)
        ], 200);
    }
    public function show(Users $user)
    {
        return new UserResource($user);
    }
    public function update(Request $request, Users $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'skills' => 'required|string|max:255',
            'sub_skills' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'location' => $request->location,
            'status' => $request->status,
            'skills' => $request->skills,
            'sub_skills' => $request->sub_skills,
        ]);

        return response()->json([
            'message' => 'User Updated Successfully',
            'data' => new UserResource($user)
        ], 200);
    }
    public function destroy(Users $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'User Deleted Successfully',
        ], 200);
    }
}
