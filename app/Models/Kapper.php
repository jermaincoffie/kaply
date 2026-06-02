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
        'telefoon', 'bio', 'stripe_customer_id',
        'abonnement_status', 'actief',
    ];

    protected $casts = ['actief' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function diensten() { return $this->hasMany(Dienst::class); }
    public function beschikbaarheden() { return $this->hasMany(Beschikbaarheid::class); }
    public function sluitingsdagen() { return $this->hasMany(Sluitingsdag::class); }
    public function afspraken() { return $this->hasMany(Afspraak::class); }

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
