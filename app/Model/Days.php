<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Days extends Model
{
    public function Restaurant() {
        return $this->hasMany(Restaurants::class);
    }
}
