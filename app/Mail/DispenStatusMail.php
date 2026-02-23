<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class DispenStatusMail extends Mailable
{
    public $data;
    public $detail; // ✅ tambahin ini
public $status_guru_pengajar;
public $status_guru_piket;
public $kelasNama;
public $jamKeluarNama;
public $jamKembaliNama;
public $guruNama;
public $guruPiketNama;

public function __construct(
    $data,
    $detail,
    $status_guru_pengajar,
    $status_guru_piket,
    $kelasNama,
    $jamKeluarNama,
    $jamKembaliNama,
    $guruNama,
    $guruPiketNama
){
    $this->data = $data;
    $this->detail = $detail;
    $this->status_guru_pengajar = $status_guru_pengajar;
    $this->status_guru_piket = $status_guru_piket;
    $this->kelasNama = $kelasNama;
    $this->jamKeluarNama = $jamKeluarNama;
    $this->jamKembaliNama = $jamKembaliNama;
    $this->guruNama = $guruNama;
    $this->guruPiketNama = $guruPiketNama;
}

    public function build()
    {
        return $this->subject('Status Dispensasi Anda')
                    ->view('emails.dispen_status')
                    ->with([
    'data' => $this->data,
    'detail' => $this->detail,
    'status_guru_pengajar' => $this->status_guru_pengajar,
    'status_guru_piket' => $this->status_guru_piket,
    'kelasNama' => $this->kelasNama,
    'jamKeluarNama' => $this->jamKeluarNama,
    'jamKembaliNama' => $this->jamKembaliNama,
    'guruNama' => $this->guruNama,
    'guruPiketNama' => $this->guruPiketNama
                        ]);
    }
}