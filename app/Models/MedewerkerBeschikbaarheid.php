<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedewerkerBeschikbaarheid extends Model
{
    protected $table = 'medewerker_beschikbaarheden';

    protected $fillable = ['medewerker_id', 'dag_van_week', 'start_tijd', 'eind_tijd'];

    public function medewerker()
    {
        return $this->belongsTo(Medewerker::class);
    }
}
