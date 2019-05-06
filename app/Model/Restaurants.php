<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Restaurants extends Model
{
    public function user() {
        return $this->hasMany(User::class);
    }

    public function Avis() {
        return $this->hasMany(Avis::class);
    }

    public function Days() {
        return $this->hasOne(Days::class);
    }
}