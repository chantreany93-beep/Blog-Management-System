@extends('layouts.app')

@section('title', $post->title.' - Blog Management')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h1 class="h2 mb-1">{{ $post->title }}</h1>
            <p class="text-muted mb-0">
                By {{ $post->author }} · {{ $post->created_at->format('M d, Y') }}
                · <span class="badge {{ $post->status === 'published' ? 'text-bg-success' : 'text-bg-secondary' }}">
                    {{ ucfirst($post->status) }}
                </span>
            </p>
            <div class="small text-muted mt-1">Slug: {{ $post->slug }}</div>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('posts.edit', $post) }}">Edit</a>
            <a class="btn btn-outline-primary" href="{{ route('posts.index') }}">Back to Posts</a>
        </div>
    </div>

    <article class="card">
        <div class="card-body" style="white-space: pre-line;">{{ $post->content }}</div>
    </article>

    <form
        class="mt-3"
        method="POST"
        action="{{ route('posts.destroy', $post) }}"
        onsubmit="return confirm('Are you sure you want to delete this post?');">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" type="submit">Delete Post</button>
    </form>
@endsection
