<form wire:submit.prevent="save">

<div class="space-y-6">

    <h2 class="text-lg font-semibold mb-6">
        Tambah Soal
    </h2>

    <!-- IMAGE -->
    <div class="mb-6">
        <label class="block mb-2 text-sm font-medium text-heading">
            Gambar (Optional)
        </label>

        <input
            type="file"
            wire:model="image"
            class="block w-full text-sm text-heading">

        @error('image')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror

        @if ($image)
            <div class="mt-3">
                <img src="{{ $image->temporaryUrl() }}" class="w-40 rounded">
            </div>
        @endif
    </div>

    <!-- PERTANYAAN -->
    <div>
        <label class="block mb-2 text-sm font-medium text-heading">
            Pertanyaan
        </label>

        <textarea
            wire:model="question"
            rows="4"
            class="w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium rounded-base"
            placeholder="Masukkan pertanyaan">
        </textarea>

        @error('question')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- PILIHAN JAWABAN -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <label class="text-sm font-medium text-heading">Pilihan A</label>
            <input type="text" wire:model="option_a"
                class="w-full mt-1 px-3 py-2 bg-neutral-secondary-medium border border-default-medium rounded-base">
        </div>

        <div>
            <label class="text-sm font-medium text-heading">Pilihan B</label>
            <input type="text" wire:model="option_b"
                class="w-full mt-1 px-3 py-2 bg-neutral-secondary-medium border border-default-medium rounded-base">
        </div>

        <div>
            <label class="text-sm font-medium text-heading">Pilihan C</label>
            <input type="text" wire:model="option_c"
                class="w-full mt-1 px-3 py-2 bg-neutral-secondary-medium border border-default-medium rounded-base">
        </div>

        <div>
            <label class="text-sm font-medium text-heading">Pilihan D</label>
            <input type="text" wire:model="option_d"
                class="w-full mt-1 px-3 py-2 bg-neutral-secondary-medium border border-default-medium rounded-base">
        </div>

    </div>


    <!-- JAWABAN BENAR -->
    <div>
        <label class="block mb-2 text-sm font-medium text-heading">
            Jawaban Benar
        </label>

        <select
            wire:model="correct_answer"
            class="w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium rounded-base">

            <option value="">Pilih Jawaban</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>

        </select>

        @error('correct_answer')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- BUTTON -->
    <button
        type="submit"
        wire:loading.attr="disabled"
        class="text-white bg-brand hover:bg-brand-strong px-4 py-2.5 rounded-base">

        <span wire:loading.remove wire:target="save">
            Simpan Soal
        </span>

        <span wire:loading wire:target="save">
            Menyimpan...
        </span>

    </button>

</div>

</form>