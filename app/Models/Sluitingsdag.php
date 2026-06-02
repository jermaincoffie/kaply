<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sluitingsdag extends Model
{
    protected $table = 'sluitingsdagen';
    protected $fillable = ['kapper_id', 'datum', 'datum_tot', 'reden'];
    protected $casts = ['datum' => 'date', 'datum_tot' => 'date'];
    public function kapper() { return $this->belongsTo(Kapper::class); }
}
