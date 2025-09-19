<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Control_bovine extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function confirmatory_ultrasounds()
    {
        return $this->hasMany(Confirmatory_ultrasound::class);
    }

    public function general_palpation()
    {
        return $this->hasOne(general_palpation::class);
    }

    public function implant_retrieval()
    {
        return $this->hasOne(Implant_retrieval::class);
    }

    public function inseminations()
    {
        return $this->hasMany(Insemination::class);
    }

    public function pre_sincronization()
    {
        return $this->hasOne(Presincronization::class);
    }

    public function ultrasound()
    {
        return $this->hasOne(Ultrasound::class);
    }
}
