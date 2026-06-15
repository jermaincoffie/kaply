<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kapper extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'salon_naam', 'slug', 'adres', 'stad',
        'telefoon', 'bio', 'foto', 'stripe_customer_id',
        'abonnement_status', 'actief', 'buffer_minuten', 'onboarding_voltooid',
    ];

    protected $casts = [
        'actief'              => 'boolean',
        'onboarding_voltooid' => 'boolean',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function diensten() { return $this->hasMany(Dienst::class); }
    public function beschikbaarheden() { return $this->hasMany(Beschikbaarheid::class); }
    public function sluitingsdagen() { return $this->hasMany(Sluitingsdag::class); }
    public function afspraken() { return $this->hasMany(Afspraak::class); }
    public function medewerkers() { return $this->hasMany(Medewerker::class); }
    public function reviews()        { return $this->hasMany(Review::class); }
    public function kortingscodes() { return $this->hasMany(Kortingscode::class); }
    public function galerij()    { return $this->hasMany(KapperGalerij::class)->orderBy('volgorde'); }

    public function setSalonNaamAttribute(string $value): void
    {
        $this->attributes['salon_naam'] = ucwords(strtolower($value));
    }

    public function setStadAttribute(string $value): void
    {
        $this->attributes['stad'] = ucwords(strtolower($value));
    }

    public function setAdresAttribute(?string $value): void
    {
        $this->attributes['adres'] = $value ? ucwords(strtolower($value)) : null;
    }

    public static function generateSlug(string $naam): string
    {
        $base = Str::slug($naam);
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }
}
