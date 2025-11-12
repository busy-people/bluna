<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Activity as ActivityModel;

#[Layout('layouts.app')]
class Activity extends Component
{
    public $activities;
    public $showModal = false;
    public $editId = null;

    public $name = '';
    public $category = 'belanja';
    public $base_points = 10;
    public $unit = 'per aktivitas';
    public $description = '';

    protected $rules = [
        'name' => 'required|min:3',
        'category' => 'required',
        'base_points' => 'required|integer|min:1',
        'unit' => 'required',
        'description' => 'nullable',
    ];

    public function mount()
    {
        $this->loadActivities();
    }

    public function loadActivities()
    {
        $this->activities = ActivityModel::orderBy('category')->orderBy('name')->get();
    }

    public function openModal()
    {
        $this->reset(['name', 'category', 'base_points', 'unit', 'description', 'editId']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'category', 'base_points', 'unit', 'description', 'editId']);
    }

    public function save()
    {
        $this->validate();

        if ($this->editId) {
            $activity = ActivityModel::find($this->editId);
            $activity->update([
                'name' => $this->name,
                'category' => $this->category,
                'base_points' => $this->base_points,
                'unit' => $this->unit,
                'description' => $this->description,
            ]);
        } else {
            ActivityModel::create([
                'name' => $this->name,
                'category' => $this->category,
                'base_points' => $this->base_points,
                'unit' => $this->unit,
                'description' => $this->description,
            ]);
        }

        $this->loadActivities();
        $this->closeModal();
        session()->flash('message', 'Aktivitas berhasil disimpan!');
    }

    public function edit($id)
    {
        $activity = ActivityModel::find($id);
        $this->editId = $id;
        $this->name = $activity->name;
        $this->category = $activity->category;
        $this->base_points = $activity->base_points;
        $this->unit = $activity->unit;
        $this->description = $activity->description;
        $this->showModal = true;
    }

    public function delete($id)
    {
        ActivityModel::find($id)->delete();
        $this->loadActivities();
        session()->flash('message', 'Aktivitas berhasil dihapus!');
    }

    public function toggleActive($id)
    {
        $activity = ActivityModel::find($id);
        $activity->is_active = !$activity->is_active;
        $activity->save();
        $this->loadActivities();
    }

    public function render()
    {
        return view('livewire.activity');
    }
}
