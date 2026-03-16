<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Siswa;

class User extends Authenticatable
{
    protected $table = 'users'; 
    protected $primaryKey = 'id_user';
    public $timestamps = true;

    protected $fillable = [
        'nis', // tambahkan ini
        'username',
        'email',
        'password',
        'role',
        'foto',
        'is_walikelas',
    ];

    protected $hidden = [
        'password'
    ];

    // RELASI KE SISWA
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }
}