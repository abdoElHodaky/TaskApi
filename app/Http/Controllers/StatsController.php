<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function __construct()
    {
         $this->middleware('auth:sanctum');
    }
    public function numberOfUsers()
    {
        $users = Cache::get('users');
        if($users){
            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);
        }else{
            $users = \App\Models\User::count();
            Cache::put('users', $users, 300);
            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);
        }

    }
    public function numberOfPosts()
    {
        $posts = Cache::get('posts');
        if($posts){
            return response()->json([
                'status' => 'success',
                'data' => $posts
            ]);
        }else{
            $posts = \App\Models\Post::count();
            Cache::put('posts', $posts, 300);
            return response()->json([
                'status' => 'success',
                'data' => $posts
            ]);
        }
    }
    public function usersHasNoPosts()
    {
        $users = Cache::get('usersHasNoPosts');
        if($users){
            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);
        }else{
            $users = \App\Models\User::doesntHave('posts')->get();
            Cache::put('usersHasNoPosts', $users, 300);
            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);
        }
    }
}
