<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teaser extends Model
{
    /** @use HasFactory<\Database\Factories\TeaserFactory> */
    use HasFactory, SoftDeletes ;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'image_path',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
