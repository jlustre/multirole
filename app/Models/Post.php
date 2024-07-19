<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'image',
        'title',
        'slug',
        'body',
        'published_at',
        // 'tags',
        // 'category_id',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    public function scopePublished($query) {
        $query->where('published_at', '<=', Carbon::now());
    }

    public function scopeFeatured($query) {
        $query->where('featured', true);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function getReadingTime () {
        $numwords = ceil(str_word_count($this->body)/250) ;
        $newtext = strval($numwords) . ($numwords > 1 ? ' mins read' : ' min read');
        return $newtext;
    }
    public function getExcerpt ($words) {
        return Str::limit(strip_tags($this->body), $words);
    }
}
