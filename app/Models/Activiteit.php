<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activiteit extends Model
{
    protected $fillable = ['kapper_id', 'afspraak_id', 'datum', 'type', 'tekst'];
}
