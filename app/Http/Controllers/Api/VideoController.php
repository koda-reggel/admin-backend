<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\VideoResource;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    public function index()
    {
        $video = Video::get();
        if ($video->count() > 0) {
            return VideoResource::collection($video);
        } else {
            return response()->json(['message' => 'No video available'], 200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'image' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image,
            'duration' => $request->duration,
        ]);

        return response()->json([
            'message' => 'Video Created Successfully',
            'data' => new VideoResource($video)
        ], 200);
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return response()->json([
            'message' => 'Video Deleted Successfully',
        ], 200);
    }
}
