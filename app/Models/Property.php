<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function bovines()
    {
        return $this->hasMany(Bovine::class);
    }

    public function controls()
    {
        return $this->hasMany(Control::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
