<div>

<x-button-add :route="route('quiz.create')" />

@if (session()->has('success'))
<div class="mb-4 p-3 text-green-700 bg-green-100 rounded">
    {{ session('success') }}
</div>
@endif


@forelse($classes as $class)

<div class="mb-10 bg-neutral-primary-soft shadow-xs rounded-base border border-default">

    <!-- CLASS HEADER -->
    <div class="px-6 py-4 bg-neutral-secondary-medium border-b border-default-medium flex justify-between items-center">

        <h3 class="text-lg font-semibold text-heading">
            {{ $class->name }}
        </h3>

        <span class="text-xs bg-blue-500/10 text-blue-500 px-2 py-1 rounded">
            {{ $class->quizzes->count() }} Quiz
        </span>

    </div>


    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($class->quizzes as $quiz)

        <div class="bg-neutral-secondary-medium border border-default-medium rounded-base shadow-xs p-5 flex flex-col justify-between">

            <div>

                <h5 class="font-semibold text-heading text-base">
                    {{ $quiz->title }}
                </h5>

                <div class="mt-2 text-sm text-body space-y-1">

                    <div>
                        ⏱ Durasi :
                        <span class="font-medium">
                            {{ $quiz->duration }} menit
                        </span>
                    </div>

                    <div>
                        ❓ Soal :
                        <span class="font-medium">
                            {{ $quiz->questions->count() }}
                        </span>
                    </div>

                </div>

            </div>


            <div class="mt-5 flex justify-between items-center text-sm">

                <a 
                    wire:navigate
                    href="{{ route('question.index', ['quiz' => $quiz->id]) }}"
                    class="text-brand font-medium hover:underline">

                    Kelola Soal
                </a>
                <a
                    wire:navigate
                    href="{{ route('quiz.edit', $quiz->id) }}"
                    class="text-fg-brand hover:underline"> 
                    Edit
                </a>

                <button
                    wire:click="$dispatch('confirmDelete', { id: {{ $quiz->id }} })" 
                    class="text-red-500 hover:underline"> 
                    Delete 
                </button>

            </div>

        </div>

        @empty

        <div class="text-gray-500 text-sm">
            Belum ada quiz
        </div>

        @endforelse

        </div>

    </div>

</div>

@empty

<div class="p-6 text-center text-gray-500">
    Belum ada data kelas
</div>

@endforelse

</div>