<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use Billable;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'voornaam',
        'achternaam',
        'telefoon',
        'email',
        'password',
        'role',
        'stripe_id',
        'stripe_payment_method_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKapper(): bool
    {
        return $this->role === 'kapper';
    }

    public function isKlant(): bool
    {
        return $this->role === 'klant';
    }

    public function kapper() { return $this->hasOne(Kapper::class); }
    public function klantprofiel() { return $this->hasOne(Klant::class); }
    public function afspraken() { return $this->hasMany(\App\Models\Afspraak::class, 'klant_id'); }
    public function klantNotitie() { return $this->hasOne(\App\Models\KlantNotitie::class, 'klant_id')->where('kapper_id', auth()->user()?->kapper?->id); }
    public function favorieteKappers() { return $this->belongsToMany(Kapper::class, 'klant_favoriete_kappers'); }
}
