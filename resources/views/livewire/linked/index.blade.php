<div class="p-6 max-w-7xl mx-auto text-gray-200">

    <h1 class="text-2xl font-bold mb-6 text-white">Link Management</h1>

    {{-- SUCCESS --}}
    @if (session()->has('success'))
        <div class="mb-4 p-3 text-sm text-green-300 bg-green-900/40 border border-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= FORM ================= --}}
    <div class="bg-[#1f2937] p-6 rounded-2xl shadow-lg border border-gray-700">

        <h2 class="text-lg font-semibold mb-4 text-white">Tambah Link</h2>

        <form wire:submit.prevent="saveLink" class="space-y-4">

            <div class="grid md:grid-cols-2 gap-4">

                <!-- NAMA -->
                <select
                    wire:model.defer="link_name"
                    class="px-4 py-2.5 rounded-lg text-sm bg-[#111827] border border-gray-600 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none w-full"
                >
                    <option value="">-- Pilih Jenis Link --</option>
                    <option value="peta_seleksi">Peta Seleksi</option>
                    <option value="informasi_beasiswa">Informasi Beasiswa</option>
                    <option value="informasi_kampus">Informasi Kampus</option>
                    <option value="grup_mentoring">Grup Mentoring</option>
                </select>

                @error('link_name')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror

                <!-- URL -->
                <input type="text"
                    wire:model.defer="link_url"
                    placeholder="https://..."
                    class="px-4 py-2.5 rounded-lg text-sm bg-[#111827] border border-gray-600 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none w-full" />

                @error('link_url')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror

            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="bg-green-600 hover:bg-green-700 transition text-white px-5 py-2.5 rounded-lg text-sm font-medium shadow"
            >
                <span wire:loading.remove>+ Tambah Link</span>
                <span wire:loading>Menyimpan...</span>
            </button>

        </form>

    </div>

    {{-- ================= LIST ================= --}}
    <div class="mt-8 bg-[#1f2937] p-6 rounded-2xl shadow-lg border border-gray-700">

        <h2 class="text-lg font-semibold mb-4 text-white">Daftar Link</h2>

        <div class="overflow-x-auto rounded-lg border border-gray-700">
            @php
                $linkLabels = [
                    'peta_seleksi' => '📍 Peta Seleksi',
                    'informasi_beasiswa' => '🎓 Informasi Beasiswa',
                    'informasi_kampus' => '🏫 Informasi Kampus',
                    'grup_mentoring' => '👥 Grup Mentoring',
                ];
            @endphp
            <table class="w-full text-sm">

                <thead class="bg-[#111827] text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Link</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($links as $link)
                        <tr class="border-t border-gray-700 hover:bg-[#111827] transition">

                            <!-- NAMA -->
                            <td class="px-4 py-3 font-medium text-white">
                                {{ $linkLabels[$link->name] ?? $link->name }}
                            </td>

                            <!-- LINK -->
                            <td class="px-4 py-3">
                                <a href="{{ $link->url }}" target="_blank"
                                   class="text-blue-400 hover:text-blue-300 hover:underline break-all">
                                    {{ $link->url }}
                                </a>
                            </td>

                            <!-- AKSI -->
                            <td class="px-4 py-3 text-right">
                                <button
                                    wire:click="deleteLink({{ $link->id }})"
                                    class="text-red-400 hover:text-red-300 text-xs font-medium"
                                >
                                    Hapus
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-gray-500">
                                Belum ada link
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

</div>