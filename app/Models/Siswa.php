<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'nis';

    public $incrementing = false; // tetap false karena bukan auto increment
    protected $keyType = 'int';   // sekarang INT

    protected $fillable = [
        'nis',
        'nama',
        'kelas'
    ];
}