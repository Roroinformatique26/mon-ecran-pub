<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    public $timestamps = false;   // ← ta table n'a pas ces colonnes

    protected $fillable = ['name'];

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'zone_id', 'id');
    }
}