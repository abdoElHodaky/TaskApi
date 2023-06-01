<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function getUsersPosts()
    {
        $posts = auth()->user()->posts()->where('deleted_at', null)->get();
        $pinned = $posts->where('pinned', true);
        //get unpinned posts
        $unpinned = $posts->where('pinned', false);
        //merge pinned and unpinned posts
        $posts = $pinned->merge($unpinned);

        if (!$posts) {
            return response()->json([
                'status' => 'error',
                'message' => 'User has no posts'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }
    public function viewDeletedPosts()
    {
        $posts = auth()->user()->posts()->where('deleted_at', '!=', null)->get();
        if ($posts->count() == 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'User has no deleted posts'
            ], 200);
        }
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }
    public function restoreDeletedPosts($id)
    {
        $post = auth()->user()->posts()->where('id', $id)->first();
        $post->deleted_at = null;
        $post->deleted = false;
        $post->save();
        if (!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Post restored successfully'
        ]);
    }
}
