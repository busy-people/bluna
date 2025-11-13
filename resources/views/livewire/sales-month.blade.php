<div class="flex h-screen bg-gray-100">
    @include('components.sidebar', ['active' => 'sales'])

    <main class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm">
            <div class="px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800">Sales - Monthly</h2>
            </div>
        </header>
        <div class="p-8">
            @if (session()->has('message'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Sales — {{ $monthLabel }}</h1>
                <div class="space-x-2">
                    <button wire:click="openModal" class="px-4 py-2 bg-purple-600 text-white rounded-lg shadow hover:bg-purple-700">
                        📅 Tambah Custom
                    </button>
                    <button wire:click="createToday" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                        Add Today
                    </button>
                    <a href="{{ route('sales') }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Back</a>
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

            <div class="bg-white rounded-2xl shadow overflow-hidden">
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
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('sales.day', [\Carbon\Carbon::parse($d->date)->format('Y-m'), \Carbon\Carbon::parse($d->date)->format('d')]) }}"
                                        class="px-3 py-1 text-sm bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100">Open</a>
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

    <!-- Modal Custom Date -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
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
                                <div class="font-semibold">Botol Kecil</div>
                                <div class="text-xs">Rp{{ number_format(config('sales.price_small'), 0, ',', '.') }}</div>
                            </button>
                            <button type="button"
                                wire:click="$set('productType', 'large')"
                                class="px-4 py-3 rounded-lg border-2 transition {{ $productType === 'large' ? 'border-green-600 bg-green-50 text-green-700' : 'border-gray-300 hover:border-green-300' }}">
                                <div class="font-semibold">Botol Besar</div>
                                <div class="text-xs">Rp{{ number_format(config('sales.price_large'), 0, ',', '.') }}</div>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                        <input wire:model="customDate" type="date" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                        @error('customDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah *</label>
                        <input wire:model="quantity" type="number" min="1" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
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
                        <button type="button" wire:click="closeModal" class="flex-1 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
