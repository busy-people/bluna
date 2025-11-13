<div class="flex h-screen bg-gray-100">
    @include('components.sidebar', ['active' => 'sales'])

    <main class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm">
            <div class="px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800">Sales - Daily</h2>
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
                    <h1 class="text-2xl font-bold">Sales — {{ $dateLabel }}</h1>
                    <p class="text-sm text-gray-500">Transaksi hari ini</p>
                </div>
                <div class="space-x-2">
                    <a href="{{ route('sales.month', $month) }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Back</a>
                    <button wire:click="addSmall" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">+ Botol Kecil</button>
                    <button wire:click="addLarge" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700">+ Botol Besar</button>
                    <button wire:click="openModal('custom')" class="px-4 py-2 bg-purple-600 text-white rounded-lg shadow hover:bg-purple-700">📅 Custom</button>
                </div>
            </div>

            <!-- Overall Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-sm text-gray-500">Total Pendapatan</div>
                    <div class="text-xl font-semibold">Rp{{ number_format($totalRevenue,0,',','.') }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-sm text-gray-500">Total Botol</div>
                    <div class="text-xl font-semibold">{{ $totalBottles }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-sm text-gray-500">Jumlah Transaksi</div>
                    <div class="text-xl font-semibold">{{ $transactions->count() }}</div>
                </div>
            </div>

            <!-- Location Summary -->
            @if($locationSummary->count() > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Penjualan per Lokasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($locationSummary as $ls)
                            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-lg shadow">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-sm text-indigo-900 font-medium mb-1">{{ $ls->location->name ?? 'No Location' }}</div>
                                <div class="text-xs text-indigo-700">{{ $ls->total_bottles }} botol</div>
                                <div class="text-lg font-bold text-indigo-900">Rp{{ number_format($ls->total_revenue, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- transactions: mobile card + desktop table --}}
            <div class="space-y-3">
                {{-- Desktop table --}}
                <div class="overflow-x-auto bg-white rounded-2xl shadow">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Lokasi</th>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3 text-right">Harga</th>
                                <th class="px-4 py-3 text-right">Total</th>
                                <th class="px-4 py-3">Waktu</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $idx => $s)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $idx + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-700 rounded">
                                            {{ $s->location->name ?? 'No Location' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $s->product_type === 'small' ? 'Botol Kecil' : 'Botol Besar' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="inline-flex items-center gap-2">
                                            <button wire:click="changeQuantity({{ $s->id }}, -1)" class="px-2 py-1 bg-gray-200 rounded">-</button>
                                            <span>{{ $s->quantity }}</span>
                                            <button wire:click="changeQuantity({{ $s->id }}, 1)" class="px-2 py-1 bg-gray-200 rounded">+</button>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">Rp{{ number_format($s->price,0,',','.') }}</td>
                                    <td class="px-4 py-3 text-right">Rp{{ number_format($s->total,0,',','.') }}</td>
                                    <td class="px-4 py-3">{{ $s->created_at->format('H:i') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <button wire:click="deleteSale({{ $s->id }})" class="px-3 py-1.5 text-sm bg-red-50 text-red-700 rounded-lg">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="px-4 py-6 text-center italic text-gray-500">Belum ada transaksi hari ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Custom Date dengan Location -->
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
                                wire:click="$set('modalType', 'small')"
                                class="px-4 py-3 rounded-lg border-2 transition {{ $modalType === 'small' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-gray-300 hover:border-blue-300' }}">
                                <div class="font-semibold">Botol Kecil</div>
                                <div class="text-xs">Rp{{ number_format(config('sales.price_small'), 0, ',', '.') }}</div>
                            </button>
                            <button type="button"
                                wire:click="$set('modalType', 'large')"
                                class="px-4 py-3 rounded-lg border-2 transition {{ $modalType === 'large' ? 'border-green-600 bg-green-50 text-green-700' : 'border-gray-300 hover:border-green-300' }}">
                                <div class="font-semibold">Botol Besar</div>
                                <div class="text-xs">Rp{{ number_format(config('sales.price_large'), 0, ',', '.') }}</div>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Penjualan</label>
                        <select wire:model="location_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih Lokasi (Opsional) --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                        @error('location_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                        <input wire:model="customDate" type="date" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                        @error('customDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah *</label>
                        <input wire:model="customQuantity" type="number" min="1" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                        @error('customQuantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Harga Satuan:</span>
                            <span class="font-semibold">Rp{{ number_format($modalType === 'small' ? config('sales.price_small') : config('sales.price_large'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Jumlah:</span>
                            <span class="font-semibold">{{ $customQuantity }}x</span>
                        </div>
                        <div class="flex justify-between text-base font-bold border-t pt-2 mt-2">
                            <span>Total:</span>
                            <span class="text-green-600">Rp{{ number_format(($modalType === 'small' ? config('sales.price_small') : config('sales.price_large')) * $customQuantity, 0, ',', '.') }}</span>
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
