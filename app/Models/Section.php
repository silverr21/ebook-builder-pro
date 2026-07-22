<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Section extends Model
{
    protected $fillable = ['chapter_id', 'title', 'content', 'order', 'metadata', 'word_count'];

    protected $casts = [
        'metadata' => 'json'
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function updateWordCount()
    {
        $this->word_count = str_word_count(strip_tags($this->content));
        $this->save();
    }
}

