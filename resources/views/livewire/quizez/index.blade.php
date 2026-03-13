<div>

<div class="flex justify-between items-center mb-8">

    <div>

        <h2 class="text-xl font-semibold text-heading">
            Kelola Soal
        </h2>

        <p class="text-sm text-gray-400 mt-1">
            Quiz : {{ $quiz->title }}
        </p>

    </div>

    <a
        wire:navigate
        href="{{ route('question.create', ['quiz' => $quiz->id]) }}"
        class="bg-brand hover:bg-brand-strong text-white px-5 py-2.5 rounded-lg text-sm shadow">

        + Tambah Soal

    </a>

</div>


<div class="space-y-6">

@forelse($questions as $question)

<x-question-card :question="$question">
    {{ $loop->iteration }}.
</x-question-card>

@empty

<div class="text-center py-12 text-gray-500">

    <p class="text-lg font-medium">
        Belum ada soal
    </p>

    <p class="text-sm mt-2">
        Klik tombol <strong>Tambah Soal</strong> untuk membuat soal pertama
    </p>

</div>

@endforelse

</div>

</div>