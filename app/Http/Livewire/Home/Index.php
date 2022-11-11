<?php

namespace App\Http\Livewire\Home;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public $new_name;
    public $new_parent;
    public $select_id;
    public $parent_id;
    public $child_id;
    public $bonus = 0;

    public function render()
    {
        // $user = User::find(2);
        // dd($user->getBonus());
        $trees   = User::tree();
        // dd($trees);
        $users   = User::all();
        $parents = User::where('parent_id',null)->get();
        return view('livewire.home.index',compact('parents','users','trees'))
                ->extends('layouts.app')
                ->section('content');
    }

    public function register()
    {
        $this->validate([
            'new_name' => 'required',
        ]);

        User::create([
            'name'      => $this->new_name,
            'parent_id' => $this->new_parent,
        ]);

        session()->flash('succes', 'Berhasil menambahkan member baru');
    }

    public function bonusCount()
    {
        if ($this->select_id) {
            $user        = User::find($this->select_id);
            $this->bonus = $user->getBonus();
        } else {
            session()->flash('succes', 'Pilih member terlebih dahulu');
        }
    }

    public function migrate()
    {
        $user        = User::find($this->child_id);

        $user->update([
            'parent_id' => $this->parent_id,
        ]);

        session()->flash('succes', 'Berhasil memindahkan member');
    }
    
}
