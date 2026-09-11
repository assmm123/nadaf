<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slide extends Model
{
    protected $fillable = [
        'title_ar',
        'title_en',
        'image_path',
        'video_path',
        'link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getTitleAttribute(): ?string
    {
        return app()->getLocale() === 'en'
            ? ($this->title_en ?: $this->title_ar)
            : $this->title_ar;
    }

    public function imageUrl(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return str_starts_with($this->image_path, 'images/')
            ? asset($this->image_path)
            : Storage::url($this->image_path);
    }

    public function videoUrl(): ?string
    {
        if (! $this->video_path) {
            return null;
        }

        return str_starts_with($this->video_path, 'images/')
            ? asset($this->video_path)
            : Storage::url($this->video_path);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
