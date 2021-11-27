<?php

namespace App\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $fillable = ['text', 'user_name', 'post_id', 'comment_id'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function referencedComment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function replies(){
        return $this->hasMany(Comment::class, 'comment_id')->with('replies');
    }

    protected static function newFactory()
    {
        return CommentFactory::new();
    }
}
