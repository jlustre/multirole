<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $featuredPosts = Post::published()->featured()->latest('published_at')->take(4)->get();
        // $featuredPosts = Cache::remember('featuredPosts', now()->addDay(), function () {
        //     return Post::published()->featured()->with('categories')->latest('published_at')->take(4)->get();
        // });

        $latestPosts = Cache::remember('latestPosts', now()->addDay(), function () {
            return Post::published()->latest('published_at')->take(8)->get();
            // return Post::published()->with('categories')->latest('published_at')->take(9)->get();
        });

        return view('posts.index', [
            'posts' => Post::take(6)->get(),
            'latestPosts' => $latestPosts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
