<?php

namespace App\Models;

use Database\Factories\TeaserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Teaser extends Model
{
    /** @use HasFactory<TeaserFactory> */
    use HasFactory, SoftDeletes ;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'image_name',
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

    /**
     * Get the full path to the image.
     */
    public function getImageUrl(): string
    {
        if (!$this->image_name) {
            return  asset('images/default-teaser.png');
        }

        return asset('teasers/' . $this->id . '/' . $this->image_name);
    }


    public function getExcerptAttribute(): string
    {
        return \Str::limit($this->description, 150);
    }

}
