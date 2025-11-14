<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Sale;
use App\Models\Contribution;
use App\Models\Member;
use App\Models\CashFlow;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }

    public function render()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        [$year, $month] = explode('-', $currentMonth);

        // Sales Data (Income)
        $salesData = Sale::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->select(
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('SUM(quantity) as total_bottles'),
                DB::raw('COUNT(*) as total_transactions')
            )
            ->first();

        // Cashflow Data
        $cashFlowData = CashFlow::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->select(
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            )
            ->first();

        // Contributions Data
        $contributionsData = Contribution::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', 'approved')
            ->select(
                DB::raw('SUM(total_points) as total_points'),
                DB::raw('COUNT(*) as total_contributions')
            )
            ->first();

        // Top Contributors
        $topContributors = Contribution::select(
                'member_id',
                DB::raw('SUM(total_points) as total_points')
            )
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', 'approved')
            ->groupBy('member_id')
            ->orderBy('total_points', 'desc')
            ->limit(4)
            ->get();

        // ===== PERHITUNGAN PAYROLL AKURAT =====
        // Total Omzet = Sales + Income dari Cashflow
        $totalRevenue = $cashFlowData->total_income ?? 0;

        // Total Pengeluaran dari Cashflow
        $totalExpense = $cashFlowData->total_expense ?? 0;

        // Net Revenue (Omzet - Pengeluaran)
        $netRevenue = $totalRevenue - $totalExpense;

        // Operational Cost 35% dari Net Revenue
        $operationalCost = $netRevenue * 0.35;

        // Net Salary 65% dari Net Revenue
        $netSalary = $netRevenue * 0.65;

        // Point calculation
        $totalPoints = $contributionsData->total_points ?? 0;
        $pointValue = $totalPoints > 0 ? $netSalary / $totalPoints : 0;

        // Active Members
        $activeMembers = Member::where('is_active', true)->count();

        return view('livewire.dashboard', [
            'salesData' => $salesData,
            'cashFlowData' => $cashFlowData,
            'contributionsData' => $contributionsData,
            'topContributors' => $topContributors,
            'totalRevenue' => $totalRevenue,
            'totalExpense' => $totalExpense,
            'netRevenue' => $netRevenue,
            'operationalCost' => $operationalCost,
            'netSalary' => $netSalary,
            'totalPoints' => $totalPoints,
            'pointValue' => $pointValue,
            'activeMembers' => $activeMembers,
            'currentMonth' => Carbon::now()->format('F Y'),
        ]);
    }
}
