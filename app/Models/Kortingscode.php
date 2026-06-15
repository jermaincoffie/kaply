<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kortingscode extends Model
{
    use HasFactory;

    protected $fillable = [
        'kapper_id', 'code', 'type', 'waarde',
        'max_gebruik', 'gebruik_teller', 'geldig_van', 'geldig_tot', 'actief',
    ];

    protected $casts = [
        'actief'     => 'boolean',
        'geldig_van' => 'date',
        'geldig_tot' => 'date',
    ];

    public function kapper() { return $this->belongsTo(Kapper::class); }
    public function afspraken() { return $this->hasMany(Afspraak::class); }

    public function isGeldig(): bool
    {
        if (!$this->actief) return false;
        if ($this->geldig_van && today()->lt($this->geldig_van)) return false;
        if ($this->geldig_tot && today()->gt($this->geldig_tot)) return false;
        if ($this->max_gebruik !== null && $this->gebruik_teller >= $this->max_gebruik) return false;
        return true;
    }

    public function berekenKorting(int $prijsInCenten): int
    {
        if ($this->type === 'percentage') {
            return min((int) round($prijsInCenten * $this->waarde / 100), $prijsInCenten);
        }
        return min($this->waarde, $prijsInCenten);
    }

    public function getLabelAttribute(): string
    {
        return $this->type === 'percentage'
            ? "{$this->waarde}% korting"
            : '€ ' . number_format($this->waarde / 100, 2, ',', '.');
    }
}
