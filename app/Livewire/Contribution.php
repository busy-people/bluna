<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Contribution as ContributionModel;
use App\Models\Member;
use App\Models\Activity as ActivityModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class Contribution extends Component
{
    public $period; // YYYY-MM
    public $member_id = '';
    public $activity_id = '';
    public $date = '';
    public $quantity = 1;
    public $bonus_points = 0;
    public $notes = '';
    public $showModal = false;
    public $editId = null;

    protected $rules = [
        'member_id' => 'required|exists:members,id',
        'activity_id' => 'required|exists:activities,id',
        'date' => 'required|date',
        'quantity' => 'required|integer|min:1',
        'bonus_points' => 'nullable|integer|min:0',
        'notes' => 'nullable|string',
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
        $this->reset([
            'member_id',
            'activity_id',
            'quantity',
            'bonus_points',
            'notes',
            'editId'
        ]);
        $this->quantity = 1;
        $this->bonus_points = 0;
        $this->resetValidation();
    }

    public function save()
    {
        $validated = $this->validate();

        try {
            if ($this->editId) {
                // Update existing
                $contribution = ContributionModel::findOrFail($this->editId);
                $contribution->update([
                    'member_id' => $validated['member_id'],
                    'activity_id' => $validated['activity_id'],
                    'date' => $validated['date'],
                    'quantity' => $validated['quantity'],
                    'bonus_points' => $validated['bonus_points'] ?? 0,
                    'notes' => $validated['notes'] ?? null,
                ]);

                session()->flash('message', 'Kontribusi berhasil diupdate!');
            } else {
                // Create new
                ContributionModel::create([
                    'member_id' => $validated['member_id'],
                    'activity_id' => $validated['activity_id'],
                    'date' => $validated['date'],
                    'quantity' => $validated['quantity'],
                    'bonus_points' => $validated['bonus_points'] ?? 0,
                    'notes' => $validated['notes'] ?? null,
                    'status' => 'approved',
                ]);

                session()->flash('message', 'Kontribusi berhasil ditambahkan!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $contrib = ContributionModel::findOrFail($id);

            $this->editId = $id;
            $this->member_id = $contrib->member_id;
            $this->activity_id = $contrib->activity_id;
            $this->date = $contrib->date->format('Y-m-d');
            $this->quantity = $contrib->quantity;
            $this->bonus_points = $contrib->bonus_points;
            $this->notes = $contrib->notes;
            $this->showModal = true;

        } catch (\Exception $e) {
            session()->flash('error', 'Data tidak ditemukan!');
        }
    }

    public function delete($id)
    {
        try {
            $contribution = ContributionModel::findOrFail($id);
            $contribution->delete();
            session()->flash('message', 'Kontribusi berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus kontribusi!');
        }
    }

    public function changePeriod($newPeriod)
    {
        return redirect()->route('contribution', $newPeriod);
    }

    public function render()
    {
        [$year, $month] = explode('-', $this->period);

        // Get contributions for current period
        $contributions = ContributionModel::with(['member', 'activity'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get summary per member
        $summary = ContributionModel::select(
                'member_id',
                DB::raw('SUM(total_points) as total_points'),
                DB::raw('COUNT(*) as total_activities')
            )
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', 'approved')
            ->groupBy('member_id')
            ->get();

        $totalPoints = $summary->sum('total_points');

        // Get active members and activities for modal
        $members = Member::where('is_active', true)->orderBy('name')->get();
        $activities = ActivityModel::where('is_active', true)->orderBy('category')->orderBy('name')->get();

        return view('livewire.contribution', [
            'contributions' => $contributions,
            'summary' => $summary,
            'totalPoints' => $totalPoints,
            'members' => $members,
            'activities' => $activities,
            'periodLabel' => Carbon::createFromFormat('Y-m', $this->period)->format('F Y'),
        ]);
    }
}
