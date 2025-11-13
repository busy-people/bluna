<div class="flex h-screen bg-gray-100">
    @include('components.sidebar', ['active' => 'dashboard'])

    <main class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800">Dashboard Overview — {{ $currentMonth }}</h2>
                <div class="flex items-center space-x-4">
                    <button class="relative p-2 text-gray-600 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <div class="p-8">
            <!-- Stats Cards Row 1: Revenue & Expenses -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Revenue (Sales + Income) -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Omzet</p>
                            <h3 class="text-2xl font-bold text-blue-600">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            <p class="text-xs text-blue-500 mt-2">Penjualan + Pemasukan</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Expenses -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Pengeluaran</p>
                            <h3 class="text-2xl font-bold text-red-600">Rp{{ number_format($totalExpense, 0, ',', '.') }}</h3>
                            <p class="text-xs text-red-500 mt-2">Belanja & Operasional</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Net Revenue -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Omzet Bersih</p>
                            <h3 class="text-2xl font-bold text-emerald-600">Rp{{ number_format($netRevenue, 0, ',', '.') }}</h3>
                            <p class="text-xs text-emerald-500 mt-2">Omzet - Pengeluaran</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Point Value -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nilai Per Point</p>
                            <h3 class="text-2xl font-bold text-purple-600">Rp{{ number_format($pointValue, 0, ',', '.') }}</h3>
                            <p class="text-xs text-purple-500 mt-2">{{ $totalPoints }} total points</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards Row 2: Payroll Breakdown -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Operational Cost (35%) -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-orange-700 mb-1">Modal Operasional (35%)</p>
                            <h3 class="text-3xl font-bold text-orange-900">Rp{{ number_format($operationalCost, 0, ',', '.') }}</h3>
                            <p class="text-xs text-orange-600 mt-2">Untuk putar modal & operasional</p>
                        </div>
                        <div class="w-16 h-16 bg-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Net Salary (65%) -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-700 mb-1">Gaji Bersih (65%)</p>
                            <h3 class="text-3xl font-bold text-green-900">Rp{{ number_format($netSalary, 0, ',', '.') }}</h3>
                            <p class="text-xs text-green-600 mt-2">Dibagi ke {{ $activeMembers }} member berdasarkan point</p>
                        </div>
                        <div class="w-16 h-16 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Contributors & Recent Activities -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Top Contributors dengan Proyeksi Gaji -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Contributors & Proyeksi Gaji</h3>
                    <div class="space-y-4">
                        @forelse($topContributors as $contributor)
                            @php
                                $member = \App\Models\Member::find($contributor->member_id);
                                $percentage = $totalPoints > 0 ? ($contributor->total_points / $totalPoints) * 100 : 0;
                                $salary = $contributor->total_points * $pointValue;
                            @endphp
                            <div class="flex items-center justify-between pb-3 border-b">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-indigo-600 font-semibold">{{ strtoupper(substr($member->name ?? 'U', 0, 1)) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-800">{{ $member->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-500">{{ $contributor->total_points }} points ({{ number_format($percentage, 1) }}%)</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-green-600 font-bold">Rp{{ number_format($salary, 0, ',', '.') }}</div>
                                    <div class="text-xs text-gray-500">Proyeksi Gaji</div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">Belum ada kontribusi bulan ini</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                    <div class="space-y-4">
                        @php
                            $recentContributions = \App\Models\Contribution::with(['member', 'activity'])
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        @foreach($recentContributions as $rc)
                            <div class="flex items-start">
                                <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3"></div>
                                <div>
                                    <p class="text-sm text-gray-800">
                                        <span class="font-semibold">{{ $rc->member->name }}</span> — {{ $rc->activity->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        +{{ $rc->total_points }} points • {{ $rc->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Formula Explanation -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg shadow p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">💡 Cara Perhitungan Gaji</h3>
                <div class="space-y-2 text-sm text-gray-700">
                    <p><span class="font-semibold">1. Omzet Bersih</span> = Total Omzet - Total Pengeluaran</p>
                    <p><span class="font-semibold">2. Modal (35%)</span> = Omzet Bersih × 35% → untuk belanja bahan & operasional</p>
                    <p><span class="font-semibold">3. Gaji (65%)</span> = Omzet Bersih × 65% → untuk dibagi ke member</p>
                    <p><span class="font-semibold">4. Nilai/Point</span> = Gaji ÷ Total Point Semua Member</p>
                    <p><span class="font-semibold">5. Gaji Member</span> = Point Member × Nilai/Point</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <a href="{{ route('sales') }}" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition text-center">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <p class="text-sm text-gray-600">Input Sales</p>
                    </a>
                    <a href="{{ route('cashflow') }}" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition text-center">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-gray-600">Cashflow</p>
                    </a>
                    <a href="{{ route('contribution') }}" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition text-center">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <p class="text-sm text-gray-600">Kontribusi</p>
                    </a>
                    <a href="{{ route('member') }}" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition text-center">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p class="text-sm text-gray-600">Member</p>
                    </a>
                    <a href="{{ route('activity') }}" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition text-center">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm text-gray-600">Activity</p>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
