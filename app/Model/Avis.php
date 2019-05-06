<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    public function Menu() {
        return $this->hasOne(Menu::class);
    }

    public function Restaurant() {
        return $this->hasOne(Restaurants::class);
    }
}
