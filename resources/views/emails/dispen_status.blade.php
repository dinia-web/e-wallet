<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Status Dispensasi</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f9; font-family:Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
<tr>
<td align="center">

<table width="600" cellpadding="0" cellspacing="0" 
       style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.08);">

    <!-- BODY -->
<tr>
<td style="padding:30px; color:#333; font-size:14px;">

    @php
        $pengaju = $detail->first();
    @endphp

    <p>Halo <strong>{{ $pengaju->nama ?? '-' }}</strong>,</p>
    <p>
        Permohonan dispensasi Anda telah diproses dengan hasil:
        <strong style="color:
        {{ $data->status == 'disetujui' ? '#27ae60' : '#e74c3c' }};">
        {{ strtoupper($data->status) }}
        </strong>
    </p>

    <hr style="border:none; border-top:1px solid #eee; margin:20px 0;">

    <!-- 🔥 DATA SISWA -->
    <h4 style="margin-bottom:10px;">Data Murid</h4>

    <table width="100%" cellpadding="8" cellspacing="0" 
           style="border-collapse:collapse; font-size:13px;">
             <!-- HEADER -->
    <tr style="background:#f3f4f6;">
        <th align="left">Nama</th>
        <th align="left">NIS</th>
        <th align="left">Kelas</th>
        <th align="left">Keperluan</th>
        <th align="left">Keterangan</th>
    </tr>
        @foreach($detail as $i => $d)
        <tr style="{{ $i == 0 ? 'background:#ecfdf5;' : '' }}">
            <td>
                {{ $i == 0 ? '' : '' }}
                {{ $d->nama }}
            </td>
            <td>{{ $d->nis }}</td>
            <td>{{ $d->siswa->kelas ?? '-' }}</td>

            @if($i == 0)
                <td>{{ $data->alasan }}</td>
                <td style="color:#22c55e;"><strong>Pengaju</strong></td>
            @else
                <td>{{ $data->alasan }}</td>
                <td style="color:#3b82f6;">Tambahan</td>
            @endif
        </tr>
        @endforeach

    </table>

    <hr style="border:none; border-top:1px solid #eee; margin:20px 0;">

    <!-- STATUS MENINDAKLANJUTI -->
    <h4>Yang Menindaklanjuti</h4>

    <table width="100%" cellpadding="8" cellspacing="0" style="font-size:14px; border-collapse:collapse;">
    <tr>

        <!-- GURU PENGAJAR -->
        <td valign="top" width="50%" style="padding-right:10px;">
            <strong>Guru Pengajar</strong>
            <br><br>

            @if(!empty($status_guru_pengajar))
                @foreach($status_guru_pengajar as $item)
                    <div style="margin-bottom:8px;">
                        <span style="color:
                            {{ $item['type'] == 'approved' ? '#16a34a' :
                            ($item['type'] == 'rejected' ? '#dc2626' : '#f59e0b') }}; font-weight:bold;">
                            {{ $item['text'] }}
                        </span>
                    </div>
                @endforeach
            @else
                <div style="color:#999;">Belum ada aksi</div>
            @endif
        </td>

        <!-- GURU PIKET -->
        <td valign="top" width="50%" style="padding-left:10px;">
            <strong>Guru Piket</strong>
            <br><br>

            @if(!empty($status_guru_piket))
                @foreach($status_guru_piket as $item)
                    <div style="margin-bottom:8px;">
                        <span style="color:
                            {{ $item['type'] == 'approved' ? '#16a34a' :
                            ($item['type'] == 'rejected' ? '#dc2626' : '#f59e0b') }}; font-weight:bold;">
                            {{ $item['text'] }}
                        </span>
                    </div>
                @endforeach
            @else
                <div style="color:#999;">Belum ada aksi</div>
            @endif
        </td>

    </tr>
    </table>
    <br>
    <p style="font-size:12px; color:#777;">
        Mohon simpan email ini sebagai bukti resmi.
    </p>

</td>
</tr>
</table>

</td>
</tr>
</table>
</body>
</html>
