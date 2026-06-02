<?php

namespace App\Livewire\Kapper;

use App\Models\Kapper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Registratie extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $salon_naam = '';
    public string $stad = '';
    public string $telefoon = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'salon_naam' => 'required|string|max:255',
            'stad' => 'required|string|max:255',
            'telefoon' => 'nullable|string|max:20',
        ];
    }

    public function registreer()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        $user->role = 'kapper';
        $user->save();

        Kapper::create([
            'user_id' => $user->id,
            'salon_naam' => $this->salon_naam,
            'slug' => Kapper::generateSlug($this->salon_naam),
            'stad' => $this->stad,
            'telefoon' => $this->telefoon,
            'abonnement_status' => 'geen',
            'actief' => false,
        ]);

        Auth::login($user);

        return redirect()->route('kapper.dashboard');
    }

    public function render()
    {
        return view('livewire.kapper.registratie')->layout('layouts.publiek');
    }
}
