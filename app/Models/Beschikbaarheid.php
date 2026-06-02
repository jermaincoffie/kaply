<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beschikbaarheid extends Model
{
    protected $table = 'beschikbaarheden';
    protected $fillable = ['kapper_id', 'dag_van_week', 'start_tijd', 'eind_tijd'];

    public function kapper() { return $this->belongsTo(Kapper::class); }

    public function getDagNaamAttribute(): string
    {
        return ['Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag','Zondag'][$this->dag_van_week] ?? 'Onbekend';
    }
}
