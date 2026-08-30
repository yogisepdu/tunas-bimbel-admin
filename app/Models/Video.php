<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    protected $fillable = [
        'chapter_id',
        'title',
        'subtitle',
        'youtube_id',
        'source_type',
        'video_path',
        'video_mime_type',
        'video_size',
    ];

    protected function casts(): array
    {
        return [
            'video_size' => 'integer',
        ];
    }

    public function chapter()
    {
        return $this->belongsTo(
            Chapter::class
        );
    }

    public function isYoutube(): bool
    {
        return $this->source_type === 'youtube';
    }

    public function isPrivateFile(): bool
    {
        return $this->source_type === 'private_file';
    }

    protected static function booted()
    {
        static::deleting(function (Video $video) {
            if (
                $video->video_path
                && Storage::disk('local')
                ->exists($video->video_path)
            ) {
                Storage::disk('local')
                    ->delete($video->video_path);
            }
        });
    }
}
