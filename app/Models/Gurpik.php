<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gurpik extends Model
{
    protected $table = 'gurpik';
    protected $primaryKey = 'id_guru';
    public $timestamps = true;

    protected $fillable = [
        'gurpi',
    ];
}
