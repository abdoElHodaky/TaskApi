<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function index()
    {
        $tags = \App\Models\Tag::all();
        return response()->json([
            'status' => 'success',
            'data' => $tags
        ]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:tags'
        ]);
        $tag = new \App\Models\Tag;
        $tag->name = $validatedData['name'];
        $tag->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Tag created successfully'
        ]);
    }
    public function show($id)
    {
        $tag = \App\Models\Tag::find($id);
        if (!$tag) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tag not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $tag
        ]);
    }
    public function update(Request $request, $id)
    {
        $tag = \App\Models\Tag::find($id);
        if (!$tag) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tag not found'
            ], 404);
        }
        $validatedData = $request->validate([
            'name' => 'required|unique:tags'
        ]);
        $tag->name = $validatedData['name'];
        $tag->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Tag updated successfully'
        ]);
    }
    public function destroy($id)
    {
        $tag = \App\Models\Tag::find($id);
        if (!$tag) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tag not found'
            ], 404);
        }
        $tag->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Tag deleted successfully'
        ]);
    }
}
