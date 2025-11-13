<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\CashFlow as CashFlowModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class CashFlow extends Component
{
    public $period; // YYYY-MM
    public $showModal = false;
    public $editId = null;

    public $date = '';
    public $type = 'expense';
    public $category = 'beli_bahan';
    public $amount = 0;
    public $description = '';

    protected $rules = [
        'date' => 'required|date',
        'type' => 'required|in:income,expense',
        'category' => 'required|string',
        'amount' => 'required|numeric|min:0',
        'description' => 'nullable|string',
    ];

    public function mount($period = null)
    {
        $this->period = $period ?? Carbon::now()->format('Y-m');
        $this->date = Carbon::now()->format('Y-m-d');
    }

    public function openModal()
    {
        $this->resetForm();
        $this->date = Carbon::now()->format('Y-m-d');
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['editId', 'type', 'category', 'amount', 'description']);
        $this->type = 'expense';
        $this->category = 'beli_bahan';
        $this->amount = 0;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editId) {
                $cashflow = CashFlowModel::findOrFail($this->editId);
                $cashflow->update([
                    'date' => $this->date,
                    'type' => $this->type,
                    'category' => $this->category,
                    'amount' => $this->amount,
                    'description' => $this->description,
                ]);
                session()->flash('message', 'Cashflow berhasil diupdate!');
            } else {
                CashFlowModel::create([
                    'date' => $this->date,
                    'type' => $this->type,
                    'category' => $this->category,
                    'amount' => $this->amount,
                    'description' => $this->description,
                ]);
                session()->flash('message', 'Cashflow berhasil ditambahkan!');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $cashflow = CashFlowModel::findOrFail($id);

            $this->editId = $id;
            $this->date = $cashflow->date->format('Y-m-d');
            $this->type = $cashflow->type;
            $this->category = $cashflow->category;
            $this->amount = $cashflow->amount;
            $this->description = $cashflow->description;
            $this->showModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Data tidak ditemukan!');
        }
    }

    public function delete($id)
    {
        try {
            CashFlowModel::findOrFail($id)->delete();
            session()->flash('message', 'Cashflow berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus cashflow!');
        }
    }

    public function changePeriod($newPeriod)
    {
        return redirect()->route('cashflow', $newPeriod);
    }

    public function render()
    {
        [$year, $month] = explode('-', $this->period);

        // Get cashflows for period
        $cashflows = CashFlowModel::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Summary
        $summary = CashFlowModel::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->select(
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            )
            ->first();

        $totalIncome = $summary->total_income ?? 0;
        $totalExpense = $summary->total_expense ?? 0;
        $netCashFlow = $totalIncome - $totalExpense;

        // Expense by category
        $expenseByCategory = CashFlowModel::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('type', 'expense')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        return view('livewire.cash-flow', [
            'cashflows' => $cashflows,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netCashFlow' => $netCashFlow,
            'expenseByCategory' => $expenseByCategory,
            'periodLabel' => Carbon::createFromFormat('Y-m', $this->period)->format('F Y'),
        ]);
    }
}
