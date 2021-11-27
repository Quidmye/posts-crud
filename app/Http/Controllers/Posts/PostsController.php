<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PostRequest;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class PostsController extends Controller
{

    public function index(Request $request)
    {
        $sortable = ['id', 'views', 'trending', 'controversial'];
        $directable = ['asc', 'desc'];

        $orderBy = Post::SORT_COLUMN;
        $direction = 'desc';
        $perPage = 15;
        if (!is_null($request->get('sort')) && in_array($request->get('sort'), $sortable)) {
            $orderBy = $request->get('sort');
        }
        if (!is_null($request->get('direction')) && in_array($request->get('direction'), $directable)) {
            $direction = $request->get('directions');
        }
        if (is_numeric($request->get('per_page')) && intval($request->get('per_page')) > 1 && intval($request->get('per_page')) < 50) {
            $perPage = intval($request->get('per_page'));
        }
        return response()->json(Post::with([
            'comments' => function (HasMany $builder) {
                $builder->whereNull('comment_id');
            }
        ])
            ->withCount([
                'comments as trending' => function (Builder $query) {
                    $query->where('created_at', '>', Carbon::now()->subHour());
                }
            ])
            ->selectRaw('((views/comments_count)+(SELECT MAX(level) from `comments` WHERE `comments`.`post_id` = `posts`.`id`)) as controversial')
            ->orderBy($orderBy, $direction)
            ->paginate($perPage)
        );
    }

    public function view($id)
    {
        return response()->json(Post::with([
            'comments' => function (HasMany $builder) {
                $builder->whereNull('comment_id');
            }
        ])
            ->findOrFail($id)
        );
    }

    public function create(PostRequest $request)
    {
        return response()->json(
            Post::create(
                $request->validated()
            )
        );
    }

    public function update(PostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->validated());
        return response()->json($post);
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json('ok', 204);
    }
}
