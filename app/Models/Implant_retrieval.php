<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Implant_retrieval extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function control_bovine()
    {
        return $this->belongsTo(Control_bovine::class);
    }
}
