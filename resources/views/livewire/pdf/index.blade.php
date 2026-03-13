<div>

    <x-button-add :route="route('pdf.create')" />

    @if (session()->has('success'))
        <div class="mb-4 p-3 text-green-700 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif


    @forelse($classes as $class)

    <div class="mb-10 border border-default rounded-lg overflow-hidden bg-neutral-primary-soft">

        <!-- HEADER KELAS -->
        <div class="px-6 py-4 bg-blue-900/40 border-b border-default flex justify-between items-center">

            <div>
                <h2 class="text-xl font-semibold text-white">
                    {{ $class->name }}
                </h2>

                <p class="text-xs text-gray-400">
                    Kelas
                </p>
            </div>

            <span class="text-xs bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full">
                {{ $class->chapters->count() }} Chapter
            </span>

        </div>


        @foreach($class->chapters as $chapter)

        <div class="border-b border-default-medium">

            <!-- HEADER CHAPTER -->
            <div class="px-8 py-3 bg-neutral-secondary-medium flex justify-between items-center">

                <div>
                    <h4 class="font-medium text-heading">
                        {{ $chapter->title }}
                    </h4>

                    <p class="text-xs text-gray-400">
                        Chapter
                    </p>
                </div>

                <span class="text-xs bg-purple-500/20 text-purple-400 px-2 py-1 rounded">
                    {{ $chapter->materiPdf->count() }} Materi
                </span>

            </div>


            <!-- TABLE MATERI -->
            <div class="px-8 pb-4 pt-2">

                <table class="w-full text-sm table-fixed">

                    <thead class="border-b border-default-medium text-gray-400">
                        <tr>
                            <th class="py-3 text-left w-1/2">Judul Materi</th>
                            <th class="py-3 text-left w-1/4">File</th>
                            <th class="py-3 text-right w-1/4">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($chapter->materiPdf as $pdf)

                        <tr class="border-b border-default hover:bg-neutral-secondary-soft">

                            <td class="py-3 text-heading truncate">
                                {{ $pdf->title }}
                            </td>

                            <td class="py-3">
                                <a 
                                    href="{{ $pdf->pdf_url }}"
                                    target="_blank"
                                    class="text-blue-400 hover:underline">
                                    Lihat PDF
                                </a>
                            </td>

                            <td class="py-3 text-right space-x-3">

                                <a 
                                    wire:navigate
                                    href="{{ route('pdf.edit', $pdf->id) }}"
                                    class="text-blue-500 hover:underline">
                                    Edit
                                </a>

                                <button
                                    wire:click="$dispatch('confirmDelete', { id: {{ $pdf->id }} })"
                                    class="text-red-500 hover:underline">
                                    Delete
                                </button>

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">
                                Belum ada materi
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @endforeach

    </div>

    @empty

        <div class="p-6 text-center text-gray-500">
            Belum ada materi PDF
        </div>

    @endforelse

</div>