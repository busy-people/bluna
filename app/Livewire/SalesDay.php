<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Sale;
use App\Models\SalesLocation;
use Illuminate\Support\Carbon;

#[Layout('layouts.app')]
class SalesDay extends Component
{
    public string $month;
    public string $day;
    public $showModal = false;
    public $modalType = 'small';
    public $customDate = '';
    public $customQuantity = 1;
    public $location_id = null;
    public $quickAddLocation = null; // For quick add buttons

    protected $rules = [
        'customDate' => 'required|date',
        'customQuantity' => 'required|integer|min:1',
        'location_id' => 'nullable|exists:sales_locations,id',
    ];

    public function mount($month, $day)
    {
        $this->month = $month;
        $this->day = $day;
        $this->customDate = Carbon::createFromFormat('Y-m-d', "{$month}-{$day}")->format('Y-m-d');
    }

    public function render()
    {
        $date = Carbon::createFromFormat('Y-m-d', "{$this->month}-{$this->day}")->toDateString();

        $transactions = Sale::with('location')
            ->whereDate('date', $date)
            ->orderBy('location_id')
            ->orderBy('created_at', 'asc')
            ->get();

        $totalRevenue = $transactions->sum('total');
        $totalBottles = $transactions->sum('quantity');

        // Summary by location
        $locationSummary = Sale::with('location')
            ->whereDate('date', $date)
            ->selectRaw('location_id, SUM(quantity) as total_bottles, SUM(total) as total_revenue, COUNT(*) as total_transactions')
            ->groupBy('location_id')
            ->get();

        $locations = SalesLocation::where('is_active', true)->orderBy('name')->get();

        return view('livewire.sales-day', [
            'transactions' => $transactions,
            'dateLabel' => Carbon::parse($date)->format('d F Y (l)'),
            'totalRevenue' => $totalRevenue,
            'totalBottles' => $totalBottles,
            'locationSummary' => $locationSummary,
            'locations' => $locations,
        ]);
    }

    public function setQuickLocation($locationId)
    {
        $this->quickAddLocation = $locationId;
    }

    public function addSmall()
    {
        $this->addSale('small', $this->quickAddLocation);
        // DON'T reset location - keep it selected
        // $this->quickAddLocation remains the same
    }

    public function addLarge()
    {
        $this->addSale('large', $this->quickAddLocation);
        // DON'T reset location - keep it selected
        // $this->quickAddLocation remains the same
    }

    private function addSale(string $type, $locationId = null)
    {
        $price = $type === 'small' ? config('sales.price_small') : config('sales.price_large');
        $date = Carbon::createFromFormat('Y-m-d', "{$this->month}-{$this->day}")->toDateString();

        Sale::create([
            'date' => $date,
            'location_id' => $locationId,
            'product_type' => $type,
            'quantity' => 1,
            'price' => $price,
            'total' => $price,
        ]);

        session()->flash('message', 'Transaksi berhasil ditambahkan!');
    }

    public function openModal($type)
    {
        $this->modalType = $type;
        $this->customDate = Carbon::createFromFormat('Y-m-d', "{$this->month}-{$this->day}")->format('Y-m-d');
        $this->customQuantity = 1;
        $this->location_id = null;
        $this->showModal = true;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['modalType', 'customDate', 'customQuantity', 'location_id']);
    }

    public function saveCustomSale()
    {
        $this->validate();

        $price = $this->modalType === 'small' ? config('sales.price_small') : config('sales.price_large');

        Sale::create([
            'date' => $this->customDate,
            'location_id' => $this->location_id,
            'product_type' => $this->modalType,
            'quantity' => $this->customQuantity,
            'price' => $price,
            'total' => $price * $this->customQuantity,
        ]);

        session()->flash('message', 'Transaksi berhasil ditambahkan!');
        $this->closeModal();
    }

    public function deleteSale($id)
    {
        if ($s = Sale::find($id)) {
            $s->delete(); // Auto delete cashflow via model event
            session()->flash('message', 'Transaksi berhasil dihapus!');
        }
    }

    public function changeQuantity($id, $delta)
    {
        if (! $s = Sale::find($id)) return;

        $newQty = max(1, $s->quantity + intval($delta));
        $s->quantity = $newQty;
        $s->total = $s->price * $s->quantity;
        $s->save(); // Auto update cashflow via model event
    }
}
