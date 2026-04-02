<div class="p-6 max-w-7xl mx-auto text-gray-200">

    <h1 class="text-2xl font-bold mb-6 text-white">Pengumuman</h1>

    {{-- SUCCESS --}}
    @if (session()->has('success'))
        <div class="mb-4 p-3 text-sm text-green-300 bg-green-900/40 border border-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= FORM ================= --}}
    <div class="bg-[#1f2937] p-6 rounded-2xl shadow-lg border border-gray-700">

        <h2 class="text-lg font-semibold mb-4 text-white">
            {{ $announcementId ? 'Edit Pengumuman' : 'Tambah Pengumuman' }}
        </h2>

        <form class="space-y-4">

            <div class="grid md:grid-cols-2 gap-4">

                <!-- CATEGORY -->
                <div>
                    <select
                        wire:model.defer="category"
                        class="px-4 py-2.5 rounded-lg text-sm bg-[#111827] border border-gray-600 text-white w-full"
                    >
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Informasi">Informasi</option>
                        <option value="Promo">Promo</option>
                        <option value="Update">Update</option>
                    </select>

                    @error('category')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- TITLE -->
                <div>
                    <input type="text"
                        wire:model.defer="title"
                        placeholder="Judul pengumuman"
                        class="px-4 py-2.5 rounded-lg text-sm bg-[#111827] border border-gray-600 text-white w-full" />

                    @error('title')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- DESCRIPTION -->
            <div>
                <textarea
                    wire:model.defer="description"
                    rows="4"
                    placeholder="Isi pengumuman..."
                    class="w-full px-4 py-2.5 rounded-lg text-sm bg-[#111827] border border-gray-600 text-white"
                ></textarea>

                @error('description')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- BUTTON --}}
            <div class="flex gap-2">

                @if($announcementId)
                    <button
                        type="button"
                        wire:click="update"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium"
                    >
                        Update
                    </button>

                    <button
                        type="button"
                        wire:click="$set('announcementId', null)"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-lg text-sm"
                    >
                        Batal
                    </button>
                @else
                    <button
                        type="button"
                        wire:click="save"
                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium"
                    >
                        + Tambah
                    </button>
                @endif

            </div>

        </form>

    </div>

    {{-- ================= LIST ================= --}}
    <div class="mt-8 bg-[#1f2937] p-6 rounded-2xl shadow-lg border border-gray-700">

        <h2 class="text-lg font-semibold mb-4 text-white">Daftar Pengumuman</h2>

        <div class="overflow-x-auto rounded-lg border border-gray-700">

            <table class="w-full text-sm">

                <thead class="bg-[#111827] text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Judul</th>
                        <th class="px-4 py-3 text-left">Deskripsi</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-right">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($announcements as $item)
                        <tr class="border-t border-gray-700 hover:bg-[#111827] transition">

                            <td class="px-4 py-3 text-blue-400 font-medium">
                                {{ $item->category }}
                            </td>

                            <td class="px-4 py-3 text-white font-semibold">
                                {{ $item->title }}
                            </td>

                            <td class="px-4 py-3 text-gray-300 max-w-xs truncate">
                                {{ $item->description }}
                            </td>

                            <td class="px-4 py-3 text-gray-400 text-xs">
                                {{ $item->published_at?->format('d M Y') }}
                            </td>

                            <td class="px-4 py-3 text-right">
                                @if($item->is_new)
                                    <span class="bg-green-600 text-white text-xs px-2 py-1 rounded">
                                        BARU
                                    </span>
                                @else
                                    <span class="text-gray-500 text-xs">
                                        Lama
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-right space-x-2">
                                <button
                                    wire:click="edit({{ $item->id }})"
                                    class="text-blue-400 hover:text-blue-300 text-xs"
                                >
                                    Edit
                                </button>

                                <button
                                    wire:click="$dispatch('confirmDelete', { id: {{ $item->id }} })" 
                                    class="text-red-500 hover:underline"> 
                                    Delete 
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">
                                Belum ada pengumuman
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

</div>