<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KapperGalerij extends Model
{
    protected $table = 'kapper_galerij';

    protected $fillable = ['kapper_id', 'pad', 'volgorde'];

    public function kapper()
    {
        return $this->belongsTo(Kapper::class);
    }
}
