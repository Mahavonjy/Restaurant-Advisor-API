<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function Restaurants() {
        return $this->hasMany(Restaurants::class);
    }

    public function Avis() {
        return $this->hasMany(Avis::class);
    }
}
