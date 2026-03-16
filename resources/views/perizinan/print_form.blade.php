@include('layout.header')
@include('layout.sidebar')

<div class="main-content dispen-detail-page">

 <!-- HEADER -->
     <div class="page-title">
        <i class="fas fa-print"></i>
        <h1>Print Data Perizinan</h1>
    </div>

<p class="page-desc">
Anda dapat melihat data Print Data Perizinan di bawah
</p>

<div class="print-card">

<div class="print-card-header">
Form Laporan Perizinan Windows Print
</div>

<div class="print-card-body">

<form action="{{ route('perizinan.printData') }}" method="GET" target="_blank">

<div class="form-group">
<label>Semester :</label>
<select name="semester" required class="form-control">
<option value="">- Pilih -</option>
<option value="1">Semester 1</option>
<option value="2">Semester 2</option>
</select>
</div>

<div class="form-group">
<label>Tahun :</label>

<select name="tahun" required class="form-control">

<option value="">- Pilih -</option>

@for($i = date('Y')-2; $i <= date('Y')+1; $i++)
<option value="{{ $i }}">{{ $i }}</option>
@endfor

</select>

</div>

<div class="form-group">
<label>Rombel :</label>

<select name="kelas" required class="form-control">

<option value="">- Pilih -</option>

@foreach($kelas as $k)
<option value="{{ $k->kelas }}">
{{ $k->kelas }}
</option>
@endforeach

</select>

</div>

<div class="print-button-area">
<button type="submit" class="print-button">
🖨 Print
</button>
</div>

</form>

</div>
</div>

</div>

@include('layout.footer')