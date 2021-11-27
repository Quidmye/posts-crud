<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::factory()
            ->count(100)
            ->has(
                Comment::factory()
                    ->count(random_int(20, 50))
                    ->has(
                        Comment::factory()
                            ->state(function (array $attributes, Comment $comment) {
                                return ['post_id' => $comment->post_id];
                            })
                            ->count(random_int(10, 20))
                            ->has(
                                Comment::factory()
                                    ->state(function (array $attributes, Comment $comment) {
                                        return ['post_id' => $comment->post_id];
                                    })
                                ->count(random_int(2, 8)),
                                'replies'
                            ),'replies'
                    )
            )
            ->create();
    }
}
