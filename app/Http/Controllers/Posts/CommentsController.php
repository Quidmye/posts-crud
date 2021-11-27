<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\CommentRequest;
use App\Http\Requests\Posts\CommentUpdateRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function view($id)
    {
        return response()->json(
            Comment::with('replies')->findOrFail($id)
        );
    }

    public function postComments($postId, $commentId)
    {
        $comment = Comment::with('replies')->findOrFail($commentId);
        if (!is_null($postId) && $comment->post_id !== (int)$postId) {
            abort(404);
        }
        return response()->json(
            $comment
        );
    }

    public function create(CommentRequest $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $data = $request->except(['referenced_comment_id']);
        $data['post_id'] = $post->id;

        if ($request->has('referenced_comment_id')) {
            $parentComment = Comment::findOrFail($request->input('referenced_comment_id'));
            if ($parentComment->post_id !== (int)$postId) {
                abort(400);
            }
            $data['comment_id'] = $parentComment->id;
        }

        $comment = Comment::create($data);

        return response()->json($comment);
    }

    public function delete(int $resourceId, $id = null)
    {
        $commentId = is_null($id) ? $resourceId : $id;
        $comment = Comment::findOrFail($commentId);

        if(!is_null($id) && $comment->post_id !== $resourceId){
            abort(404);
        }

        $comment->delete();

        return response()->json('ok', 204);
    }

    public function update(CommentUpdateRequest $request, $postId, $commentId)
    {
        $comment = Comment::where('post_id', $postId)
            ->where('id', $commentId)
            ->firstOrFail();

        return $this->patch($request, $comment);

    }

    public function updateComment(CommentUpdateRequest $request, $id)
    {
        $comment = Comment::findOrFail($id);
        return $this->patch($request, $comment);
    }

    protected function patch(CommentUpdateRequest $request, Comment $comment)
    {
        $data = $request->except(['referenced_comment_id']);
        $comment->fill($data);
        $parentCommentId = $request->input('referenced_comment_id', $comment->comment_id);

        if (!is_null($parentCommentId)) {
            $parentComment = Comment::find($parentCommentId);
            if ($parentComment->post_id !== (int)$comment->post_id) {
                abort(400);
            }
        }

        $comment->comment_id = $parentCommentId;
        $comment->save();

        return response()->json($comment);
    }
}
