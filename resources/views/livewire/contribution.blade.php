<div class="flex h-screen bg-gray-100">
    @include('components.sidebar', ['active' => 'contribution'])

    <main class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm">
            <div class="px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800">Contribution Tracking</h2>
            </div>
        </header>

        <div class="p-8">
            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg flex items-center justify-between">
                    <span>{{ session('message') }}</span>
                    <button wire:click="$set('flash', null)" class="text-green-900">&times;</button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button wire:click="$set('flash', null)" class="text-red-900">&times;</button>
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-semibold">Kontribusi — {{ $periodLabel }}</h1>
                    <p class="text-sm text-gray-500">Total: {{ $totalPoints }} points</p>
                </div>
                <div class="flex gap-2">
                    <select wire:change="changePeriod($event.target.value)" class="px-4 py-2 border rounded-lg">
                        @for($i = 0; $i < 6; $i++)
                            @php
                                $date = \Carbon\Carbon::now()->subMonths($i);
                                $val = $date->format('Y-m');
                                $label = $date->format('F Y');
                            @endphp
                            <option value="{{ $val }}" {{ $val === $period ? 'selected' : '' }}>{{ $label }}</option>
                        @endfor
                    </select>
                    <button wire:click="openModal" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                        + Tambah Kontribusi
                    </button>
                </div>
            </div>

            <!-- Summary Cards -->
            @if($summary->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    @foreach($summary as $s)
                        @php
                            $member = $members->firstWhere('id', $s->member_id);
                            $percentage = $totalPoints > 0 ? ($s->total_points / $totalPoints) * 100 : 0;
                        @endphp
                        @if($member)
                            <div class="bg-white rounded-lg shadow p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <span class="text-2xl font-bold text-indigo-600">{{ number_format($percentage, 1) }}%</span>
                                </div>
                                <h3 class="font-semibold text-gray-800">{{ $member->name }}</h3>
                                <div class="flex justify-between text-sm text-gray-600 mt-2">
                                    <span>{{ $s->total_points }} pts</span>
                                    <span>{{ $s->total_activities }} aktivitas</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            <!-- Contributions Table -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Member</th>
                            <th class="px-4 py-3 text-left">Kegiatan</th>
                            <th class="px-4 py-3 text-center">Qty</th>
                            <th class="px-4 py-3 text-center">Base Pts</th>
                            <th class="px-4 py-3 text-center">Bonus</th>
                            <th class="px-4 py-3 text-center">Total Pts</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($contributions as $i => $c)
                            <tr class="hover:bg-gray-50" wire:key="contribution-{{ $c->id }}">
                                <td class="px-4 py-3">{{ $i + 1 }}</td>
                                <td class="px-4 py-3">{{ $c->date->format('d M Y') }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $c->member->name }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $c->activity->name }}</div>
                                    <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $c->activity->category)) }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">{{ $c->quantity }}x</td>
                                <td class="px-4 py-3 text-center">{{ $c->activity->base_points }}</td>
                                <td class="px-4 py-3 text-center">{{ $c->bonus_points > 0 ? '+'.$c->bonus_points : '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full font-semibold">{{ $c->total_points }} pts</span>
                                </td>
                                <td class="px-4 py-3 text-center space-x-2">
                                    <button wire:click="edit({{ $c->id }})" class="px-3 py-1 text-sm bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100">
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $c->id }})"
                                        onclick="return confirm('Yakin hapus kontribusi ini?')"
                                        class="px-3 py-1 text-sm bg-red-50 text-red-700 rounded hover:bg-red-100">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-6 text-center text-gray-500 italic">Belum ada kontribusi di periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <h3 class="text-lg font-semibold">{{ $editId ? 'Edit Kontribusi' : 'Tambah Kontribusi' }}</h3>
                    <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Member *</label>
                        <select wire:model="member_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">-- Pilih Member --</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                        @error('member_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kegiatan *</label>
                        <select wire:model="activity_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">-- Pilih Kegiatan --</option>
                            @foreach($activities as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->base_points }} pts / {{ $a->unit }})</option>
                            @endforeach
                        </select>
                        @error('activity_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                        <input wire:model="date" type="date" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                        @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                            <input wire:model="quantity" type="number" min="1" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bonus Points</label>
                            <input wire:model="bonus_points" type="number" min="0" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @error('bonus_points') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea wire:model="notes" rows="2" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                        @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" wire:click="closeModal" class="flex-1 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            {{ $editId ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
