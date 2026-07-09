<?php

namespace App\Livewire\Klant;

use App\Mail\OtpCodeMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
    public string $voornaam = '';
    public string $achternaam = '';
    public string $telefoon = '';
    public bool $isNieuwGebruiker = false;

    public function verstuurCode(): void
    {
        $this->validate(['email' => 'required|email']);
        $this->fout = '';

        if (!User::where('email', strtolower($this->email))->exists()) {
            $this->isNieuwGebruiker = true;
            $this->stap = 'profiel';
            return;
        }

        $this->isNieuwGebruiker = false;
        $this->verzendOtp();
    }

    public function vulProfielIn(): void
    {
        Log::info('OTP: vulProfielIn aangeroepen', ['email' => $this->email, 'voornaam' => $this->voornaam]);
        $this->validate([
            'voornaam'   => 'required|string|min:2',
            'achternaam' => 'required|string|min:2',
            'telefoon'   => 'required|string|min:8|max:20',
        ]);
        $this->fout = '';
        $this->verzendOtp();
    }

    private function verzendOtp(): void
    {
        Log::info('OTP: verzendOtp gestart', ['email' => $this->email]);

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

        Log::info('OTP: code aangemaakt, mail versturen...', ['email' => $this->email]);

        try {
            Mail::to($this->email)->send(new OtpCodeMail($code));
            Log::info('OTP: mail verzonden', ['email' => $this->email]);
        } catch (\Throwable $e) {
            Log::error('OTP: mail mislukt', ['email' => $this->email, 'error' => $e->getMessage()]);
            $this->fout = 'Kon geen e-mail versturen. Probeer het later opnieuw.';
            return;
        }

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
            $this->isNieuwGebruiker ? [
                'name'       => trim($this->voornaam . ' ' . $this->achternaam),
                'voornaam'   => $this->voornaam,
                'achternaam' => $this->achternaam,
                'telefoon'   => $this->telefoon,
                'password'   => bcrypt(str()->random(32)),
                'role'       => 'klant',
            ] : [
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
        $this->isNieuwGebruiker = false;
        $this->voornaam = '';
        $this->achternaam = '';
        $this->telefoon = '';
    }

    public function render()
    {
        return view('livewire.klant.inloggen')->layout('layouts.publiek', ['title' => 'Inloggen']);
    }
}
