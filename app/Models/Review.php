<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['kapper_id', 'klant_id', 'afspraak_id', 'rating', 'tekst', 'zichtbaar'];

    protected $casts = ['zichtbaar' => 'boolean', 'rating' => 'integer'];

    public function kapper()   { return $this->belongsTo(Kapper::class); }
    public function klant()    { return $this->belongsTo(User::class, 'klant_id'); }
    public function afspraak() { return $this->belongsTo(Afspraak::class); }
}
