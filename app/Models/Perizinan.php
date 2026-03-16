<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    protected $table = 'perizinan';
    protected $primaryKey = 'id_perizinan';

    protected $fillable = [
        'nis',
        'jenis',
        'keterangan',
        'id_guru',
        'file'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class,'nis','nis');
    }

    public function guru()
    {
        return $this->belongsTo(User::class,'id_guru','id_user');
    }
}
