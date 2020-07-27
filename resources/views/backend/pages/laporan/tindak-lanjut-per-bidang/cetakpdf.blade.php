<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Laporan Temuan Unit Kerja</title>
</head>
<body style="padding:0px; margin:0px;">
    <div class="row" style="padding:0px; margin:0px;">
            <div class="col-md-12 text-center" style="text-align:center">
                <h5>
                @php
                $pemeriksaTitle='';
                if($request->pemeriksa == 0){
                    $pemeriksaTitle.='Semua';
                }else{
                    foreach($npemeriksa as $k=>$v){
                        $pemeriksaTitle.=$v->pemeriksa.' ';
                    }
                }
                @endphp
                LAPORAN PEMANTAUAN TINDAK LANJUT PER BIDANG <br>
                PEMERIKSA: <span style="font-weight: bold;" id="span_pemeriksa">{{$pemeriksaTitle}}</span><br>
                BIDANG: <span style="font-weight: bold;" id="span_unitkerja">{{$bidangTitle}}</span><br>
                PERIODE: <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
                </h5>
            </div>
        </div>
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" border="1">
        <thead>
            <tr class="primary">
				<th class="text-center" style="width:15px;" rowspan="2">#</th>
                <th class="text-center" colspan="2">LHP</th>
                <th class="text-center" colspan="3">Temuan Pemeriksa</th>
                <th class="text-center" colspan="4">Rekomendasi</th>
                <th class="text-center" colspan="4">Tindak Lanjut</th>
                <th class="text-center" rowspan="2">Waktu Penyelesaian</th>
				<th class="text-center" rowspan="2">Overdue</th>
            </tr>
            <tr class="primary">
                <th class="text-center">No. LHP</th>
                <th class="text-center">Judul LHP</th>
                <th class="text-center">Temuan</th>
                <th class="text-center">Nilai Temuan</th>
                <!-- <th class="text-center">PIC Temuan</th> -->
                <th class="text-center">Level Resiko</th>
                <th class="text-center">Nilai<br> Rekomendasi</th>
                <th class="text-center">Saran dan<br>Rekomendasi</th>
                <th class="text-center">No.<br>Rekomendasi</th>
                <th class="text-center">Status<br>Rekomendasi</th>
                <th class="text-center">Tindak Lanjut</th>
                <th class="text-center">Nilai<br>Tindak Lanjut</th>
                <th class="text-center">Dokumen<br>Pendukung</th>
                <th class="text-center">PIC</th>
            </tr>
        </thead>
        @php
        $totalTemuan=$totalRekomendasi=$totalTdklanjut=0;
        @endphp
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($rekomendasi as $k=> $item)
                @php
                    $dtindaklanjut=$ntindaklanjut=$doktindaklanjut=$pictindaklanjut='';
                    if(isset($tindaklanjut[$item->id_rekom]))
                    {
                        foreach ($tindaklanjut[$item->id_rekom] as $key => $value) {
                            $dtindaklanjut.='<li>'.$value->tindak_lanjut.'</li>';
                        }
                    }
                    $totalTemuan += $item->nominal;
                    $totalRekomendasi += $item->nilai_rekomendasi;
                    $totalTdklanjut += $ntindaklanjut;
                @endphp
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-left">{{$item->no_lhp}}</td>
                    <td class="text-left">{{$item->judul_lhp}}</td>
                    <td class="text-left">{{$item->temuan}}</td>
                    <td class="text-right">{{rupiah($item->nominal)}}</td>
                    
                    <td class="text-center">{{$item->level_resiko}}</td>
                    <td class="text-right">{{rupiah($item->nilai_rekomendasi)}}</td>
                    <td class="text-left">{{$item->rekom}}</td>
                    <td class="text-right">{{rupiah($item->nilai_rekomendasi)}}</td>
                    <td class="text-center">{{$item->st_rekom}}</td>
                    <td class="text-left"><ul>{!!$dtindaklanjut!!}</ul></td>
                    <td class="text-right"><ul>{!!$ntindaklanjut!!}</ul></td>
                    <td class="text-center"><ul>{!!$doktindaklanjut!!}</ul></td>
                    {{--<td class="text-left"><ul>{!!$pictindaklanjut!!}</ul></td>--}}
                    <td class="text-center">{{isset($pic_unit[$item->pic_temuan_id]) ? $pic_unit[$item->pic_temuan_id]->nama_pic : '-'}}</td>
                   
                    @if ($item->tanggal_penyelesaian=='')
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                    @else
                        <td class="text-center">{{tgl_indo($item->tanggal_penyelesaian)}}</td>
                        @php
                            $over=selisihhari($item->tanggal_penyelesaian,date('Y-m-d'),0);
                        @endphp
                        <td class="text-center">{{$over}} Hari</td>
                    @endif

                </tr> 
               
                @php
                    $no++;
                @endphp
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th colspan="3" style="text-align:left">Total:</th>
                <th></th>
                <th>{{rupiah($totalTemuan)}}</th>
                <th></th>
                <th>{{rupiah($totalRekomendasi)}}</th>
                <th colspan="4" style="text-align:left"></th>
                <th>{{rupiah($totalTdklanjut)}}</th>
                <th colspan="4" style="text-align:left"></th>
            </tr>
        </tfoot>

        </table>
        <style>
            th,td
            {
                font-size:10px;
                padding:2px;
            }
            td{
                vertical-align: top !important;
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