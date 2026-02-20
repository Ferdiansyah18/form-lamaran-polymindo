<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataAnak extends Model
{
    protected $guarded = [];

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class);
    }
}
