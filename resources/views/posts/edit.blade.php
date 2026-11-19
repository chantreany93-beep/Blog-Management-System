@extends('layouts.app')

@section('title', 'Edit Post - Blog Management')

@section('content')
    <h1 class="h2 mb-3">Edit Post</h1>

    <form class="card card-body" method="POST" action="{{ route('posts.update', $post) }}">
        @csrf
        @method('PUT')
        @include('posts._form', ['post' => $post])

        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save Changes</button>
            <a class="btn btn-outline-secondary" href="{{ route('posts.show', $post) }}">Cancel</a>
        </div>
    </form>
@endsection
