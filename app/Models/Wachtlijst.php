<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wachtlijst extends Model
{
    protected $table = 'wachtlijsten';

    protected $fillable = ['kapper_id', 'klant_id', 'naam', 'email', 'telefoonnummer', 'gewenste_datum', 'status'];

    protected $casts = [
        'gewenste_datum' => 'date',
    ];

    public function kapper(): BelongsTo
    {
        return $this->belongsTo(Kapper::class);
    }

    public function klant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'klant_id');
    }
}
