@extends('layouts.app')

@section('title', 'Posts - Blog Management')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h2 mb-1">Blog Posts</h1>
            <p class="text-muted mb-0">Create and manage your posts.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('posts.create') }}">Create Post</a>
    </div>

    <form class="row g-2 mb-3" method="GET" action="{{ route('posts.index') }}">
        <div class="col-md-6">
            <label class="visually-hidden" for="search">Search by title</label>
            <input
                class="form-control"
                id="search"
                name="search"
                type="search"
                value="{{ $filters['search'] ?? '' }}"
                placeholder="Search by title">
        </div>
        <div class="col-md-3">
            <label class="visually-hidden" for="status">Filter by status</label>
            <select class="form-select" id="status" name="status">
                <option value="">All statuses</option>
                <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Draft</option>
                <option value="published" @selected(($filters['status'] ?? '') === 'published')>Published</option>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-primary" type="submit">Search / Filter</button>
            <a class="btn btn-outline-secondary" href="{{ route('posts.index') }}">Clear</a>
        </div>
    </form>

    @if ($posts->isEmpty())
        <div class="card card-body text-center text-muted">
            No posts found. Create your first post to get started.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle bg-white">
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Author</th>
                        <th scope="col">Status</th>
                        <th scope="col">Created</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>
                                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                                <div class="small text-muted">{{ $post->slug }}</div>
                            </td>
                            <td>{{ $post->author }}</td>
                            <td>
                                <span class="badge {{ $post->status === 'published' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td>{{ $post->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('posts.show', $post) }}">View</a>
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('posts.edit', $post) }}">Edit</a>
                                    <form
                                        method="POST"
                                        action="{{ route('posts.destroy', $post) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $posts->links() }}
    @endif
@endsection
