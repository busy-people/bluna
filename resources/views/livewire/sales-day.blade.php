

<div class="flex h-screen bg-gray-100">
    @include('components.sidebar', ['active' => 'sales'])

    <main class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm">
            <div class="px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800">Sales - Daily</h2>
            </div>
        </header>
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                <h1 class="text-2xl font-bold">Sales — {{ $dateLabel }}</h1>
                <p class="text-sm text-gray-500">Transaksi hari ini</p>
                </div>
                <div class="space-x-2">
                <a href="{{ route('sales.month', $month) }}" class="px-4 py-2 bg-gray-100 rounded-lg">Back</a>
                <button wire:click="addSmall" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow">+ Botol Kecil</button>
                <button wire:click="addLarge" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow">+ Botol Besar</button>
                </div>
            </div>

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

            {{-- transactions: mobile card + desktop table --}}
            <div class="space-y-3">
                {{-- Mobile --}}
                <div class="block sm:hidden space-y-3">
                @forelse($transactions as $t)
                    <div class="bg-white p-4 rounded-xl shadow flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold">{{ $t->product_type === 'small' ? 'Botol Kecil' : 'Botol Besar' }}</span>
                        <span class="text-gray-500 text-sm">{{ $t->created_at->format('H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                        <button wire:click="changeQuantity({{ $t->id }}, -1)" class="px-3 py-1 bg-gray-200 rounded-lg">-</button>
                        <span class="min-w-[24px] text-center">{{ $t->quantity }}</span>
                        <button wire:click="changeQuantity({{ $t->id }}, 1)" class="px-3 py-1 bg-gray-200 rounded-lg">+</button>
                        </div>
                        <span class="font-bold text-green-700">Rp{{ number_format($t->total,0,',','.') }}</span>
                    </div>
                    <button wire:click="deleteSale({{ $t->id }})" class="bg-red-50 text-red-700 px-3 py-2 rounded-lg">Hapus</button>
                    </div>
                @empty
                    <p class="text-gray-500 italic text-center">Belum ada transaksi hari ini.</p>
                @endforelse
                </div>

                {{-- Desktop table --}}
                <div class="hidden sm:block overflow-x-auto bg-white rounded-2xl shadow">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3">#</th>
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
                        <tr><td colspan="7" class="px-4 py-6 text-center italic text-gray-500">Belum ada transaksi hari ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </main>
</div>

