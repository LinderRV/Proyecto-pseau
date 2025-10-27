<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::published()
                    ->with('author')
                    ->paginate(12);
        
        return view('blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published' => 'boolean',
        ]);

        $slug = Str::slug($request->title);
        $uniqueSlug = $slug;
        $counter = 1;
        
        // Ensure slug is unique
        while (Post::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = $slug . '-' . $counter;
            $counter++;
        }

        $post = new Post();
        $post->title = $request->title;
        $post->slug = $uniqueSlug;
        $post->content = $request->content;
        $post->excerpt = $request->excerpt;
        $post->published = $request->has('published');
        $post->user_id = Auth::id();
        
        if ($request->published) {
            $post->published_at = now();
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blog', 'public');
            $post->image = $imagePath;
        }

        $post->save();

        return redirect()->route('blog.show', $post->slug)
                         ->with('success', 'Artículo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
                    ->with('author')
                    ->firstOrFail();
        
        return view('blog.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        
        // Authorization check - only allow the author or admin to edit
        if ($post->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }
        
        return view('blog.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        
        // Authorization check - only allow the author or admin to update
        if ($post->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published' => 'boolean',
        ]);

        $wasPublished = $post->published;
        
        $post->title = $request->title;
        $post->content = $request->content;
        $post->excerpt = $request->excerpt;
        $post->published = $request->has('published');
        
        // Set published_at if post becomes published
        if (!$wasPublished && $post->published) {
            $post->published_at = now();
        }

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            
            $imagePath = $request->file('image')->store('blog', 'public');
            $post->image = $imagePath;
        }

        $post->save();

        return redirect()->route('blog.show', $post->slug)
                         ->with('success', 'Artículo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        
        // Authorization check - only allow the author or admin to delete
        if ($post->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }
        
        // Delete image if it exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        
        $post->delete();
        
        return redirect()->route('blog.index')
                         ->with('success', 'Artículo eliminado exitosamente.');
    }
    
    /**
     * Display only the user's posts for management
     */
    public function myPosts()
    {
        $posts = Post::where('user_id', Auth::id())
                     ->latest()
                     ->paginate(10);
        
        return view('blog.my-posts', compact('posts'));
    }
}
