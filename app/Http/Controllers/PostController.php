<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostupdateRequest;
use App\Models\Post;
use App\Models\PostTags;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function index()
    {
        $posts = auth()->user()->posts;
        $posts->map(function ($post) {
            $postTags = PostTags::where('post_id', $post->id)->get();
            $tags = [];
            $postTags->map(function ($postTag) use (&$tags) {
                $tags[] = Tag::find($postTag->tag_id);
            });
            $post->tags = $tags;
        });
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }
    public function store(PostStoreRequest $request)
    {
        $auth = auth()->user();
        $post = new \App\Models\Post;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = $auth->id;
        $post->coverImage = $request->coverImage->store('images', 'public');
        $post->save();
        //update cache
        $posts = \App\Models\Post::count();
        Cache::put('posts', $posts, 60);
        if($request->tag_id == null){
            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully'
            ]);
        }else{
            foreach ($request->tag_id as $tag) {
                PostTags::create([
                    'post_id' => $post->id,
                    'tag_id' => $tag
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully'
            ]);

        }
    }
    public function show($id)
    {
        $post = \App\Models\Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $post
        ]);
    }
    public function update(PostupdateRequest $request, $id)
    {
        $post = \App\Models\Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
        }
        $post->title = $request->title;
        $post->body = $request->body;
        $post->coverImage = $request->coverImage->store('images', 'public');
        $post->save();
        $postTag = PostTags::where('post_id', $id)->get();
        if (!$postTag) {
            foreach ($request->tag_id as $tag) {
                PostTags::create([
                    'post_id' => $post->id,
                    'tag_id' => $tag
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Post updated successfully'
            ]);
        }else{
            $postTag->delete();
            foreach ($request->tag_id as $tag) {
                PostTags::create([
                    'post_id' => $post->id,
                    'tag_id' => $tag
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Post updated successfully'
            ]);
        }
    }
    public function destroy($id)
    {
        $post = \App\Models\Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
        }
        $post->deleted = 1;
        $post->deleted_at = now();
        $post->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Post deleted successfully'
        ]);
    }

}
