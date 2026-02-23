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
public $guruNama;
public $guruPiketNama;

public function __construct(
    $data,
    $detail,
    $status_guru_pengajar,
    $status_guru_piket,
    $kelasNama,
    $guruNama,
    $guruPiketNama
){
    $this->data = $data;
    $this->detail = $detail;
    $this->status_guru_pengajar = $status_guru_pengajar;
    $this->status_guru_piket = $status_guru_piket;
    $this->kelasNama = $kelasNama;
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
    'guruNama' => $this->guruNama,
    'guruPiketNama' => $this->guruPiketNama
                        ]);
    }
}