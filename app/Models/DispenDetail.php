<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispenDetail extends Model
{
    protected $table = 'dispen_detail';

    protected $fillable = [
        'id_dispen',
        'nis',
        'nama'
    ];

    public function dispen()
    {
        return $this->belongsTo(Dispen::class, 'id_dispen', 'id_dispen');
    }
    // Di model DispenDetail.php
public function siswa()
{
    return $this->belongsTo(Siswa::class, 'nis', 'nis');
}
}
