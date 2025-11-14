<div class="flex flex-col lg:flex-row h-screen bg-gray-100">
    <!-- Mobile Sidebar Toggle -->
    <div class="lg:hidden">
        <button id="sidebar-toggle" class="fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="hidden lg:block">
        @include('components.sidebar', ['active' => 'sales'])
    </div>

    <main class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm">
            <div class="px-4 sm:px-8 py-4">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Sales - Monthly</h2>
            </div>
        </header>

        <div class="p-4 sm:p-8">
            @if (session()->has('message'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h1 class="text-lg sm:text-2xl font-bold">Sales — {{ $monthLabel }}</h1>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="openModal" class="px-4 py-2 bg-purple-600 text-white rounded-lg shadow hover:bg-purple-700 text-sm">
                        📅 Tambah Custom
                    </button>
                    <button wire:click="createToday" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 text-sm">
                        Add Today
                    </button>
                    <a href="{{ route('sales') }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 text-sm">Back</a>
                </div>
            </div>

            <div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-sm text-gray-500">Total Hari Aktif</div>
                    <div class="text-xl font-semibold">{{ $summary->total_days ?? 0 }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-sm text-gray-500">Total Botol (Kecil / Besar)</div>
                    <div class="text-xl font-semibold">{{ $summary->total_small ?? 0 }} / {{ $summary->total_large ?? 0 }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-sm text-gray-500">Pendapatan</div>
                    <div class="text-xl font-semibold">Rp{{ number_format($summary->total_revenue ?? 0,0,',','.') }}</div>
                </div>
            </div>

            <!-- Mobile Cards -->
            <div class="block lg:hidden space-y-4">
                @forelse($items as $d)
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex justify-between items-start mb-3">
                            <a href="{{ route('sales.day', [\Carbon\Carbon::parse($d->date)->format('Y-m'), \Carbon\Carbon::parse($d->date)->format('d')]) }}"
                                class="font-semibold text-indigo-600 hover:underline">
                                {{ \Carbon\Carbon::parse($d->date)->format('d M Y (l)') }}
                            </a>
                            <button wire:click="deleteDay('{{ $d->date }}')"
                                onclick="return confirm('Yakin hapus semua transaksi tanggal ini?')"
                                class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>Botol Kecil: <span class="font-semibold">{{ $d->total_small }}</span></div>
                            <div>Botol Besar: <span class="font-semibold">{{ $d->total_large }}</span></div>
                            <div>Total: <span class="font-semibold">{{ $d->total_bottles }}</span></div>
                            <div class="text-green-600 font-bold">Rp{{ number_format($d->total_revenue,0,',','.') }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">Belum ada transaksi di bulan ini.</p>
                @endforelse
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block bg-white rounded-2xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3 text-center">Botol Kecil</th>
                            <th class="px-4 py-3 text-center">Botol Besar</th>
                            <th class="px-4 py-3 text-center">Total Botol</th>
                            <th class="px-4 py-3 text-center">Transaksi</th>
                            <th class="px-4 py-3 text-right">Pendapatan</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $i => $d)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $i + 1 }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('sales.day', [\Carbon\Carbon::parse($d->date)->format('Y-m'), \Carbon\Carbon::parse($d->date)->format('d')]) }}"
                                        class="font-semibold text-indigo-600 hover:underline">
                                        {{ \Carbon\Carbon::parse($d->date)->format('d M Y') }} — {{ \Carbon\Carbon::parse($d->date)->format('l') }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-center">{{ $d->total_small }}</td>
                                <td class="px-4 py-3 text-center">{{ $d->total_large }}</td>
                                <td class="px-4 py-3 text-center">{{ $d->total_bottles }}</td>
                                <td class="px-4 py-3 text-center">{{ $d->total_transactions }}</td>
                                <td class="px-4 py-3 text-right">Rp{{ number_format($d->total_revenue,0,',','.') }}</td>
                                <td class="px-4 py-3 text-center space-x-2">
                                    <a href="{{ route('sales.day', [\Carbon\Carbon::parse($d->date)->format('Y-m'), \Carbon\Carbon::parse($d->date)->format('d')]) }}"
                                        class="px-3 py-1 text-sm bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100">Open</a>
                                    <button wire:click="deleteDay('{{ $d->date }}')"
                                        onclick="return confirm('Yakin hapus semua transaksi tanggal {{ \Carbon\Carbon::parse($d->date)->format('d F Y') }}?')"
                                        class="px-3 py-1 text-sm bg-red-50 text-red-700 rounded hover:bg-red-100">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-4 py-6 text-center italic text-gray-500">Belum ada transaksi di bulan ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Custom Date dengan Location -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Tambah Transaksi Custom</h3>
                    <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveCustomSale" class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Produk *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button"
                                wire:click="$set('productType', 'small')"
                                class="px-4 py-3 rounded-lg border-2 transition {{ $productType === 'small' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-gray-300 hover:border-blue-300' }}">
                                <div class="font-semibold text-sm">Botol Kecil</div>
                                <div class="text-xs">Rp{{ number_format(config('sales.price_small'), 0, ',', '.') }}</div>
                            </button>
                            <button type="button"
                                wire:click="$set('productType', 'large')"
                                class="px-4 py-3 rounded-lg border-2 transition {{ $productType === 'large' ? 'border-green-600 bg-green-50 text-green-700' : 'border-gray-300 hover:border-green-300' }}">
                                <div class="font-semibold text-sm">Botol Besar</div>
                                <div class="text-xs">Rp{{ number_format(config('sales.price_large'), 0, ',', '.') }}</div>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <select wire:model="location_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                            <option value="">-- Pilih Lokasi (Opsional) --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                        <input wire:model="customDate" type="date" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm" required>
                        @error('customDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah *</label>
                        <input wire:model="quantity" type="number" min="1" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm" required>
                        @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Harga Satuan:</span>
                            <span class="font-semibold">Rp{{ number_format($productType === 'small' ? config('sales.price_small') : config('sales.price_large'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Jumlah:</span>
                            <span class="font-semibold">{{ $quantity }}x</span>
                        </div>
                        <div class="flex justify-between text-base font-bold border-t pt-2 mt-2">
                            <span>Total:</span>
                            <span class="text-green-600">Rp{{ number_format(($productType === 'small' ? config('sales.price_small') : config('sales.price_large')) * $quantity, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" wire:click="closeModal" class="flex-1 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 text-sm">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');

        if (toggle && sidebar) {
            toggle.addEventListener('click', function() {
                sidebar.classList.toggle('hidden');
                sidebar.classList.toggle('fixed');
                sidebar.classList.toggle('inset-0');
                sidebar.classList.toggle('z-40');
                sidebar.classList.toggle('bg-white');
            });
        }
    });
</script>
