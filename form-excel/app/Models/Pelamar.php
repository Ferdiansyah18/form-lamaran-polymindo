<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelamar extends Model
{
    protected $guarded = [];

    public function riwayatPekerjaans()
    {
        return $this->hasMany(RiwayatPekerjaan::class);
    }

    public function keahlians()
    {
        return $this->hasMany(Keahlian::class);
    }

    public function dataAnaks()
    {
        return $this->hasMany(DataAnak::class);
    }
}
