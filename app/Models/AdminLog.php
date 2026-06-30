<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $fillable = ['admin_id', 'actie', 'kapper_naam', 'kapper_id', 'details'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function kapper()
    {
        return $this->belongsTo(Kapper::class);
    }

    public static function schrijf(string $actie, ?Kapper $kapper = null, ?string $details = null): void
    {
        static::create([
            'admin_id'    => auth()->id(),
            'actie'       => $actie,
            'kapper_naam' => $kapper?->salon_naam,
            'kapper_id'   => $kapper?->id,
            'details'     => $details,
        ]);
    }
}
