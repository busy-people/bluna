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
        @include('components.sidebar', ['active' => 'cashflow'])
    </div>

    <main class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm">
            <div class="px-4 sm:px-8 py-4">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Cash Flow Management</h2>
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
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold">Cash Flow — {{ $periodLabel }}</h1>
                    <p class="text-sm text-gray-500">Kelola pemasukan & pengeluaran</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <select wire:change="changePeriod($event.target.value)" class="px-4 py-2 border rounded-lg text-sm">
                        @for($i = 0; $i < 6; $i++)
                            @php
                                $date = \Carbon\Carbon::now()->subMonths($i);
                                $val = $date->format('Y-m');
                                $label = $date->format('F Y');
                            @endphp
                            <option value="{{ $val }}" {{ $val === $period ? 'selected' : '' }}>{{ $label }}</option>
                        @endfor
                    </select>
                    <button wire:click="openModal" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 text-sm whitespace-nowrap">
                        + Tambah Transaksi
                    </button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-600 mb-1">Total Pemasukan</p>
                            <h3 class="text-xl sm:text-2xl font-bold text-green-600 truncate">Rp{{ number_format($totalIncome, 0, ',', '.') }}</h3>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-600 mb-1">Total Pengeluaran</p>
                            <h3 class="text-xl sm:text-2xl font-bold text-red-600 truncate">Rp{{ number_format($totalExpense, 0, ',', '.') }}</h3>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4 sm:p-6 sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-600 mb-1">Net Cash Flow</p>
                            <h3 class="text-xl sm:text-2xl font-bold {{ $netCashFlow >= 0 ? 'text-blue-600' : 'text-orange-600' }} truncate">
                                Rp{{ number_format($netCashFlow, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expense Breakdown -->
            @if($expenseByCategory->count() > 0)
                <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Pengeluaran per Kategori</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                        @foreach($expenseByCategory as $cat)
                            @php
                                $categories = \App\Models\Cashflow::categories();
                                $catName = $categories['expense'][$cat->category] ?? ucfirst($cat->category);
                            @endphp
                            <div class="p-3 sm:p-4 bg-red-50 rounded-lg">
                                <div class="text-xs sm:text-sm text-red-700 font-medium mb-1">{{ $catName }}</div>
                                <div class="text-base sm:text-lg font-bold text-red-600 truncate">Rp{{ number_format($cat->total, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Cashflow Table/Cards -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Mobile Cards -->
                <div class="block lg:hidden divide-y">
                    @forelse($cashflows as $cf)
                        <div class="p-4" wire:key="cashflow-{{ $cf->id }}">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $cf->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $cf->type === 'income' ? 'Masuk' : 'Keluar' }}
                                    </span>
                                    <div class="mt-1 text-sm text-gray-600">
                                        @php
                                            $categories = \App\Models\Cashflow::categories();
                                            $catName = $categories[$cf->type][$cf->category] ?? ucfirst($cf->category);
                                        @endphp
                                        {{ $catName }}
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500">{{ $cf->date->format('d M Y') }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-lg font-semibold {{ $cf->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $cf->type === 'income' ? '+' : '-' }}Rp{{ number_format($cf->amount, 0, ',', '.') }}
                                </span>
                            </div>
                            @if($cf->description)
                                <div class="text-sm text-gray-600 mb-2">{{ $cf->description }}</div>
                            @endif
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $cf->id }})" class="flex-1 px-3 py-2 text-sm bg-indigo-50 text-indigo-700 rounded-lg">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $cf->id }})"
                                    onclick="return confirm('Yakin hapus transaksi ini?')"
                                    class="flex-1 px-3 py-2 text-sm bg-red-50 text-red-700 rounded-lg">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500 text-sm">Belum ada transaksi di periode ini.</div>
                    @endforelse
                </div>

                <!-- Desktop Table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-center">Tipe</th>
                                <th class="px-4 py-3 text-left">Kategori</th>
                                <th class="px-4 py-3 text-right">Jumlah</th>
                                <th class="px-4 py-3 text-left">Keterangan</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($cashflows as $i => $cf)
                                <tr class="hover:bg-gray-50" wire:key="cashflow-{{ $cf->id }}">
                                    <td class="px-4 py-3">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3">{{ $cf->date->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $cf->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $cf->type === 'income' ? 'Masuk' : 'Keluar' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $categories = \App\Models\Cashflow::categories();
                                            $catName = $categories[$cf->type][$cf->category] ?? ucfirst($cf->category);
                                        @endphp
                                        {{ $catName }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="font-semibold {{ $cf->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $cf->type === 'income' ? '+' : '-' }}Rp{{ number_format($cf->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $cf->description ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center space-x-2">
                                        <button wire:click="edit({{ $cf->id }})" class="px-3 py-1 text-sm bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100">
                                            Edit
                                        </button>
                                        <button wire:click="delete({{ $cf->id }})"
                                            onclick="return confirm('Yakin hapus transaksi ini?')"
                                            class="px-3 py-1 text-sm bg-red-50 text-red-700 rounded hover:bg-red-100">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500 italic">Belum ada transaksi di periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <h3 class="text-lg font-semibold">{{ $editId ? 'Edit Transaksi' : 'Tambah Transaksi' }}</h3>
                    <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button"
                                wire:click="$set('type', 'income')"
                                class="px-4 py-3 rounded-lg border-2 transition {{ $type === 'income' ? 'border-green-600 bg-green-50 text-green-700' : 'border-gray-300 hover:border-green-300' }}">
                                <div class="font-semibold text-sm">💰 Pemasukan</div>
                            </button>
                            <button type="button"
                                wire:click="$set('type', 'expense')"
                                class="px-4 py-3 rounded-lg border-2 transition {{ $type === 'expense' ? 'border-red-600 bg-red-50 text-red-700' : 'border-gray-300 hover:border-red-300' }}">
                                <div class="font-semibold text-sm">💸 Pengeluaran</div>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                        <select wire:model="category" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm" required>
                            @php
                                $categories = \App\Models\Cashflow::categories();
                                $availableCategories = $categories[$type] ?? [];
                            @endphp
                            @foreach($availableCategories as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                        <input wire:model="date" type="date" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm" required>
                        @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp) *</label>
                        <input wire:model="amount" type="number" min="0" step="0.01" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm" required>
                        @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Opsional..."></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" wire:click="closeModal" class="flex-1 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 text-sm">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                            {{ $editId ? 'Update' : 'Simpan' }}
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
            });
        }
    });
</script>
