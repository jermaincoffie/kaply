<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlantNotitie extends Model
{
    protected $table = 'klant_notities';
    protected $fillable = ['kapper_id', 'klant_id', 'notities'];

    public function kapper() { return $this->belongsTo(Kapper::class); }
    public function klant()  { return $this->belongsTo(User::class, 'klant_id'); }
}
