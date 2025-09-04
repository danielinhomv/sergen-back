<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Control extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function control_bovines()
    {
        return $this->hasMany(Control_bovine::class);
    }
}
