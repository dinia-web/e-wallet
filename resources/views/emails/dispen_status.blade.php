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

    <p>Halo <strong>{{ $data->nama }}</strong>,</p>

    <p>
        Permohonan dispensasi Anda telah diproses dengan hasil:
        <strong style="color:
        {{ $data->status == 'disetujui' ? '#27ae60' : '#e74c3c' }};">
        {{ strtoupper($data->status) }}
        </strong>
    </p>

    <hr style="border:none; border-top:1px solid #eee; margin:20px 0;">

    <!-- 🔥 DATA SISWA -->
    <h4 style="margin-bottom:10px;">Data Siswa</h4>

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
        <!-- SISWA UTAMA -->
        <tr style="background:#ecfdf5;">
            <td><strong>{{ $data->nama }}</strong></td>
            <td>{{ $data->nis }}</td>
            <td>{{ $kelasNama }}</td>
            <td>{{ $data->alasan }}</td>
            <td style="color:#22c55e;"><strong>Pengaju</strong></td>
        </tr>

        <!-- SISWA TAMBAHAN -->
        @foreach($detail as $d)
        <tr>
            <td>{{ $d->nama }}</td>
            <td>{{ $d->nis }}</td>
            <td>{{ $d->siswa->kelas }}</td>
            <td>{{ '-' }}</td>
            <td style="color:#3b82f6;">Tambahan</td>
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
            <ul style="padding-left:20px; margin-top:5px;">
            @if(!empty($status_guru_pengajar))
                @foreach($status_guru_pengajar as $item)
                    <li style="color:
                        {{ $item['type'] == 'approved' ? '#16a34a' :
                           ($item['type'] == 'rejected' ? '#dc2626' : '#f59e0b') }};">
                        {{ $item['text'] }}
                    </li>
                @endforeach
            @else
                <li style="color:#999;">Belum ada aksi</li>
            @endif
            </ul>
        </td>

        <!-- GURU PIKET -->
        <td valign="top" width="50%" style="padding-left:10px;">
            <strong>Guru Piket</strong>
            <ul style="padding-left:20px; margin-top:5px;">
            @if(!empty($status_guru_piket))
                @foreach($status_guru_piket as $item)
                    <li style="color:
                        {{ $item['type'] == 'approved' ? '#16a34a' :
                           ($item['type'] == 'rejected' ? '#dc2626' : '#f59e0b') }};">
                        {{ $item['text'] }}
                    </li>
                @endforeach
            @else
                <li style="color:#999;">Belum ada aksi</li>
            @endif
            </ul>
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
