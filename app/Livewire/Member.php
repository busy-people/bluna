<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Member as MemberModel;

#[Layout('layouts.app')]
class Member extends Component
{
    public $members;
    public $showModal = false;
    public $editId = null;

    public $name = '';
    public $email = '';
    public $phone = '';
    public $role = 'member';
    public $notes = '';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'phone' => 'nullable',
        'role' => 'required',
        'notes' => 'nullable',
    ];

    public function mount()
    {
        $this->loadMembers();
    }

    public function loadMembers()
    {
        $this->members = MemberModel::orderBy('name')->get();
    }

    public function openModal()
    {
        $this->reset(['name', 'email', 'phone', 'role', 'notes', 'editId']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'email', 'phone', 'role', 'notes', 'editId']);
    }

    public function save()
    {
        $this->validate();

        if ($this->editId) {
            $member = MemberModel::find($this->editId);
            $member->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'role' => $this->role,
                'notes' => $this->notes,
            ]);
        } else {
            MemberModel::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'role' => $this->role,
                'notes' => $this->notes,
            ]);
        }

        $this->loadMembers();
        $this->closeModal();
        session()->flash('message', 'Member berhasil disimpan!');
    }

    public function edit($id)
    {
        $member = MemberModel::find($id);
        $this->editId = $id;
        $this->name = $member->name;
        $this->email = $member->email;
        $this->phone = $member->phone;
        $this->role = $member->role;
        $this->notes = $member->notes;
        $this->showModal = true;
    }

    public function delete($id)
    {
        MemberModel::find($id)->delete();
        $this->loadMembers();
        session()->flash('message', 'Member berhasil dihapus!');
    }

    public function toggleActive($id)
    {
        $member = MemberModel::find($id);
        $member->is_active = !$member->is_active;
        $member->save();
        $this->loadMembers();
    }

    public function render()
    {
        return view('livewire.member');
    }
}
