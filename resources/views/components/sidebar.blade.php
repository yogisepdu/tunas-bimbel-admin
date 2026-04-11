        <flux:sidebar.nav>

            <!-- Dashboard -->
            <flux:sidebar.item
                icon="home"
                href="{{ route('dashboard') }}"
                wire:navigate
                :current="request()->routeIs('dashboard')"
                class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                Dashboard
            </flux:sidebar.item>

            <!-- User Management -->
            <flux:sidebar.group expandable heading="User Management" class="grid">

                <flux:sidebar.item
                    icon="users"
                    href="{{ route('student.index') }}"
                    wire:navigate
                    :current="request()->routeIs('student*')"
                    class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium"
                >
                    Siswa
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="user"
                    href="{{ route('teacher.index') }}"
                    wire:navigate
                    :current="request()->routeIs('teacher*')"
                    class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                    Teacher
                </flux:sidebar.item>

                
            </flux:sidebar.group>
            
            <!-- Materi -->
            <flux:sidebar.group expandable heading="Materi" class="grid">
                <flux:sidebar.item
                    icon="gift"
                    href="{{ route('packages.index') }}"
                    wire:navigate
                    :current="request()->routeIs('packages*')"
                    class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                    Packages
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="book-open"
                    href="{{ route('course.index') }}"
                    wire:navigate
                    :current="request()->routeIs('course*')"
                    class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                    Kelas
                </flux:sidebar.item>
                

                <flux:sidebar.item
                    icon="book-open"
                    href="{{ route('sub-course.index') }}"
                    wire:navigate
                    :current="request()->routeIs('sub-course*')"
                    class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                    Sub Materi
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="book-open"
                    href="{{ route('video.index') }}"
                    wire:navigate
                    :current="request()->routeIs('video*')"
                    class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                    Video
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="document-text"
                    href="{{ route('pdf.index') }}"
                    wire:navigate
                    :current="request()->routeIs('pdf*')"
                    class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                    Materi PDF
                </flux:sidebar.item>

            </flux:sidebar.group>

            <!-- Quiz -->
            <flux:sidebar.item
                icon="clipboard-document-list"
                href="{{ route('quiz.index') }}"
                wire:navigate
                :current="request()->routeIs('quiz*')"
                class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                Quiz
            </flux:sidebar.item>

            {{-- Quiz --}}
            <flux:sidebar.group expandable heading="Soal TryOut" class="grid">

                <flux:sidebar.item
                    icon="clipboard-document-list"
                    href="{{ route('soal-section.index') }}"
                    wire:navigate
                    :current="request()->routeIs('soal-section*')"
                    class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                    Soal Section
                </flux:sidebar.item>

                {{-- Soal Set --}}
                <flux:sidebar.item
                    icon="clipboard-document-list"
                    href="{{ route('soal-set.index') }}"
                    wire:navigate
                    :current="request()->routeIs('soal-set*')"
                    class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                    Soal Set
                </flux:sidebar.item>

                {{-- Soal Question --}}
                <flux:sidebar.item
                    icon="clipboard-document-list"
                    href="{{ route('soal-question.index') }}"
                    wire:navigate
                    :current="request()->routeIs('soal-question.index')"
                    class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                    Soal Question
                </flux:sidebar.item>
            </flux:sidebar.group>
            <!-- Kalender -->

            <flux:sidebar.item
                icon="calendar"
                href="{{ route('kalender.index') }}"
                wire:navigate
                :current="request()->routeIs('kalender*')"
                class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                Kalender Akademik
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="link"
                href="{{ route('linked.index') }}"
                wire:navigate
                :current="request()->routeIs('linked*')"
                class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                Linked
            </flux:sidebar.item>
            
            <flux:sidebar.item
                icon="bell"
                href="{{ route('announcement.index') }}"
                wire:navigate
                :current="request()->routeIs('announcement*')"
                class="hover:bg-zinc-800 hover:text-white data-[current]:bg-zinc-800 data-[current]:text-white font-medium">
                Announcement
            </flux:sidebar.item>

            <!-- Laporan -->
            <flux:sidebar.group expandable heading="Laporan" class="grid">

                <flux:sidebar.item icon="chart-pie">
                    Progress Belajar
                </flux:sidebar.item>

                <flux:sidebar.item icon="document-chart-bar">
                    Nilai Quiz
                </flux:sidebar.item>

            </flux:sidebar.group>

        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth">Settings</flux:sidebar.item>
            <flux:sidebar.item icon="information-circle">Help</flux:sidebar.item>
        </flux:sidebar.nav>

        <!-- PROFILE -->
        <flux:dropdown position="top" align="start" class="max-lg:hidden">

            <flux:sidebar.profile
                avatar="https://fluxui.dev/img/demo/user.png"
                name="{{ auth()->user()->name ?? 'User' }}"
            />

            <flux:menu>

                <flux:menu.radio.group>
                    <flux:menu.radio checked>
                        {{ auth()->user()->name ?? 'User' }}
                    </flux:menu.radio>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <flux:menu.item
                        icon="arrow-right-start-on-rectangle"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Logout
                    </flux:menu.item>

                </form>

            </flux:menu>

        </flux:dropdown>

