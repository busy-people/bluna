<div class="flex h-screen bg-gray-100">
    @include('components.sidebar', ['active' => 'sales'])

    <main class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm">
            <div class="px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800">Sales</h2>
            </div>
        </header>

        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-semibold">Monthly Sales List</h1>
                <div class="space-x-2">
                    <button wire:click="createCurrentMonth"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:opacity-95">
                        Tambah Bulan Ini
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Bulan</th>
                        <th class="px-4 py-3 text-center">Total Hari</th>
                        <th class="px-4 py-3 text-center">Botol Kecil</th>
                        <th class="px-4 py-3 text-center">Botol Besar</th>
                        <th class="px-4 py-3 text-center">Total Botol</th>
                        <th class="px-4 py-3 text-center">Total Transaksi</th>
                        <th class="px-4 py-3 text-right">Pendapatan</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y">
                    @forelse($items as $i => $item)
                        @php
                            $label = \Carbon\Carbon::createFromFormat('Y-m', $item->month)->format('F Y');
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('sales.month', $item->month) }}" class="font-semibold text-indigo-600 hover:underline">
                                    {{ $label }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-center">{{ $item->total_days }}</td>
                            <td class="px-4 py-3 text-center">{{ $item->total_small }}</td>
                            <td class="px-4 py-3 text-center">{{ $item->total_large }}</td>
                            <td class="px-4 py-3 text-center">{{ $item->total_bottles }}</td>
                            <td class="px-4 py-3 text-center">{{ $item->total_transactions }}</td>
                            <td class="px-4 py-3 text-right">Rp{{ number_format($item->total_revenue,0,',','.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('sales.month', $item->month) }}" class="px-3 py-1 text-sm bg-indigo-50 text-indigo-700 rounded">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-gray-500 italic">Belum ada data sales.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
