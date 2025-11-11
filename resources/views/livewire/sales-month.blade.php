<div class="flex h-screen bg-gray-100">
    @include('components.sidebar', ['active' => 'sales'])

    <main class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm">
            <div class="px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800">Sales - Bulanan</h2>
            </div>
        </header>
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Sales — {{ $monthLabel }}</h1>
                <div class="space-x-2">
                <button wire:click="createToday" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow">Add Today</button>
                <a href="{{ route('sales') }}" class="px-4 py-2 bg-gray-100 rounded-lg">Back</a>
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
                        <a href="{{ route('sales.day', [\Carbon\Carbon::parse($d->date)->format('Y-m'), \Carbon\Carbon::parse($d->date)->format('d')]) }}" class="px-3 py-1 text-sm bg-indigo-50 text-indigo-700 rounded">Open</a>
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
</div>
