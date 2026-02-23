<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users'; // karena tabel kamu namanya user
    protected $primaryKey = 'id_user'; // primary key kamu
    public $timestamps = true; 
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'foto'
    ];

    protected $hidden = [
        'password'
    ];
    
}