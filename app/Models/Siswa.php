<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'nis';

    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'nis',
        'nama',
        'kelas'
    ];

    // RELASI KE USER
    public function user()
    {
        return $this->hasOne(User::class, 'nis', 'nis');
    }
}