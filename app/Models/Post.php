<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = ['text', 'image', 'views'];

    public const SORT_COLUMN = 'id';

    public function comments(){
        return $this->hasMany(Comment::class, 'post_id')->with('replies');
    }

    protected static function newFactory()
    {
        return PostFactory::new();
    }
}
