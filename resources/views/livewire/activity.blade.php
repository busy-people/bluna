<div class="flex h-screen bg-gray-100">
    @include('components.sidebar', ['active' => 'activity'])

    <main class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm">
            <div class="px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800">Activity & Point Management</h2>
            </div>
        </header>

        <div class="p-8">
            @if (session()->has('message'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-semibold">Master Kegiatan & Bobot Point</h1>
                    <p class="text-sm text-gray-500">Atur kegiatan dan point yang didapat</p>
                </div>
                <button wire:click="openModal" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                    + Tambah Aktivitas
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Nama Kegiatan</th>
                            <th class="px-4 py-3 text-left">Kategori</th>
                            <th class="px-4 py-3 text-center">Base Points</th>
                            <th class="px-4 py-3 text-center">Unit</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @php
                            $categories = ['belanja' => 'Belanja', 'produksi' => 'Produksi', 'jaga_stand' => 'Jaga Stand', 'promosi' => 'Promosi', 'administrasi' => 'Administrasi', 'pengembangan' => 'Pengembangan'];
                        @endphp
                        @forelse($activities as $i => $a)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $a->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700">
                                        {{ $categories[$a->category] ?? $a->category }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full font-semibold">{{ $a->base_points }} pts</span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600">{{ $a->unit }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="toggleActive({{ $a->id }})"
                                        class="px-2 py-1 text-xs rounded-full {{ $a->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $a->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-4 py-3 text-center space-x-2">
                                    <button wire:click="edit({{ $a->id }})" class="px-3 py-1 text-sm bg-indigo-50 text-indigo-700 rounded">Edit</button>
                                    <button wire:click="delete({{ $a->id }})"
                                        onclick="return confirm('Yakin hapus aktivitas ini?')"
                                        class="px-3 py-1 text-sm bg-red-50 text-red-700 rounded">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500 italic">Belum ada aktivitas. Jalankan seeder terlebih dahulu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <h3 class="text-lg font-semibold">{{ $editId ? 'Edit Aktivitas' : 'Tambah Aktivitas' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan *</label>
                        <input wire:model="name" type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                        <select wire:model="category" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="belanja">Belanja Bahan</option>
                            <option value="produksi">Produksi</option>
                            <option value="jaga_stand">Jaga Stand</option>
                            <option value="promosi">Marketing & Promosi</option>
                            <option value="administrasi">Administrasi</option>
                            <option value="pengembangan">Pengembangan Bisnis</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Base Points *</label>
                            <input wire:model="base_points" type="number" min="1" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            @error('base_points') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                            <input wire:model="unit" type="text" placeholder="per aktivitas" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" wire:click="closeModal" class="flex-1 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
