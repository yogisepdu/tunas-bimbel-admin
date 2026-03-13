<div class="border border-default rounded-xl bg-neutral-primary-soft p-6 shadow-sm">

    <!-- HEADER -->
    <div class="flex items-start gap-4 mb-5">

        <div class="text-sm font-semibold text-gray-400">
            {{ $slot }}
        </div>

        <div class="flex-1">

            <h3 class="text-lg font-semibold text-heading leading-relaxed">
                {{ $question->question }}
            </h3>

            @if($question->image)
            <div class="mt-4">
                <img
                    src="{{ asset('storage/'.$question->image) }}"
                    class="rounded-lg max-h-60 border border-default shadow-sm">
            </div>
            @endif

        </div>

    </div>


    <!-- OPTIONS -->
    <div class="grid md:grid-cols-2 gap-4">

        <div class="p-4 rounded-lg border transition
            {{ $question->correct_answer == 'A'
            ? 'border-green-500 bg-green-50 text-green-700'
            : 'border-default hover:bg-neutral-secondary-soft' }}">

            <span class="font-semibold">A.</span>
            {{ $question->option_a }}

        </div>


        <div class="p-4 rounded-lg border transition
            {{ $question->correct_answer == 'B'
            ? 'border-green-500 bg-green-50 text-green-700'
            : 'border-default hover:bg-neutral-secondary-soft' }}">

            <span class="font-semibold">B.</span>
            {{ $question->option_b }}

        </div>


        <div class="p-4 rounded-lg border transition
            {{ $question->correct_answer == 'C'
            ? 'border-green-500 bg-green-50 text-green-700'
            : 'border-default hover:bg-neutral-secondary-soft' }}">

            <span class="font-semibold">C.</span>
            {{ $question->option_c }}

        </div>


        <div class="p-4 rounded-lg border transition
            {{ $question->correct_answer == 'D'
            ? 'border-green-500 bg-green-50 text-green-700'
            : 'border-default hover:bg-neutral-secondary-soft' }}">

            <span class="font-semibold">D.</span>
            {{ $question->option_d }}

        </div>

    </div>


    <!-- ACTION -->
    <div class="flex justify-end gap-4 mt-6 text-sm">

        <a
            wire:navigate
            {{-- href="{{ route('question.edit',$question->id) }}" --}}
            class="text-blue-400 hover:text-blue-300">
            Edit
        </a>

        <button
            wire:click="$dispatch('confirmDelete', { id: {{ $question->id }} })"
            class="font-medium text-red-500 hover:underline">
            Delete
        </button>

    </div>

</div>