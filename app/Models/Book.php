<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'slug', 'status',
        'cover_image', 'author', 'tags', 'is_featured', 'published_at'
    ];

    protected $casts = [
        'tags' => 'json',
        'published_at' => 'datetime',
        'is_featured' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    public function design()
    {
        return $this->hasOne(Design::class);
    }

    public function sections()
    {
        return $this->hasManyThrough(Section::class, Chapter::class);
    }

    public function wordCount()
    {
        return $this->sections()->sum('word_count');
    }
}

