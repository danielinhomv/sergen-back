<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bull extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function births()
    {
        return $this->hasMany(Bull::class);
    }
}
