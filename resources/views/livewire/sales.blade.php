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
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Sales</h2>
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
                <h1 class="text-lg sm:text-xl font-semibold">Monthly Sales List</h1>
                <button wire:click="createCurrentMonth"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 text-sm">
                    Tambah Bulan Ini
                </button>
            </div>

            <!-- Mobile Cards -->
            <div class="block lg:hidden space-y-4">
                @forelse($items as $item)
                    @php
                        $label = \Carbon\Carbon::createFromFormat('Y-m', $item->month)->format('F Y');
                    @endphp
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex justify-between items-start mb-3">
                            <a href="{{ route('sales.month', $item->month) }}" class="font-semibold text-indigo-600 hover:underline">
                                {{ $label }}
                            </a>
                            <button wire:click="deleteMonth('{{ $item->month }}')"
                                onclick="return confirm('Yakin hapus semua data bulan {{ $label }}?')"
                                class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="text-gray-600">Total Hari: <span class="font-semibold">{{ $item->total_days }}</span></div>
                            <div class="text-gray-600">Botol: <span class="font-semibold">{{ $item->total_bottles }}</span></div>
                            <div class="text-gray-600">Transaksi: <span class="font-semibold">{{ $item->total_transactions }}</span></div>
                            <div class="text-green-600 font-bold">Rp{{ number_format($item->total_revenue,0,',','.') }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">Belum ada data sales.</p>
                @endforelse
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block bg-white rounded-2xl shadow overflow-hidden">
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
                            <td class="px-4 py-3 text-center space-x-2">
                                <a href="{{ route('sales.month', $item->month) }}" class="px-3 py-1 text-sm bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100">View</a>
                                <button wire:click="deleteMonth('{{ $item->month }}')"
                                    onclick="return confirm('Yakin hapus semua data bulan {{ $label }}? Semua transaksi dan cashflow akan terhapus!')"
                                    class="px-3 py-1 text-sm bg-red-50 text-red-700 rounded hover:bg-red-100">
                                    Hapus
                                </button>
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
