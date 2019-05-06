<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }
}
