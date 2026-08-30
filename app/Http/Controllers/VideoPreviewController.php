<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Support\ClassAccess;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VideoPreviewController extends Controller
{
    public function show(
        Video $video
    ): BinaryFileResponse {
        /*
        |--------------------------------------------------------------------------
        | CLASS ACCESS
        |--------------------------------------------------------------------------
        |
        | administrator/admin:
        | dapat melihat semua.
        |
        | teacher:
        | hanya video dari kelas yang ditugaskan.
        |
        */

        $video = ClassAccess::videoOrFail(
            (int) $video->id
        );

        /*
        |--------------------------------------------------------------------------
        | Harus Private Video
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $video->source_type
                === 'private_file',
            404,
            'Video ini bukan private file.'
        );

        abort_if(
            empty($video->video_path),
            404,
            'Path video tidak tersedia.'
        );

        /*
        |--------------------------------------------------------------------------
        | STORAGE
        |--------------------------------------------------------------------------
        */

        $disk =
            Storage::disk('local');

        abort_unless(
            $disk->exists(
                $video->video_path
            ),
            404,
            'File video tidak ditemukan.'
        );

        $absolutePath =
            $disk->path(
                $video->video_path
            );

        /*
        |--------------------------------------------------------------------------
        | MIME TYPE
        |--------------------------------------------------------------------------
        */

        $mimeType =
            $video->video_mime_type;

        if (! $mimeType) {
            try {
                $mimeType =
                    $disk->mimeType(
                        $video->video_path
                    );
            } catch (\Throwable $e) {
                $mimeType = null;
            }
        }

        $mimeType =
            $mimeType
            ?: 'video/mp4';

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->file(
            $absolutePath,
            [
                'Content-Type' =>
                $mimeType,

                'Content-Disposition' =>
                'inline; filename="video-'
                    . $video->id
                    . '.mp4"',

                'Cache-Control' =>
                'private, no-store, no-cache, must-revalidate, max-age=0',

                'Pragma' =>
                'no-cache',

                'Expires' =>
                '0',

                'X-Content-Type-Options' =>
                'nosniff',
            ]
        );
    }
}
