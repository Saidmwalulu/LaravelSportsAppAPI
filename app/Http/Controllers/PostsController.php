<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    public function createPost(Request $request) {
        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->desc = $request->desc;

        if ($request->photo != '') {
            $photo = 'post_image'.time().'.'.$request->photo->extension();

            //Storage::put('posts', $request->photo);
            $request->photo->move(public_path('/uploads/posts/'),$photo);
            //file_put_contents('storage/posts'.$photo, base64_decode($request->photo));
            $post->photo = $photo;
        }

        $post->save();
        $post->user;

        return response()->json([
            'success' => true,
            'message' => 'posted',
            'post' => $post
        ]);

    }

    public function updatePost(Request $request) {
        $post = Post::find($request->id);
        if (Auth::user()->id != $post->user_id) {
            return response()->json([
                'success' => false,
                'message' => "can't edit this post"
            ]);
        }
        $post->desc = $request->desc;
        $post->update();
        return response()->json([
            'success' => true,
            'message' => 'post updated'
        ]);
    }

    public function deletePost(Request $request) {
        $post = Post::find($request->id);

         if (Auth::user()->id == $post->user_id || Auth::user()->role == 2) {
            $destination = public_path().'/uploads/posts/'.$post->photo;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $post->delete();
            return response()->json([
                'success' => true,
                'message' => 'post deleted'
            ]);

        }
        return response()->json([
            'success' => false,
            'message' => "can't delete this post"
        ]);

    }

    public function getPosts() {
        $posts = Post::orderBy('id','desc')->get();
        foreach ($posts as $post) {
            //get user with a post
            $post->user;
            //comments count
            $post['commentsCount'] = count($post->comments);
            //likes count
            $post['likesCount'] = count($post->likes);
            //check if user likes his own post
            $post['selfLike'] = false;
            foreach($post->likes as $like) {
                if ($like->user_id == Auth::user()->id) {
                    $post['selfLike'] = true;
                }
            }
        }
        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }
}
