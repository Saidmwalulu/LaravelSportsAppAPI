<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function createComment(Request $request) {
        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $request->id;
        $comment->comment = $request->comment;
        $comment->save();

        $comment->user;

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'message' => 'commented'
        ]);
    }

    public function updateComment(Request $request) {
        $comment = Comment::find($request->id);
        if ($comment->id != Auth::user()->id) {
            return response()->json([
                'success' => fail,
                'message' => "can't edit comment"
            ]);
        }
        $comment->comment = $request->comment;
        $comment->update();
        return response()->json([
            'success' => true,
            'message' => 'comment edited'
        ]);
    }

    public function deleteComment(Request $request) {
        $comment = Comment::find($request->id);

        if (Auth::user()->id == $comment->user_id || Auth::user()->role == 2) {
            
            $comment->comment = $request->comment;
            $comment->delete();
            return response()->json([
                'success' => true,
                'message' => 'comment deleted'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "can't delete comment"
        ]);
    }

    public function getComments(Request $request) {
        $comments = Comment::where('post_id', $request->id)->get();
        //show user for each comment
        foreach($comments as $comment) {
            $comment->user;
        }
        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
}
