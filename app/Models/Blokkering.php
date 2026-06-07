<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blokkering extends Model
{
    protected $table = 'blokkeringen';
    protected $fillable = ['kapper_id', 'datum', 'start_tijd', 'eind_tijd', 'reden'];

    protected $casts = ['datum' => 'date'];

    public function kapper() { return $this->belongsTo(Kapper::class); }
}
