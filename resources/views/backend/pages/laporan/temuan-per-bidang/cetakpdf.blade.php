<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Laporan Temuan Per Bidang</title>
</head>
<body style="padding:0px; margin:0px;">
    <div class="row" style="padding:0px; margin:0px;">
            <div class="col-md-12 text-center" style="text-align:center">
                <h5>
                REKAPITULASI PEMERIKSAAN - BIDANG {{--<span style="font-weight: bold;" id="span_pemeriksa">{{strtoupper($npemeriksa ? $npemeriksa->pemeriksa : '')}}</span>--}}<br>
                Periode: <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
                {{--<span style="font-weight: bold;" id="span_judul_lhp">{{isset($lhp[$no_lhp]) ? $lhp[$no_lhp]->judul_lhp : 'JUDUL LHP BERDASARKAN NO LHP YANG DIPILIH'}}</span><br>--}}
                Bidang: <span style="font-weight: bold;" id="span_bidang">{{$bidangTitle}}</span>
                </h5>
            </div>
        </div>
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" border="1">
            <thead>
                <tr class="primary">
                    <th class="text-center" style="width:15px;">#</th>
                    <th class="text-center">Pemeriksa</th>
                    <th class="text-center">Nomor LHP</th>
                    <th class="text-center">Temuan</th>
                    @if ($tampilkannilai==1)
                        <th class="text-center">Nilai Temuan</th>
                    @endif
                    <th class="text-center">Level Resiko</th>
                    <th class="text-center">Saran dan Rekomendasi</th>
                    <th class="text-center">Nilai Rekomendasi</th>
                    @if ($tampilkanwaktupenyelesaian==1)
                        <th class="text-center">Waktu Penyelesaian</th>
                    @endif
                </tr>
            </thead>
            @php
            $totalTemuan=$totalRekomendasi=0;
            @endphp
            <tbody>
                @php
                    $no=1;
                @endphp
                @foreach ($rekomendasi as $k=> $item)
                    <tr>
                        <td class="text-center">{{$no}}</td>
                        <td class="text-center">{{$item->code}}</td>
                        <td class="text-left">{{$item->no_lhp}}</td>
                        <td class="text-left">{{$item->temuan}}</td>
                        @if ($tampilkannilai==1)
                            <td class="text-right">{{rupiah($item->nominal)}}</td>
                        @endif
                        <td class="text-center">{{$item->level_resiko}}</td>
                        <td class="text-left">{{$item->rekomendasi}}</td>
                        <td class="text-right">{{rupiah($item->nominal_rekomendasi)}}</td>
                        @if ($tampilkanwaktupenyelesaian==1)
                            @if ($item->tanggal_penyelesaian!='')
                                <td class="text-center">{{tgl_indo($item->tanggal_penyelesaian)}}</td>
                            @else
                                <td class="text-center">-</td>
                            @endif
                        @endif
                    </tr> 
                    
                    @php
                        $totalTemuan += $item->nominal;
                        $totalRekomendasi += $item->nilai_rekomendasi;
                        $no++;
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4" style="text-align:left">Total:</th>
                @if ($tampilkannilai==1)
                <th>{{rupiah($totalTemuan)}}</th>
                @endif
                <th colspan="2" style="text-align:left"></th>
                <th>{{rupiah($totalRekomendasi)}}</th>
                @if ($tampilkanwaktupenyelesaian==1)
                <th></th>
                @endif
            </tr>
        </tfoot>
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