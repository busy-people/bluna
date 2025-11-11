<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Sale;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class Sales extends Component
{
    public function render()
    {
        // ambil grouped by month (YYYY-MM)
        $items = Sale::select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as month"),
                DB::raw('COUNT(DISTINCT date) as total_days'),
                DB::raw("SUM(CASE WHEN product_type = 'small' THEN quantity ELSE 0 END) as total_small"),
                DB::raw("SUM(CASE WHEN product_type = 'large' THEN quantity ELSE 0 END) as total_large"),
                DB::raw('SUM(quantity) as total_bottles'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(total) as total_revenue')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return view('livewire.sales', [
            'items' => $items,
        ]);
    }

    // tombol Add: langsung redirect ke bulan ini tanpa membuat data
    public function createCurrentMonth()
    {
        return redirect()->route('sales.month', Carbon::now()->format('Y-m'));
    }
}
