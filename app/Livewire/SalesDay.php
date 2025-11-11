<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Sale;
use Illuminate\Support\Carbon;

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
    }

    public function deleteSale($id)
    {
        if ($s = Sale::find($id)) {
            $s->delete();
        }
    }

    public function changeQuantity($id, $delta)
    {
        if (! $s = Sale::find($id)) return;

        $newQty = max(1, $s->quantity + intval($delta));
        $s->quantity = $newQty;
        $s->total = $s->price * $s->quantity;
        $s->save();
    }
}
