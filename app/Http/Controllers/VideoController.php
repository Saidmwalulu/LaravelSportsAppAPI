<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function createVideo(Request $request) {
        $video = new Video;
        $video->user_id = Auth::user()->id;
        $video->video_category = $request->video_category;
        $video->video_desc = $request->video_desc;
        $video->video_id = $request->video_id;

        $video->save();
        $video->user;

        return response()->json([
            'success' => true,
            'message' => 'video details uploaded',
            'video' => $video
        ]);
    }

    public function getVideos() {
         //get videos in a descending order as 'id','desc'
         $videos = Video::orderBy('id','desc')->get();

         foreach ($videos as $video) {
            //get user with a video
            $video->user;
        }

         return response()->json([
            'success' => true,
            'videos' => $videos
        ]);
    }

    public function editVideo (Request $request) {
        $video = Video::find($request->id);
        if (Auth::user()->id != $video->user_id) {
            return response()->json([
                'success' => false,
                'message' => "can't edit"
            ]);
        }

        $video->video_category = $request->video_category;
        $video->video_desc = $request->video_desc;
        $video->video_id = $request->video_id;

        $video->update();

        return response()->json([
            'success' => true,
            'message' => 'video updated'
        ]);
    }

    public function deleteVideo(Request $request) {
        $video = Video::find($request->id);
        if (Auth::user()->id != $video->user_id) {
            return response()->json([
                'success' => false,
                'message' => "can't delete"
            ]);
        }

        $video->delete();

        return response()->json([
            'success'=> true,
            'message' => 'video deleted'
        ]);
    }
}
