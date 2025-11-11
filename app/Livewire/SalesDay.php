<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Sale;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class SalesDay extends Component
{
    public string $month; // YYYY-MM
    public string $day;   // DD

    public function mount($month, $day)
    {
        $this->month = $month;
        $this->day = $day;
    }

    public function render()
    {
        $date = Carbon::createFromFormat('Y-m-d', "{$this->month}-{$this->day}")->toDateString();

        $transactions = Sale::whereDate('date', $date)
            ->orderBy('created_at', 'asc')
            ->get();

        $totalRevenue = $transactions->sum('total');
        $totalBottles = $transactions->sum('quantity');

        return view('livewire.sales-day', [
            'transactions' => $transactions,
            'dateLabel' => Carbon::parse($date)->format('d F Y (l)'),
            'totalRevenue' => $totalRevenue,
            'totalBottles' => $totalBottles,
        ]);
    }

    // one-click add small bottle
    public function addSmall()
    {
        $this->addSale('small');
    }

    public function addLarge()
    {
        $this->addSale('large');
    }

    private function addSale(string $type)
    {
        $price = $type === 'small' ? config('sales.price_small') : config('sales.price_large');
        $date = Carbon::createFromFormat('Y-m-d', "{$this->month}-{$this->day}")->toDateString();

        Sale::create([
            'date' => $date,
            'product_type' => $type,
            'quantity' => 1,
            'price' => $price,
            'total' => $price,
        ]);

        // re-render (Livewire will re-render automatically)
    }

    // delete a transaction
    public function deleteSale($id)
    {
        $s = Sale::find($id);
        if ($s) $s->delete();
    }

    // change quantity (increase or decrease)
    public function changeQuantity($id, $delta)
    {
        $s = Sale::find($id);
        if (! $s) return;
        $newQty = max(1, $s->quantity + intval($delta));
        $s->quantity = $newQty;
        $s->total = $s->price * $s->quantity;
        $s->save();
    }
}
