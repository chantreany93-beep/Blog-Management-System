@extends('layouts.app')

@section('title', 'Create Post - Blog Management')

@section('content')
    <h1 class="h2 mb-3">Create Post</h1>

    <form class="card card-body" method="POST" action="{{ route('posts.store') }}">
        @csrf
        @include('posts._form', ['post' => null])

        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Create Post</button>
            <a class="btn btn-outline-secondary" href="{{ route('posts.index') }}">Cancel</a>
        </div>
    </form>
@endsection
