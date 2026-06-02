<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medewerker extends Model
{
    protected $fillable = ['kapper_id', 'naam', 'foto', 'actief'];
    protected $casts = ['actief' => 'boolean'];

    public function kapper() { return $this->belongsTo(Kapper::class); }
    public function afspraken() { return $this->hasMany(Afspraak::class); }
}
