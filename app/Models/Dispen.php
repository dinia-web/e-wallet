<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispen extends Model
{
    protected $table = 'dispen';
    protected $primaryKey = 'id_dispen';

    protected $fillable = [
        'nis',
        'nama',
        'kelas',
        'email',
        'id_guru',
        'gurpi',
        'alasan',
        'status',
        'approved_by_admin',
        'approved_by_guru',
        'admin_action',
        'guru_action',
        'rejection_reason'
    ];

    // 🔹 Relasi ke tabel kelas
    public function kelasRel()
    {
        return $this->belongsTo(Kelas::class, 'kelas', 'id_kelas');
    }

    // 🔹 Guru yang diajukan
    public function guru()
    {
        return $this->belongsTo(User::class, 'id_guru', 'id_user');
    }

    // 🔹 Admin yang approve
    public function adminApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_admin', 'id_user');
    }

    // 🔹 Guru yang approve
    public function guruApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_guru', 'id_user');
    }

    // 🔹 Guru piket
    public function guruPiket()
    {
        return $this->belongsTo(Gurpik::class, 'gurpi', 'id_guru');
    }
    public function detail()
{
    return $this->hasMany(DispenDetail::class, 'id_dispen', 'id_dispen');
}

}