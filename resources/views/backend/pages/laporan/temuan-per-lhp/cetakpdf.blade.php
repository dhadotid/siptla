<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Laporan Temuan Unit Kerja</title>
</head>
<body style="padding:0px; margin:0px;">
    <div class="row" style="padding:0px; margin:0px;">
            <div class="col-md-12 text-center" style="text-align:center">
                <h5>
                    REKIPUTLASI PEMERIKSAAN LHP<br>
                    PERIODE <span style="font-weight: bold;text-decoration:underline" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;text-decoration:underline" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
                    <span style="font-weight: bold;text-decoration:underline" id="span_judul_lhp">{{isset($lhp[$no_lhp]) ? $lhp[$no_lhp]->judul_lhp : 'JUDUL LHP BERDASARKAN NO LHP YANG DIPILIH'}}</span><br>
                    NO. LHP <span style="font-weight: bold;text-decoration:underline" id="span_unitkerja">{{isset($lhp[$no_lhp]) ? $lhp[$no_lhp]->no_lhp : 'NO. LHP'}}</span>
                    &nbsp;
                    TANGGAL LHP <span style="font-weight: bold;text-decoration:underline" id="span_unitkerja">{{tgl_indo($request->tgl_awal)}} s.d. {{tgl_indo($request->tgl_akhir)}}</span>
                </h5>
            </div>
        </div>
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" border="1">
            <thead>
                <tr class="primary">
                    <th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Temuan</th>
                @if ($tampilkannilai==1)
				    <th class="text-center">Nilai Temuan</th>
                @endif
				<th class="text-center">Level Resiko</th>
				<th class="text-center">Saran dan Rekomendasi</th>
                <th class="text-center">Nilai Rekomendasi</th>
                <th class="text-center">Unit Kerja 1</th>
                <th class="text-center">Unit Kerja 2</th>
                @if ($tampilkanwaktupenyelesaian==1)
				    <th class="text-center">Waktu Penyelesaian</th>
                @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $no=1;
                @endphp
                @foreach ($rekomendasi as $k=> $item)
                
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-left">{{$item->temuan}}</td>
                    @if ($tampilkannilai==1)
                        <td class="text-right">{{rupiah($item->nominal)}}</td>
                    @endif
                    <td class="text-center">{{$item->level_resiko}}</td>
                    <td class="text-left">{{$item->rekomendasi}}</td>
                    <td class="text-right">{{rupiah($item->nilai_rekomendasi)}}</td>
                    <td class="text-right">{{($item->nilai_rekomendasi)}}</td>
                    <td class="text-right">{{($item->nilai_rekomendasi)}}</td>
                    @if ($tampilkanwaktupenyelesaian==1)
                        @if ($item->tanggal_penyelesaian!='')
                            <td class="text-center">{{tgl_indo($item->tanggal_penyelesaian)}}</td>
                        @else
                            <td class="text-center">-</td>
                        @endif
                    @endif
                </tr> 
               
                @php
                    $no++;
                @endphp
            @endforeach
            </tbody>
        </table>
        <style>
            th,td
            {
                font-size:11px;
                padding:2px;
            }
            .text-right{
                text-align:right;
            }
            .text-center{
                text-align:center;
            }
            .text-left{
                text-align:left;
            }
        </style>
</body>