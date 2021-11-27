<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseMigrations;

//    /**
//     * A basic feature test example.
//     *
//     * @return void
//     */
//    public function test_example()
//    {
//        $response = $this->get('/');
//
//        $response->assertStatus(200);
//    }

    public function test_create_post_unauthenticated()
    {
        $unauthenticated = $this->post('/api/posts',
            [
                'text' => 'Я і ти зустрілися знов...',
                'image' => 'https://images.unsplash.com/photo-1553613599-d0f3dd9416ae'
            ],
            [
                'Accept' => 'application/json'
            ]
        );
        $unauthenticated->assertStatus(401);
    }

    public function test_create_post_authenticated()
    {
        Sanctum::actingAs(
            User::factory()->create()
        );
        $authenticated = $this->post('/api/posts',
            [
                'text' => 'Я і ти зустрілися знов...',
                'image' => 'https://images.unsplash.com/photo-1553613599-d0f3dd9416ae'
            ],
            [
                'Accept' => 'application/json'
            ]
        );
        $authenticated->assertStatus(200);
    }

    public function test_create_post_invalid_data()
    {
        Sanctum::actingAs(
            User::factory()->create()
        );
        $post = $this->post('/api/posts',
            [
                'image' => 'https://images.unsplash.com/photo-1553613599-d0f3dd9416ae'
            ],
            [
                'Accept' => 'application/json'
            ]
        );
        $post->assertStatus(422);
    }

    public function test_view_post()
    {
        Sanctum::actingAs(
            User::factory()->create()
        );
        Post::factory()->count(1)->create();
        $post = $this->get('/api/posts/1', [
            'Accept' => 'application/json'
        ]);
        $post->assertStatus(200);
    }
}
