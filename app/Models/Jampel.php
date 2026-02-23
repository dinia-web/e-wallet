<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jampel extends Model
{
    protected $table = 'jampel';
    protected $primaryKey = 'id_jampel';
    public $timestamps = true; // karena tabel jampel punya timestamps

    protected $fillable = [
        'jam',
    ];
}