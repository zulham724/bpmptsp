<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserInvestor extends Authenticatable
{

    use Notifiable;

    protected $guard = 'investor';

    protected $fillable = [
        'name', 'email', 'image', 'provider', 'provider_id', 'password',
    ];
}
