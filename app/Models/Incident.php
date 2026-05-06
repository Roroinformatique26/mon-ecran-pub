<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $primaryKey = 'id_incident';
    public $incrementing  = true;
    protected $keyType    = 'int';
    public $timestamps    = false;

    protected $fillable = [
        'date_emis', 'photo_ouverte', 'photo_fermee', 'date_maj',
        'date_cloture', 'discipline', 'systeme', 'lot_travail',
        'zone_id', 'etiquette', 'description', 'categorie',
        'interne', 'statut', 'responsabilite', 'emis_par',
        'qfc_ouvert', 'qfc_ferme', 'cloture_prevue',
    ];

   public function zoneObj()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
    }

    public const CATEGORIES = [
        'A' => 'A — Avant Pre-commissioning',
        'B' => 'B — Avant la Mechanical Completion',
        'C' => 'C — Après la Mechanical Completion',
        'D' => 'D — Après la Mise en route',
    ];

    public function getCategorieLabelAttribute()
    {
        return self::CATEGORIES[$this->categorie] ?? $this->categorie;
    }
}