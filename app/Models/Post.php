<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'thumbnail',
        'title',
        'slug',
        'color',
        'content',
        'tags',
        'user_id',
        'category_id',
        'published',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function authors() {
        return $this->belongsToMany(User::class, 'post_user')->withPivot(['order'])->withTimestamps();
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
