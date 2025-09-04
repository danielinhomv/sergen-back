<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Current_session extends Model
{
    public $timestamps=false;
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
