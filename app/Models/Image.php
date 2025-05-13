<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory , softDeletes;

    protected $fillable = [
        'path',
        'size',
        'mime_type',
        'alt_text',
        'original_name',
        'teaser_id',
    ];

    protected $casts = [
        'size' => 'integer',
    ];


    public function teaser()
    {
        return $this->belongsTo(Teaser::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }



}
