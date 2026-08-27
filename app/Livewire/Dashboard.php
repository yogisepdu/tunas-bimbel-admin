<?php

namespace App\Livewire;

use App\Models\ClassRoom;
use App\Models\User;
use App\Support\ClassAccess;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * Mencari nama kolom pertama yang tersedia.
     */
    private function findColumn(
        string $table,
        array $columns
    ): ?string {
        if (!Schema::hasTable($table)) {
            return null;
        }

        foreach ($columns as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }

        return null;
    }

    /**
     * Menghitung seluruh data pada tabel.
     */
    private function countTable(string $table): int
    {
        if (!Schema::hasTable($table)) {
            return 0;
        }

        return DB::table($table)->count();
    }

    /**
     * Mengambil ID data yang berhubungan langsung dengan kelas.
     */
    private function scopedIdsByClass(
        string $table,
        array $classIds,
        bool $restricted
    ): array {
        if (!Schema::hasTable($table)) {
            return [];
        }

        $query = DB::table($table);

        if ($restricted) {
            $classColumn = $this->findColumn(
                $table,
                [
                    'class_id',
                    'class_room_id',
                ]
            );

            if (!$classColumn) {
                return [];
            }

            $query->whereIn(
                $classColumn,
                $classIds
            );
        }

        return $query
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }

    /**
     * Mengambil ID berdasarkan relasi induk.
     */
    private function scopedIdsByParent(
        string $table,
        string $parentColumn,
        array $parentIds,
        bool $restricted
    ): array {
        if (!Schema::hasTable($table)) {
            return [];
        }

        $query = DB::table($table);

        if ($restricted) {
            if (!Schema::hasColumn(
                $table,
                $parentColumn
            )) {
                return [];
            }

            $query->whereIn(
                $parentColumn,
                $parentIds
            );
        }

        return $query
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }

    /**
     * Menghitung konten pembelajaran.
     *
     * Sistem mencoba mencari hubungan melalui:
     * - class_id
     * - class_room_id
     * - sub_course_id
     * - soal_section_id
     * - soal_set_id
     */
    private function countScopedContent(
        string $table,
        array $classIds,
        array $subCourseIds,
        array $sectionIds,
        array $setIds,
        bool $restricted
    ): int {
        if (!Schema::hasTable($table)) {
            return 0;
        }

        $query = DB::table($table);

        if (!$restricted) {
            return $query->count();
        }

        if (Schema::hasColumn($table, 'class_id')) {
            return $query
                ->whereIn('class_id', $classIds)
                ->count();
        }

        if (Schema::hasColumn(
            $table,
            'class_room_id'
        )) {
            return $query
                ->whereIn(
                    'class_room_id',
                    $classIds
                )
                ->count();
        }

        if (Schema::hasColumn(
            $table,
            'sub_course_id'
        )) {
            return $query
                ->whereIn(
                    'sub_course_id',
                    $subCourseIds
                )
                ->count();
        }

        if (Schema::hasColumn(
            $table,
            'soal_section_id'
        )) {
            return $query
                ->whereIn(
                    'soal_section_id',
                    $sectionIds
                )
                ->count();
        }

        if (Schema::hasColumn(
            $table,
            'soal_set_id'
        )) {
            return $query
                ->whereIn(
                    'soal_set_id',
                    $setIds
                )
                ->count();
        }

        /*
         * Teacher tidak boleh menerima hitungan global
         * jika hubungan tabel dengan kelas tidak diketahui.
         */
        return 0;
    }

    /**
     * Menghitung jumlah peserta kelas jika tabel pivot tersedia.
     *
     * Jika tabel pivot tidak ditemukan, jumlah peserta
     * diambil dari akun dengan role student.
     */
    private function participantMetric(
        array $classIds,
        bool $restricted
    ): array {
        $candidateTables = [
            'class_student',
            'class_user',
            'class_enrollments',
            'enrollments',
            'course_user',
        ];

        foreach ($candidateTables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            $classColumn = $this->findColumn(
                $table,
                [
                    'class_id',
                    'class_room_id',
                ]
            );

            $userColumn = $this->findColumn(
                $table,
                [
                    'user_id',
                    'student_id',
                ]
            );

            if (!$classColumn || !$userColumn) {
                continue;
            }

            $query = DB::table($table);

            if ($restricted) {
                $query->whereIn(
                    $classColumn,
                    $classIds
                );
            }

            return [
                'label' => 'Peserta Kelas',
                'total' => $query
                    ->distinct()
                    ->count($userColumn),
            ];
        }

        return [
            'label' => 'Siswa Terdaftar',
            'total' => User::query()
                ->where('role', 'student')
                ->count(),
        ];
    }

    public function render()
    {
        $user = auth()->user();
        $role = $user->role;

        $isAdministrator =
            $role === 'administrator';

        $isAdmin =
            $role === 'admin';

        $isTeacher =
            $role === 'teacher';

        /*
        |--------------------------------------------------------------------------
        | SAPAAN
        |--------------------------------------------------------------------------
        */

        $now = now();
        $hour = (int) $now->format('H');

        $greeting = match (true) {
            $hour >= 5 && $hour < 11 =>
            'Selamat pagi',

            $hour >= 11 && $hour < 15 =>
            'Selamat siang',

            $hour >= 15 && $hour < 18 =>
            'Selamat sore',

            default =>
            'Selamat malam',
        };

        $displayName = Str::before(
            $user->name,
            ' '
        );

        $currentDate = $now
            ->copy()
            ->locale('id')
            ->translatedFormat('l, d F Y');

        /*
        |--------------------------------------------------------------------------
        | CAKUPAN KELAS
        |--------------------------------------------------------------------------
        |
        | Administrator dan admin:
        | - Seluruh kelas.
        |
        | Teacher:
        | - Hanya kelas yang ditugaskan.
        |
        */

        if ($isTeacher) {
            $classIds = collect(
                ClassAccess::classIds()
            )
                ->map(fn($id) => (int) $id)
                ->values()
                ->all();
        } else {
            $classIds = ClassRoom::query()
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->values()
                ->all();
        }

        $restricted = $isTeacher;

        $totalClasses = ClassRoom::query()
            ->whereIn('id', $classIds)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | ID RELASI KONTEN
        |--------------------------------------------------------------------------
        */

        $subCourseIds = $this->scopedIdsByClass(
            'sub_courses',
            $classIds,
            $restricted
        );

        $sectionIds = $this->scopedIdsByClass(
            'soal_sections',
            $classIds,
            $restricted
        );

        $setIds = $this->scopedIdsByParent(
            'soal_sets',
            'soal_section_id',
            $sectionIds,
            $restricted
        );

        /*
        |--------------------------------------------------------------------------
        | JUMLAH KONTEN
        |--------------------------------------------------------------------------
        */

        $totalSubCourses = count($subCourseIds);

        $totalVideos = $this->countScopedContent(
            'videos',
            $classIds,
            $subCourseIds,
            $sectionIds,
            $setIds,
            $restricted
        );

        $totalPdfs = $this->countScopedContent(
            'pdfs',
            $classIds,
            $subCourseIds,
            $sectionIds,
            $setIds,
            $restricted
        );

        $totalQuizzes = $this->countScopedContent(
            'quizzes',
            $classIds,
            $subCourseIds,
            $sectionIds,
            $setIds,
            $restricted
        );

        $totalTryouts = count($setIds);

        $totalTryoutQuestions =
            $this->countScopedContent(
                'soal_questions',
                $classIds,
                $subCourseIds,
                $sectionIds,
                $setIds,
                $restricted
            );

        $totalPackages = $this->countTable(
            'packages'
        );

        $participantMetric =
            $this->participantMetric(
                $classIds,
                $restricted
            );

        /*
        |--------------------------------------------------------------------------
        | STATISTIK PEMBELAJARAN
        |--------------------------------------------------------------------------
        */

        $learningStats = collect([
            [
                'label' => $isTeacher
                    ? 'Kelas Ditugaskan'
                    : 'Total Kelas',
                'value' => $totalClasses,
                'description' => $isTeacher
                    ? 'Kelas yang dapat Anda kelola'
                    : 'Kelas pembelajaran yang tersedia',
                'color' => '#4f46e5',
                'background' => '#eef2ff',
            ],
            [
                'label' => 'Sub Materi',
                'value' => $totalSubCourses,
                'description' =>
                'Kelompok materi pembelajaran',
                'color' => '#7c3aed',
                'background' => '#f5f3ff',
            ],
            [
                'label' => 'Video',
                'value' => $totalVideos,
                'description' =>
                'Konten video pembelajaran',
                'color' => '#e11d48',
                'background' => '#fff1f2',
            ],
            [
                'label' => 'Dokumen PDF',
                'value' => $totalPdfs,
                'description' =>
                'Dokumen pendukung pembelajaran',
                'color' => '#ea580c',
                'background' => '#fff7ed',
            ],
            [
                'label' => 'Quiz',
                'value' => $totalQuizzes,
                'description' =>
                'Quiz evaluasi pembelajaran',
                'color' => '#0891b2',
                'background' => '#ecfeff',
            ],
            [
                'label' => 'Set TryOut',
                'value' => $totalTryouts,
                'description' =>
                'Set latihan TryOut tersedia',
                'color' => '#059669',
                'background' => '#ecfdf5',
            ],
            [
                'label' => 'Soal TryOut',
                'value' => $totalTryoutQuestions,
                'description' =>
                'Jumlah pertanyaan TryOut',
                'color' => '#ca8a04',
                'background' => '#fefce8',
            ],
            [
                'label' => $participantMetric['label'],
                'value' => $participantMetric['total'],
                'description' =>
                'Peserta yang terdaftar',
                'color' => '#2563eb',
                'background' => '#eff6ff',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | GRAFIK PERBANDINGAN KONTEN
        |--------------------------------------------------------------------------
        */

        $contentChartData = $learningStats
            ->take(7)
            ->map(function ($item) {
                return [
                    'label' => $item['label'],
                    'total' => $item['value'],
                    'color' => $item['color'],
                ];
            });

        $maximumContent = max(
            1,
            (int) $contentChartData->max('total')
        );

        $contentChartData = $contentChartData
            ->map(function ($item) use (
                $maximumContent
            ) {
                $item['percentage'] =
                    $item['total'] > 0
                    ? max(
                        4,
                        round(
                            (
                                $item['total']
                                / $maximumContent
                            ) * 100,
                            1
                        )
                    )
                    : 0;

                return $item;
            });

        /*
        |--------------------------------------------------------------------------
        | KELAS TERBARU
        |--------------------------------------------------------------------------
        */

        $latestClasses = ClassRoom::query()
            ->whereIn('id', $classIds)
            ->latest()
            ->limit(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DATA KHUSUS ADMINISTRATOR
        |--------------------------------------------------------------------------
        */

        $accountStats = collect();
        $monthlyUsers = collect();
        $roleDistribution = collect();
        $roleChartGradient = '#e4e4e7';
        $recentUsers = collect();
        $newUsersThisMonth = 0;
        $userGrowth = 0;

        if ($isAdministrator) {
            $userCountByRole = User::query()
                ->select(
                    'role',
                    DB::raw('COUNT(*) as total')
                )
                ->groupBy('role')
                ->pluck('total', 'role');

            $totalStudents = (int) (
                $userCountByRole['student'] ?? 0
            );

            $totalTeachers = (int) (
                $userCountByRole['teacher'] ?? 0
            );

            $totalAdmins = (int) (
                $userCountByRole['admin'] ?? 0
            );

            $totalAdministrators = (int) (
                $userCountByRole['administrator']
                ?? 0
            );

            $totalUsers = User::query()->count();

            $accountStats = collect([
                [
                    'label' => 'Total Akun',
                    'value' => $totalUsers,
                    'description' =>
                    'Seluruh akun dalam sistem',
                    'color' => '#4f46e5',
                    'background' => '#eef2ff',
                ],
                [
                    'label' => 'Akun Siswa',
                    'value' => $totalStudents,
                    'description' =>
                    'Siswa yang telah mendaftar',
                    'color' => '#7c3aed',
                    'background' => '#f5f3ff',
                ],
                [
                    'label' => 'Akun Teacher',
                    'value' => $totalTeachers,
                    'description' =>
                    'Pengajar yang terdaftar',
                    'color' => '#059669',
                    'background' => '#ecfdf5',
                ],
                [
                    'label' => 'Akun Admin',
                    'value' => $totalAdmins,
                    'description' =>
                    'Admin pengelola operasional',
                    'color' => '#ea580c',
                    'background' => '#fff7ed',
                ],
            ]);

            /*
             * Pengguna baru bulan ini.
             */
            $currentMonthStart = $now
                ->copy()
                ->startOfMonth();

            $currentMonthEnd = $now
                ->copy()
                ->endOfMonth();

            $previousMonthStart = $now
                ->copy()
                ->subMonthNoOverflow()
                ->startOfMonth();

            $previousMonthEnd = $now
                ->copy()
                ->subMonthNoOverflow()
                ->endOfMonth();

            $newUsersThisMonth = User::query()
                ->whereBetween('created_at', [
                    $currentMonthStart,
                    $currentMonthEnd,
                ])
                ->count();

            $newUsersPreviousMonth = User::query()
                ->whereBetween('created_at', [
                    $previousMonthStart,
                    $previousMonthEnd,
                ])
                ->count();

            if ($newUsersPreviousMonth > 0) {
                $userGrowth = round(
                    (
                        (
                            $newUsersThisMonth
                            - $newUsersPreviousMonth
                        )
                        / $newUsersPreviousMonth
                    ) * 100,
                    1
                );
            } else {
                $userGrowth =
                    $newUsersThisMonth > 0
                    ? 100
                    : 0;
            }

            /*
             * Grafik pertumbuhan akun enam bulan.
             */
            $chartStart = $now
                ->copy()
                ->subMonthsNoOverflow(5)
                ->startOfMonth();

            $registrations = User::query()
                ->where(
                    'created_at',
                    '>=',
                    $chartStart
                )
                ->get([
                    'id',
                    'created_at',
                ])
                ->groupBy(function ($account) {
                    return $account
                        ->created_at
                        ->format('Y-m');
                });

            $monthlyUsers = collect(
                range(5, 0)
            )->map(function ($monthsAgo) use (
                $now,
                $registrations
            ) {
                $month = $now
                    ->copy()
                    ->subMonthsNoOverflow(
                        $monthsAgo
                    );

                $key = $month->format('Y-m');

                return [
                    'key' => $key,
                    'label' => $month
                        ->copy()
                        ->locale('id')
                        ->translatedFormat('M'),
                    'full_label' => $month
                        ->copy()
                        ->locale('id')
                        ->translatedFormat('F Y'),
                    'total' => isset(
                        $registrations[$key]
                    )
                        ? $registrations[$key]
                        ->count()
                        : 0,
                ];
            })->values();

            $maximumMonthly = max(
                1,
                (int) $monthlyUsers->max('total')
            );

            $monthlyUsers = $monthlyUsers
                ->map(function (
                    $month,
                    $index
                ) use (
                    $maximumMonthly
                ) {
                    $pointCount = 6;

                    $month['x'] = $index
                        * (
                            100
                            / max(
                                1,
                                $pointCount - 1
                            )
                        );

                    $month['y'] = 90 - (
                        (
                            $month['total']
                            / $maximumMonthly
                        ) * 70
                    );

                    return $month;
                });

            /*
             * Distribusi role.
             */
            $roleDistribution = collect([
                [
                    'label' => 'Siswa',
                    'total' => $totalStudents,
                    'color' => '#6366f1',
                ],
                [
                    'label' => 'Teacher',
                    'total' => $totalTeachers,
                    'color' => '#10b981',
                ],
                [
                    'label' => 'Admin',
                    'total' => $totalAdmins,
                    'color' => '#f59e0b',
                ],
                [
                    'label' => 'Administrator',
                    'total' =>
                    $totalAdministrators,
                    'color' => '#f43f5e',
                ],
            ]);

            $roleTotal = max(
                1,
                (int) $roleDistribution
                    ->sum('total')
            );

            $currentAngle = 0;
            $gradientSegments = [];

            $roleDistribution =
                $roleDistribution->map(
                    function ($item) use (
                        $roleTotal,
                        &$currentAngle,
                        &$gradientSegments
                    ) {
                        $item['percentage'] =
                            round(
                                (
                                    $item['total']
                                    / $roleTotal
                                ) * 100,
                                1
                            );

                        if ($item['total'] > 0) {
                            $endAngle =
                                $currentAngle
                                + (
                                    (
                                        $item['total']
                                        / $roleTotal
                                    ) * 360
                                );

                            $gradientSegments[] =
                                sprintf(
                                    '%s %.2fdeg %.2fdeg',
                                    $item['color'],
                                    $currentAngle,
                                    $endAngle
                                );

                            $currentAngle =
                                $endAngle;
                        }

                        return $item;
                    }
                );

            if (count($gradientSegments) > 0) {
                $roleChartGradient =
                    'conic-gradient('
                    . implode(
                        ', ',
                        $gradientSegments
                    )
                    . ')';
            }

            $recentUsers = User::query()
                ->latest()
                ->limit(6)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | DATA OPERASIONAL ADMINISTRATOR
        |--------------------------------------------------------------------------
        */

        $administratorSystemStats = collect([
            [
                'label' => 'Kelas',
                'value' => $totalClasses,
                'color' => '#4f46e5',
            ],
            [
                'label' => 'Sub Materi',
                'value' => $totalSubCourses,
                'color' => '#7c3aed',
            ],
            [
                'label' => 'Video',
                'value' => $totalVideos,
                'color' => '#e11d48',
            ],
            [
                'label' => 'PDF',
                'value' => $totalPdfs,
                'color' => '#ea580c',
            ],
            [
                'label' => 'Quiz',
                'value' => $totalQuizzes,
                'color' => '#0891b2',
            ],
            [
                'label' => 'TryOut',
                'value' => $totalTryouts,
                'color' => '#059669',
            ],
            [
                'label' => 'Paket',
                'value' => $totalPackages,
                'color' => '#ca8a04',
            ],
            [
                'label' =>
                $participantMetric['label'],
                'value' =>
                $participantMetric['total'],
                'color' => '#2563eb',
            ],
        ]);

        return view('livewire.dashboard', [
            'isAdministrator' =>
            $isAdministrator,

            'isAdmin' => $isAdmin,
            'isTeacher' => $isTeacher,

            'greeting' => $greeting,
            'displayName' => $displayName,
            'currentDate' => $currentDate,

            'accountStats' => $accountStats,
            'learningStats' => $learningStats,

            'administratorSystemStats' =>
            $administratorSystemStats,

            'contentChartData' =>
            $contentChartData,

            'latestClasses' =>
            $latestClasses,

            'monthlyUsers' =>
            $monthlyUsers,

            'roleDistribution' =>
            $roleDistribution,

            'roleChartGradient' =>
            $roleChartGradient,

            'recentUsers' =>
            $recentUsers,

            'newUsersThisMonth' =>
            $newUsersThisMonth,

            'userGrowth' =>
            $userGrowth,
        ])->layout('layouts.admin', [
            'title' => 'Dashboard',
        ]);
    }
}
