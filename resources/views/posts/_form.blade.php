<div class="mb-3">
    <label class="form-label" for="title">Title</label>
    <input
        class="form-control @error('title') is-invalid @enderror"
        id="title"
        name="title"
        type="text"
        maxlength="255"
        value="{{ old('title', $post?->title) }}"
        required>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="content">Content</label>
    <textarea
        class="form-control @error('content') is-invalid @enderror"
        id="content"
        name="content"
        rows="8"
        required>{{ old('content', $post?->content) }}</textarea>
    @error('content')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="author">Author</label>
    <input
        class="form-control @error('author') is-invalid @enderror"
        id="author"
        name="author"
        type="text"
        maxlength="255"
        value="{{ old('author', $post?->author) }}"
        required>
    @error('author')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="status">Status</label>
    <select
        class="form-select @error('status') is-invalid @enderror"
        id="status"
        name="status"
        required>
        <option value="draft" @selected(old('status', $post?->status ?? 'draft') === 'draft')>Draft</option>
        <option value="published" @selected(old('status', $post?->status) === 'published')>Published</option>
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
