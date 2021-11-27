<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class CommentObserver
{
    /**
     * Handle the Comment "creating" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function creating(Comment $comment)
    {
        $comment->level = is_null($comment->referencedComment) ? 0 : intval($comment->referencedComment->level)+1;
        Post::where('id', $comment->post_id)
            ->update([
            'comments_count' => DB::raw('comments_count+1')
            ]);
    }

    /**
     * Handle the Comment "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        //
    }

    /**
     * Handle the Comment "updated" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        //
    }

    /**
     * Handle the Comment "restored" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        //
    }
}
