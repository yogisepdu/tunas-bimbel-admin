<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MateriPdf extends Model
{
    protected $fillable = [
        'chapter_id',
        'title',
        'pdf_url',
        'storage_type',
        'file_mime_type',
        'file_size',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function chapter()
    {
        return $this->belongsTo(
            Chapter::class
        );
    }

    public function isPrivateFile(): bool
    {
        return $this->storage_type
            === 'private_file';
    }

    public function isExternalUrl(): bool
    {
        return $this->storage_type
            === 'external_url';
    }

    protected static function booted()
    {
        static::deleting(
            function (
                MateriPdf $pdf
            ) {
                if (
                    $pdf->isPrivateFile()
                    && $pdf->pdf_url
                    && Storage::disk('local')
                    ->exists(
                        $pdf->pdf_url
                    )
                ) {
                    Storage::disk('local')
                        ->delete(
                            $pdf->pdf_url
                        );
                }
            }
        );
    }
}
