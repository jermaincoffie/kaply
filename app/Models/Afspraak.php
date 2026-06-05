<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Afspraak extends Model
{
    use HasFactory;

    protected $table = 'afspraken';

    protected $fillable = [
        'klant_id', 'kapper_id', 'dienst_id', 'medewerker_id', 'walk_in_naam', 'datum', 'start_tijd',
        'eind_tijd', 'status', 'betaalmethode', 'notitie',
        'stripe_payment_intent_id', 'stripe_setup_intent_id',
    ];

    protected $casts = ['datum' => 'date'];

    public function getStartTijdAttribute(string $value): string
    {
        return substr($value, 0, 5);
    }

    public function getEindTijdAttribute(string $value): string
    {
        return substr($value, 0, 5);
    }

    public function klant() { return $this->belongsTo(User::class, 'klant_id'); }

    public function getKlantNaamAttribute(): string
    {
        return $this->walk_in_naam
            ? $this->walk_in_naam . ' (walk-in)'
            : ($this->klant?->name ?? '—');
    }
    public function kapper() { return $this->belongsTo(Kapper::class); }
    public function dienst() { return $this->belongsTo(Dienst::class); }
    public function medewerker() { return $this->belongsTo(Medewerker::class); }
}
