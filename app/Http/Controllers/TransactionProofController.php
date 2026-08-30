<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TransactionProofController extends Controller
{
    public function show(
        Transaction $transaction
    ): BinaryFileResponse {
        abort_unless(
            auth()->check()
                && in_array(
                    auth()->user()->role,
                    [
                        'administrator',
                        'admin',
                    ],
                    true
                ),
            403
        );

        abort_if(
            ! $transaction->proof_path,
            404,
            'Bukti pembayaran tidak ditemukan.'
        );

        $disk = Storage::disk('local');

        abort_unless(
            $disk->exists(
                $transaction->proof_path
            ),
            404,
            'File bukti pembayaran tidak ditemukan.'
        );

        $absolutePath = $disk->path(
            $transaction->proof_path
        );

        $fileName = basename(
            $transaction->proof_original_name
                ?: $transaction->proof_path
        );

        $fileName = preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            $fileName
        ) ?: 'payment-proof';

        $mimeType = $transaction->proof_mime_type
            ?: $disk->mimeType(
                $transaction->proof_path
            )
            ?: 'application/octet-stream';

        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'application/pdf',
        ];

        if (
            ! in_array(
                $mimeType,
                $allowedMimeTypes,
                true
            )
        ) {
            $mimeType =
                'application/octet-stream';
        }

        return response()->file(
            $absolutePath,
            [
                'Content-Type' =>
                $mimeType,

                'Content-Disposition' =>
                'inline; filename="'
                    . $fileName
                    . '"',

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
