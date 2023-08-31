<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function likePost(Request $request) {
        $like = Like::where('post_id', $request->id)->where('user_id', Auth::user()->id)->get();
        //check if returns 0 then post not liked therefore should be liked else unliked
        if (count($like)>0) {
            //we cant have likes more than one
            $like[0]->delete();
            return response()->json([
                'success' => false,
                'message' => 'unliked'
            ]);
        }
        $like = new Like;
        $like->user_id = Auth::user()->id;
        $like->post_id = $request->id;
        $like->save();
        return response()->json([
            'success' => true,
            'message' => 'liked'
        ]);
    }
}
