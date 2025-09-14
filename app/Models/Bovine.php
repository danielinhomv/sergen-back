<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bovine extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function property()
    {
        return $this->belongsTo(property::class);
    }

    public function control_bovines()
    {
        return $this->hasMany(Control_bovine::class);
    }

    public function control()
    {
        return $this->belongsTo(Control::class);
    }

    public function bovineMother()
    {
        return $this->belongsTo(Bovine::class, 'mother_id');
    }

}
