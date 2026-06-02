<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dienst extends Model
{
    use HasFactory;

    protected $fillable = ['kapper_id', 'naam', 'duur_minuten', 'prijs', 'no_show_bedrag'];

    public function kapper() { return $this->belongsTo(Kapper::class); }

    public function getPrijsInEurosAttribute(): string
    {
        return number_format($this->prijs / 100, 2, ',', '.');
    }

    public function getNoShowBedragInEurosAttribute(): string
    {
        return number_format($this->no_show_bedrag / 100, 2, ',', '.');
    }
}
