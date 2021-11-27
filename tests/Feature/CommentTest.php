<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentTest extends TestCase
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

    public function test_create_comment_unauthenticated(){
        Post::factory()
            ->has(Comment::factory()->count(2))
            ->count(1)
            ->create();
        $post = $this->post('/api/posts/1/comments',
            [
                "text" => "Я і ти зустрілися знов...",
                "user_name" => "Шопен",
                "referenced_comment_id" => 1
            ],
            [
                'Accept' => 'application/json'
            ]
        );
        $post->assertStatus(401);
    }

    public function test_create_comment_authenticated(){
        Sanctum::actingAs(
            User::factory()->create()
        );
        $records = Post::factory()
            ->has(Comment::factory()->count(15))
            ->count(1)
            ->create();
        $post = $this->post("/api/posts/{$records[0]->id}/comments",
            [
                "text" => "Я і ти зустрілися знов...",
                "user_name" => "Шопен",
                "referenced_comment_id" => $records[0]->comments[0]->id
            ],
            [
                'Accept' => 'application/json'
            ]
        );
        $post->assertStatus(200);
    }

    public function test_create_bad_data(){
        Sanctum::actingAs(
            User::factory()->create()
        );
        $records = Post::factory()
            ->has(Comment::factory()->count(15))
            ->count(1)
            ->create();
        $post = $this->post("/api/posts/{$records[0]->id}/comments",
            [
                "text" => "Я і ти зустрілися знов...",
                "user_name" => "",
                "referenced_comment_id" => $records[0]->comments[0]->id
            ],
            [
                'Accept' => 'application/json'
            ]
        );
        $post->assertStatus(422);
    }

    public function test_create_bad_referenced_comment_id(){
        Sanctum::actingAs(
            User::factory()->create()
        );
        $records = Post::factory()
            ->has(Comment::factory()->count(15))
            ->count(2)
            ->create();
        $post = $this->post("/api/posts/{$records[0]->id}/comments",
            [
                "text" => "Я і ти зустрілися знов...",
                "user_name" => "Шопен",
                "referenced_comment_id" => $records[1]->comments[0]->id
            ],
            [
                'Accept' => 'application/json'
            ]
        );
        $post->assertStatus(400);
    }
}
