<?php

namespace App\Support;

use App\Models\Chapter;
use App\Models\ClassRoom;
use App\Models\MateriPdf;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\SoalQuestion;
use App\Models\SoalSection;
use App\Models\SoalSet;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final class ClassAccess
{
    private static function user(): User
    {
        $user = Auth::user();

        abort_unless($user, 401);

        return $user;
    }

    public static function isManager(): bool
    {
        return in_array(
            self::user()->role,
            [
                'administrator',
                'admin',
            ],
            true
        );
    }

    public static function ensureManager(): void
    {
        abort_unless(
            self::isManager(),
            403,
            'Hanya administrator atau admin yang dapat mengelola master kelas.'
        );
    }

    public static function classes(): Builder
    {
        $user = self::user();

        if (
            in_array(
                $user->role,
                ['administrator', 'admin'],
                true
            )
        ) {
            return ClassRoom::query();
        }

        if ($user->role === 'teacher') {
            $teacherId = $user->teacher()
                ->value('id');

            if (!$teacherId) {
                return ClassRoom::query()
                    ->whereRaw('1 = 0');
            }

            return ClassRoom::query()
                ->whereHas(
                    'teachers',
                    function ($query) use ($teacherId) {
                        $query->where(
                            'teachers.id',
                            $teacherId
                        );
                    }
                );
        }

        return ClassRoom::query()
            ->whereRaw('1 = 0');
    }

    public static function classIds(): array
    {
        return self::classes()
            ->pluck('classes.id')
            ->map(fn($id) => (int) $id)
            ->all();
    }

    public static function classOrFail(
        int $id
    ): ClassRoom {
        return self::classes()
            ->findOrFail($id);
    }

    public static function chapterOrFail(
        int $id
    ): Chapter {
        $chapter = Chapter::findOrFail($id);

        self::classOrFail(
            (int) $chapter->class_id
        );

        return $chapter;
    }

    public static function videoOrFail(
        int $id
    ): Video {
        $video = Video::with('chapter')
            ->findOrFail($id);

        self::classOrFail(
            (int) $video->chapter->class_id
        );

        return $video;
    }

    public static function pdfOrFail(
        int $id
    ): MateriPdf {
        $pdf = MateriPdf::with('chapter')
            ->findOrFail($id);

        self::classOrFail(
            (int) $pdf->chapter->class_id
        );

        return $pdf;
    }

    public static function quizOrFail(
        int $id
    ): Quiz {
        $quiz = Quiz::findOrFail($id);

        self::classOrFail(
            (int) $quiz->class_id
        );

        return $quiz;
    }

    public static function questionOrFail(
        int $id
    ): Question {
        $question = Question::with('quiz')
            ->findOrFail($id);

        self::classOrFail(
            (int) $question->quiz->class_id
        );

        return $question;
    }

    public static function sectionOrFail(
        int $id
    ): SoalSection {
        $section = SoalSection::findOrFail($id);

        self::classOrFail(
            (int) $section->class_id
        );

        return $section;
    }

    public static function setOrFail(
        int $id
    ): SoalSet {
        $set = SoalSet::with('section')
            ->findOrFail($id);

        self::classOrFail(
            (int) $set->section->class_id
        );

        return $set;
    }

    public static function soalQuestionOrFail(
        int $id
    ): SoalQuestion {
        $question = SoalQuestion::with(
            'set.section'
        )->findOrFail($id);

        self::classOrFail(
            (int) $question->set->section->class_id
        );

        return $question;
    }
}
