<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bull extends Model
{
    public $timestamps = false;
    protected $guarded = [];


    public function inseminations()
    {
        return $this->hasMany(Insemination::class);
    }
}
