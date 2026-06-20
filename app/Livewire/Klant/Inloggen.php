<?php

namespace App\Livewire\Klant;

use App\Mail\OtpCodeMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Inloggen extends Component
{
    public string $stap = 'email';
    public string $email = '';
    public string $code = '';
    public string $fout = '';

    public function verstuurCode(): void
    {
        $this->validate(['email' => 'required|email']);
        $this->fout = '';

        $sleutel = 'otp-verstuur:' . request()->ip();
        if (RateLimiter::tooManyAttempts($sleutel, 5)) {
            $this->fout = 'Te veel pogingen. Probeer over een minuut opnieuw.';
            return;
        }
        RateLimiter::hit($sleutel, 60);

        // Verwijder oude codes voor dit emailadres
        OtpCode::where('email', $this->email)->delete();

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'email'      => $this->email,
            'code'       => $code,
            'expires_at' => now()->addMinutes(15),
        ]);

        Mail::to($this->email)->send(new OtpCodeMail($code));

        $this->stap = 'code';
        $this->dispatch('otp-fokus');
    }

    public function verifieerCode(string $otpCode = ''): void
    {
        if ($otpCode !== '') {
            $this->code = $otpCode;
        }
        $this->validate(['code' => 'required|digits:6']);
        $this->fout = '';

        $sleutel = 'otp-verify:' . request()->ip();
        if (RateLimiter::tooManyAttempts($sleutel, 10)) {
            $this->fout = 'Te veel pogingen. Probeer later opnieuw.';
            return;
        }
        RateLimiter::hit($sleutel, 60);

        $otp = OtpCode::where('email', $this->email)
            ->where('code', $this->code)
            ->first();

        if (!$otp || !$otp->isGeldig()) {
            $this->fout = 'Ongeldige of verlopen code. Probeer opnieuw.';
            return;
        }

        $otp->update(['used_at' => now()]);

        $user = User::firstOrCreate(
            ['email' => $this->email],
            [
                'name'     => explode('@', $this->email)[0],
                'password' => bcrypt(str()->random(32)),
                'role'     => 'klant',
            ]
        );

        Auth::login($user, remember: true);

        $redirect = session()->pull('url.intended', route('klant.afspraken'));
        $this->redirect($redirect, navigate: false);
    }

    public function terugNaarEmail(): void
    {
        $this->stap = 'email';
        $this->code = '';
        $this->fout = '';
    }

    public function render()
    {
        return view('livewire.klant.inloggen')->layout('layouts.publiek', ['title' => 'Inloggen']);
    }
}
