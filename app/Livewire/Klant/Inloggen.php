<?php

namespace App\Livewire\Klant;

use App\Mail\OtpCodeMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class Inloggen extends Component
{
    public string $stap = 'email';
    public string $email = '';
    public string $fout = '';

    public function mount(): void
    {
        if (request()->get('stap') === 'code' && session('klant_inloggen_email')) {
            $this->email = session('klant_inloggen_email');
            $this->stap  = 'code';
        }

        if (session('fout')) {
            $this->fout = session('fout');
        }
    }

    public function verstuurCode(): void
    {
        $this->validate(['email' => 'required|email']);
        $this->fout = '';

        $email = strtolower($this->email);
        session(['klant_inloggen_email' => $email]);

        if (!User::where('email', $email)->exists()) {
            $this->isNieuwGebruiker = true;
            $this->stap = 'profiel';
            return;
        }

        $this->isNieuwGebruiker = false;
        $this->verzendOtp();
    }

    private function verzendOtp(): void
    {

        $sleutel = 'otp-verstuur:' . request()->ip();
        if (RateLimiter::tooManyAttempts($sleutel, 5)) {
            $this->fout = 'Te veel pogingen. Probeer over een minuut opnieuw.';
            Log::warning('OTP: rate limiter geblokkeerd', ['email' => $this->email]);
            return;
        }
        RateLimiter::hit($sleutel, 60);

        OtpCode::where('email', $this->email)->delete();

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'email'      => $this->email,
            'code'       => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        try {
            Mail::to($this->email)->send(new OtpCodeMail($code));
        } catch (\Throwable $e) {
            Log::error('OTP: mail mislukt', ['email' => $this->email, 'error' => $e->getMessage()]);
            $this->fout = 'Kon geen e-mail versturen. Probeer het later opnieuw.';
            return;
        }

        $this->redirect(route('klant.inloggen', ['stap' => 'code']), navigate: false);
    }

    public function terugNaarEmail(): void
    {
        $this->stap = 'email';
        $this->fout = '';
    }

    public function render()
    {
        return view('livewire.klant.inloggen')->layout('layouts.publiek', ['title' => 'Inloggen']);
    }
}
