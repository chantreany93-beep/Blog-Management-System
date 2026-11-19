<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:draft,published'],
        ]);

        $query = Post::query();

        if (! empty($filters['search'])) {
            $query->where('title', 'like', '%'.$filters['search'].'%');
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $posts = $query->latest()->paginate(10)->withQueryString();

        return view('posts.index', compact('posts', 'filters'));
    }

    public function create(): View
    {
        return view('posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'author' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['title']);
        $post = Post::create($validated);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post created successfully.');
    }

    public function show(Post $post): View
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'author' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['title'], $post);
        $post->update($validated);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    private function uniqueSlug(string $title, ?Post $post = null): string
    {
        $baseSlug = Str::slug($title);

        if ($baseSlug === '') {
            $baseSlug = 'post';
        }

        $slug = $baseSlug;
        $suffix = 2;

        while (true) {
            $query = Post::query()->where('slug', $slug);

            if ($post !== null) {
                $query->where('id', '!=', $post->getKey());
            }

            if (! $query->exists()) {
                return $slug;
            }

            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }
    }
}
