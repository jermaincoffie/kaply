<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sluitingsdag extends Model
{
    protected $table = 'sluitingsdagen';
    protected $fillable = ['kapper_id', 'datum', 'reden'];
    protected $casts = ['datum' => 'date'];
    public function kapper() { return $this->belongsTo(Kapper::class); }
}
