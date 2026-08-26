        <flux:sidebar.nav>
            @php
                $role = auth()->user()?->role;

                $isAdministrator = $role === 'administrator';

                $isAdmin = in_array($role, ['administrator', 'admin'], true);

                $canManageLearning = in_array($role, ['administrator', 'admin', 'teacher'], true);
            @endphp

            <!-- Dashboard -->
            <flux:sidebar.item :current="request()->routeIs('dashboard')"
                class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                href="{{ route('dashboard') }}" icon="home" wire:navigate>
                Dashboard
            </flux:sidebar.item>

            <!-- User Management -->
            @if ($isAdministrator)
                <flux:sidebar.group class="grid" expandable heading="User Management">

                    <flux:sidebar.item :current="request()->routeIs('student*')"
                        class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                        href="{{ route('student.index') }}" icon="users" wire:navigate>
                        Siswa
                    </flux:sidebar.item>

                    <flux:sidebar.item :current="request()->routeIs('teacher*')"
                        class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                        href="{{ route('teacher.index') }}" icon="user" wire:navigate>
                        Teacher
                    </flux:sidebar.item>
                </flux:sidebar.group>
            @endif

            <!-- Materi -->
            @if ($canManageLearning)
                @php
                    $isAdmin = in_array(auth()->user()->role, ['administrator', 'admin'], true);
                @endphp
                <flux:sidebar.group class="grid" expandable heading="Materi">
                    @if ($isAdmin)
                        <flux:sidebar.item :current="request()->routeIs('packages*')"
                            class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                            href="{{ route('packages.index') }}" icon="gift" wire:navigate>
                            Packages
                        </flux:sidebar.item>
                    @endif

                    <flux:sidebar.item :current="request()->routeIs('course*')"
                        class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                        href="{{ route('course.index') }}" icon="book-open" wire:navigate>
                        Kelas
                    </flux:sidebar.item>


                    <flux:sidebar.item :current="request()->routeIs('sub-course*')"
                        class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                        href="{{ route('sub-course.index') }}" icon="book-open" wire:navigate>
                        Sub Materi
                    </flux:sidebar.item>

                    <flux:sidebar.item :current="request()->routeIs('video*')"
                        class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                        href="{{ route('video.index') }}" icon="book-open" wire:navigate>
                        Video
                    </flux:sidebar.item>

                    <flux:sidebar.item :current="request()->routeIs('pdf*')"
                        class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                        href="{{ route('pdf.index') }}" icon="document-text" wire:navigate>
                        Materi PDF
                    </flux:sidebar.item>

                </flux:sidebar.group>
            @endif

            <!-- Quiz -->
            @if ($canManageLearning)
                <flux:sidebar.item :current="request()->routeIs('quiz*')"
                    class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                    href="{{ route('quiz.index') }}" icon="clipboard-document-list" wire:navigate>
                    Quiz
                </flux:sidebar.item>

                {{-- Quiz --}}
                <flux:sidebar.group class="grid" expandable heading="Soal TryOut">

                    <flux:sidebar.item :current="request()->routeIs('soal-section*')"
                        class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                        href="{{ route('soal-section.index') }}" icon="clipboard-document-list" wire:navigate>
                        Soal Section
                    </flux:sidebar.item>

                    {{-- Soal Set --}}
                    <flux:sidebar.item :current="request()->routeIs('soal-set*')"
                        class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                        href="{{ route('soal-set.index') }}" icon="clipboard-document-list" wire:navigate>
                        Soal Set
                    </flux:sidebar.item>

                    {{-- Soal Question --}}
                    <flux:sidebar.item :current="request()->routeIs('soal-question.index')"
                        class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                        href="{{ route('soal-question.index') }}" icon="clipboard-document-list" wire:navigate>
                        Soal Question
                    </flux:sidebar.item>
                </flux:sidebar.group>
            @endif

            <!-- Kalender -->
            @if ($isAdmin)
                <flux:sidebar.item :current="request()->routeIs('kalender*')"
                    class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                    href="{{ route('kalender.index') }}" icon="calendar" wire:navigate>
                    Kalender Akademik
                </flux:sidebar.item>

                <flux:sidebar.item :current="request()->routeIs('linked*')"
                    class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                    href="{{ route('linked.index') }}" icon="link" wire:navigate>
                    Linked
                </flux:sidebar.item>

                <flux:sidebar.item :current="request()->routeIs('announcement*')"
                    class="font-medium hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white"
                    href="{{ route('announcement.index') }}" icon="bell" wire:navigate>
                    Announcement
                </flux:sidebar.item>

                <!-- Laporan -->
                <flux:sidebar.group class="grid" expandable heading="Laporan">

                    <flux:sidebar.item icon="chart-pie">
                        Progress Belajar
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="document-chart-bar">
                        Nilai Quiz
                    </flux:sidebar.item>

                </flux:sidebar.group>
            @endif

            <flux:sidebar.item icon="cog-6-tooth">Settings</flux:sidebar.item>
            <flux:sidebar.item icon="information-circle">Help</flux:sidebar.item>
        </flux:sidebar.nav>

        <!-- PROFILE -->
        <flux:dropdown align="start" class="max-lg:hidden" position="top">

            <flux:sidebar.profile avatar="https://fluxui.dev/img/demo/user.png"
                name="{{ auth()->user()->name ?? 'User' }}" />

            <flux:menu>

                <flux:menu.radio.group>
                    <flux:menu.radio checked>
                        {{ auth()->user()->name ?? 'User' }}
                    </flux:menu.radio>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <flux:menu.item icon="arrow-right-start-on-rectangle"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Logout
                    </flux:menu.item>

                </form>

            </flux:menu>

        </flux:dropdown>
