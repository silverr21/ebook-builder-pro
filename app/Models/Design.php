<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Design extends Model
{
    protected $fillable = ['book_id', 'name', 'typography', 'colors', 'spacing', 'layout', 'template', 'is_active'];

    protected $casts = [
        'typography' => 'json',
        'colors' => 'json',
        'spacing' => 'json',
        'layout' => 'json',
        'is_active' => 'boolean'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public static function getDefaultDesign()
    {
        return [
            'typography' => [
                'heading' => ['fontFamily' => 'Poppins', 'fontSize' => 32, 'fontWeight' => 700],
                'body' => ['fontFamily' => 'Inter', 'fontSize' => 16, 'lineHeight' => 1.6],
            ],
            'colors' => [
                'primary' => '#3B82F6',
                'secondary' => '#10B981',
                'text' => '#1F2937',
                'background' => '#FFFFFF',
            ],
            'spacing' => ['margin' => 24, 'padding' => 32],
            'layout' => ['columns' => 1, 'maxWidth' => 800]
        ];
    }
}

