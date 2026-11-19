<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_can_be_created_with_an_automatically_generated_slug(): void
    {
        $response = $this->post(route('posts.store'), [
            'title' => 'My First Post',
            'content' => 'This is the post content.',
            'author' => 'Alex',
            'status' => 'draft',
        ]);

        $post = Post::query()->firstOrFail();

        $response->assertRedirect(route('posts.show', $post));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'slug' => 'my-first-post',
            'status' => 'draft',
        ]);
    }

    public function test_duplicate_titles_receive_unique_slugs(): void
    {
        $attributes = [
            'title' => 'Same Title',
            'content' => 'Post content.',
            'author' => 'Alex',
            'status' => 'published',
        ];

        $this->post(route('posts.store'), $attributes)->assertRedirect();
        $this->post(route('posts.store'), $attributes)->assertRedirect();

        $this->assertDatabaseHas('posts', ['slug' => 'same-title']);
        $this->assertDatabaseHas('posts', ['slug' => 'same-title-2']);
    }

    public function test_post_can_be_viewed_updated_and_deleted(): void
    {
        $post = $this->createPost();

        $this->get(route('posts.show', $post))
            ->assertOk()
            ->assertSee('Test post content.');

        $this->put(route('posts.update', $post), [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
            'author' => 'Sam',
            'status' => 'published',
        ])->assertRedirect(route('posts.show', $post));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'content' => 'Updated content.',
            'author' => 'Sam',
            'status' => 'published',
        ]);

        $this->delete(route('posts.destroy', $post))
            ->assertRedirect(route('posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_required_fields_and_status_are_validated(): void
    {
        $this->from(route('posts.create'))
            ->post(route('posts.store'), [
                'title' => '',
                'content' => '',
                'author' => '',
                'status' => 'invalid',
            ])
            ->assertSessionHasErrors(['title', 'content', 'author', 'status']);
    }

    public function test_posts_can_be_searched_and_filtered_by_status(): void
    {
        $this->createPost([
            'title' => 'Laravel Search Draft',
            'slug' => 'laravel-search-draft',
            'status' => 'draft',
        ]);
        $this->createPost([
            'title' => 'Laravel Search Published',
            'slug' => 'laravel-search-published',
            'status' => 'published',
        ]);
        $this->createPost([
            'title' => 'Docker Published',
            'slug' => 'docker-published',
            'status' => 'published',
        ]);

        $this->get(route('posts.index', [
            'search' => 'Laravel Search',
            'status' => 'published',
        ]))
            ->assertOk()
            ->assertSee('Laravel Search Published')
            ->assertDontSee('Laravel Search Draft')
            ->assertDontSee('Docker Published');
    }

    public function test_posts_are_paginated(): void
    {
        for ($number = 1; $number <= 11; $number++) {
            $this->createPost([
                'title' => "Post {$number}",
                'slug' => "post-{$number}",
            ]);
        }

        $this->get(route('posts.index'))
            ->assertOk()
            ->assertViewHas('posts', fn ($posts) => $posts->count() === 10);

        $this->get(route('posts.index', ['page' => 2]))
            ->assertOk()
            ->assertViewHas('posts', fn ($posts) => $posts->count() === 1);
    }

    private function createPost(array $overrides = []): Post
    {
        return Post::create(array_merge([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'content' => 'Test post content.',
            'author' => 'Taylor',
            'status' => 'draft',
        ], $overrides));
    }
}
