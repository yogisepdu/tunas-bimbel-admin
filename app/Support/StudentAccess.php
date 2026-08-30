<?php

namespace App\Support;

use App\Models\Chapter;
use App\Models\MateriPdf;
use App\Models\Quiz;
use App\Models\SoalSection;
use App\Models\SoalSet;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StudentAccess
{
    /**
     * Ambil seluruh package_id yang masih aktif untuk student.
     *
     * expires_at NULL tetap dianggap aktif untuk data legacy.
     */
    public static function activePackageIds(
        User $user
    ): Collection {
        self::ensureStudent($user);

        return DB::table('user_packages')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where(function ($query) {
                $query
                    ->whereNull('activated_at')
                    ->orWhere(
                        'activated_at',
                        '<=',
                        now()
                    );
            })
            ->where(function ($query) {
                $query
                    ->whereNull('expires_at')
                    ->orWhere(
                        'expires_at',
                        '>',
                        now()
                    );
            })
            ->pluck('package_id');
    }

    /**
     * Ambil seluruh class_id yang dapat diakses student.
     */
    public static function accessibleClassIds(
        User $user
    ): Collection {
        $packageIds = self::activePackageIds(
            $user
        );

        if ($packageIds->isEmpty()) {
            return collect();
        }

        return DB::table('package_classes')
            ->whereIn(
                'package_id',
                $packageIds
            )
            ->distinct()
            ->pluck('class_id');
    }

    /**
     * Apakah student mempunyai package aktif tertentu.
     */
    public static function hasPackage(
        User $user,
        int $packageId
    ): bool {
        self::ensureStudent($user);

        return DB::table('user_packages')
            ->where('user_id', $user->id)
            ->where('package_id', $packageId)
            ->where('status', 'active')
            ->where(function ($query) {
                $query
                    ->whereNull('activated_at')
                    ->orWhere(
                        'activated_at',
                        '<=',
                        now()
                    );
            })
            ->where(function ($query) {
                $query
                    ->whereNull('expires_at')
                    ->orWhere(
                        'expires_at',
                        '>',
                        now()
                    );
            })
            ->exists();
    }

    /**
     * Apakah student mempunyai akses ke sebuah kelas.
     */
    public static function hasClass(
        User $user,
        int $classId
    ): bool {
        self::ensureStudent($user);

        return DB::table('user_packages')
            ->join(
                'package_classes',
                'user_packages.package_id',
                '=',
                'package_classes.package_id'
            )
            ->where(
                'user_packages.user_id',
                $user->id
            )
            ->where(
                'user_packages.status',
                'active'
            )
            ->where(
                'package_classes.class_id',
                $classId
            )
            ->where(function ($query) {
                $query
                    ->whereNull(
                        'user_packages.activated_at'
                    )
                    ->orWhere(
                        'user_packages.activated_at',
                        '<=',
                        now()
                    );
            })
            ->where(function ($query) {
                $query
                    ->whereNull(
                        'user_packages.expires_at'
                    )
                    ->orWhere(
                        'user_packages.expires_at',
                        '>',
                        now()
                    );
            })
            ->exists();
    }

    /**
     * Pastikan class dapat diakses.
     */
    public static function authorizeClass(
        User $user,
        int $classId
    ): void {
        abort_unless(
            self::hasClass(
                $user,
                $classId
            ),
            403,
            'Paket kamu tidak aktif atau tidak memiliki akses ke kelas ini.'
        );
    }

    /**
     * Chapter yang sudah terotorisasi.
     */
    public static function chapter(
        User $user,
        int $chapterId
    ): Chapter {
        $chapter = Chapter::findOrFail(
            $chapterId
        );

        self::authorizeClass(
            $user,
            (int) $chapter->class_id
        );

        return $chapter;
    }

    /**
     * Video yang sudah terotorisasi.
     */
    public static function video(
        User $user,
        int $videoId
    ): Video {
        $video = Video::with('chapter')
            ->findOrFail(
                $videoId
            );

        abort_unless(
            $video->chapter,
            404,
            'Chapter video tidak ditemukan.'
        );

        self::authorizeClass(
            $user,
            (int) $video->chapter->class_id
        );

        return $video;
    }

    /**
     * PDF yang sudah terotorisasi.
     */
    public static function pdf(
        User $user,
        int $pdfId
    ): MateriPdf {
        $pdf = MateriPdf::with('chapter')
            ->findOrFail(
                $pdfId
            );

        abort_unless(
            $pdf->chapter,
            404,
            'Chapter PDF tidak ditemukan.'
        );

        self::authorizeClass(
            $user,
            (int) $pdf->chapter->class_id
        );

        return $pdf;
    }

    /**
     * Quiz yang sudah terotorisasi.
     */
    public static function quiz(
        User $user,
        int $quizId
    ): Quiz {
        $quiz = Quiz::findOrFail(
            $quizId
        );

        self::authorizeClass(
            $user,
            (int) $quiz->class_id
        );

        return $quiz;
    }

    /**
     * Section tryout yang sudah terotorisasi.
     */
    public static function soalSection(
        User $user,
        int $sectionId
    ): SoalSection {
        $section = SoalSection::findOrFail(
            $sectionId
        );

        self::authorizeClass(
            $user,
            (int) $section->class_id
        );

        return $section;
    }

    /**
     * Set tryout yang sudah terotorisasi.
     */
    public static function soalSet(
        User $user,
        int $setId
    ): SoalSet {
        $set = SoalSet::with('section')
            ->findOrFail(
                $setId
            );

        abort_unless(
            $set->section,
            404,
            'Section tryout tidak ditemukan.'
        );

        self::authorizeClass(
            $user,
            (int) $set->section->class_id
        );

        return $set;
    }

    /**
     * Pastikan token memang milik student.
     */
    public static function ensureStudent(
        User $user
    ): void {
        abort_unless(
            $user->role === 'student',
            403,
            'Endpoint ini hanya dapat diakses oleh akun student.'
        );
    }
}
