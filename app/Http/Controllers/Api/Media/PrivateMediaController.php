<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\MateriPdf;
use App\Models\Video;
use App\Support\StudentAccess;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PrivateMediaController extends Controller
{
    public function pdf(
        MateriPdf $pdf
    ): BinaryFileResponse {
        $user = auth()->user();

        StudentAccess::ensureStudent(
            $user
        );

        $authorizedPdf =
            StudentAccess::pdf(
                $user,
                (int) $pdf->id
            );

        abort_unless(
            $authorizedPdf->isPrivateFile(),
            404,
            'Materi PDF ini bukan file private.'
        );

        $disk = Storage::disk('local');

        abort_unless(
            $disk->exists(
                $authorizedPdf->pdf_url
            ),
            404,
            'File PDF tidak ditemukan.'
        );

        $path = $disk->path(
            $authorizedPdf->pdf_url
        );

        return response()->file(
            $path,
            [
                'Content-Type' =>
                $authorizedPdf
                    ->file_mime_type
                    ?: 'application/pdf',

                'Content-Disposition' =>
                'inline; filename="materi-'
                    . $authorizedPdf->id
                    . '.pdf"',

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

    public function video(
        Video $video
    ): BinaryFileResponse {
        $user = auth()->user();

        StudentAccess::ensureStudent(
            $user
        );

        $authorizedVideo =
            StudentAccess::video(
                $user,
                (int) $video->id
            );

        abort_unless(
            $authorizedVideo->isPrivateFile(),
            404,
            'Video ini bukan file private.'
        );

        abort_if(
            ! $authorizedVideo->video_path,
            404,
            'Path video tidak tersedia.'
        );

        $disk = Storage::disk('local');

        abort_unless(
            $disk->exists(
                $authorizedVideo->video_path
            ),
            404,
            'File video tidak ditemukan.'
        );

        $path = $disk->path(
            $authorizedVideo->video_path
        );

        /*
         * BinaryFileResponse milik Symfony mendukung
         * HTTP Range ketika response dipersiapkan,
         * sehingga video player dapat melakukan seek.
         */
        return response()->file(
            $path,
            [
                'Content-Type' =>
                $authorizedVideo
                    ->video_mime_type
                    ?: 'video/mp4',

                'Content-Disposition' =>
                'inline; filename="video-'
                    . $authorizedVideo->id
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
