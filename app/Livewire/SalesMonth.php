<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Sale;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class SalesMonth extends Component
{
    public string $month; // format: YYYY-MM (from route param)
    public $showModal = false;
    public $customDate = '';
    public $productType = 'small';
    public $quantity = 1;

    protected $rules = [
        'customDate' => 'required|date',
        'productType' => 'required|in:small,large',
        'quantity' => 'required|integer|min:1',
    ];

    public function mount($month)
    {
        $this->month = $month;
        $this->customDate = Carbon::createFromFormat('Y-m', $month)->format('Y-m-d');
    }

    public function openModal()
    {
        $this->customDate = Carbon::createFromFormat('Y-m', $this->month)->format('Y-m-d');
        $this->productType = 'small';
        $this->quantity = 1;
        $this->showModal = true;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['customDate', 'productType', 'quantity']);
    }

    public function saveCustomSale()
    {
        $this->validate();

        $price = $this->productType === 'small' ? config('sales.price_small') : config('sales.price_large');

        Sale::create([
            'date' => $this->customDate,
            'product_type' => $this->productType,
            'quantity' => $this->quantity,
            'price' => $price,
            'total' => $price * $this->quantity,
        ]);

        session()->flash('message', 'Transaksi berhasil ditambahkan!');
        $this->closeModal();
    }

    public function render()
    {
        [$year, $mon] = explode('-', $this->month);

        $items = Sale::select(
                DB::raw('DATE(date) as date'),
                DB::raw("DATE_FORMAT(date, '%e') as day_no"),
                DB::raw("DAYNAME(date) as day_name"),
                DB::raw("SUM(CASE WHEN product_type = 'small' THEN quantity ELSE 0 END) as total_small"),
                DB::raw("SUM(CASE WHEN product_type = 'large' THEN quantity ELSE 0 END) as total_large"),
                DB::raw('SUM(quantity) as total_bottles'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(total) as total_revenue')
            )
            ->whereYear('date', $year)
            ->whereMonth('date', $mon)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $summary = Sale::whereYear('date', $year)
            ->whereMonth('date', $mon)
            ->select(
                DB::raw("COUNT(DISTINCT date) as total_days"),
                DB::raw("SUM(CASE WHEN product_type = 'small' THEN quantity ELSE 0 END) as total_small"),
                DB::raw("SUM(CASE WHEN product_type = 'large' THEN quantity ELSE 0 END) as total_large"),
                DB::raw('SUM(quantity) as total_bottles'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(total) as total_revenue')
            )->first();

        return view('livewire.sales-month', [
            'items' => $items,
            'summary' => $summary,
            'monthLabel' => Carbon::createFromFormat('Y-m', $this->month)->format('F Y'),
        ]);
    }

    // langsung buka halaman hari ini tanpa membuat data
    public function createToday()
    {
        $month = Carbon::now()->format('Y-m');
        $day = Carbon::now()->format('d');

        return redirect()->route('sales.day', [$month, $day]);
    }
}
