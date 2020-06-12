<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Laporan Temuan Unit Kerja</title>
</head>
<body style="padding:0px; margin:0px;">
    <div class="row" style="padding:0px; margin:0px;">
            <div class="col-md-12 text-center" style="text-align:center">
            <h5>
                LAPORAN REKOMENDASI OVERDUE - UNIT KERJA<br>
                STATUS REKOMENDASI : BELUM SELESAI DAN BELUM DITINDAK LANJUTI<br>
                PERIODE <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
            </h5>
            </div>
        </div>
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" border="1">
        <thead>
			<tr class="primary">
				<th class="text-center" rowspan="2" style="width:15px;">#</th>
                <th class="text-center" rowspan="2">Bidang</th>
                <th class="text-center" rowspan="2">Unit Kerja</th>
                <th class="text-center" colspan="4">Belum Lewat Waktu</th>
                <th class="text-center" colspan="4">Overdue</th>
                <th class="text-center" rowspan="2">% Overdue</th>
                <th class="text-center" rowspan="2">Jumlah</th>
            </tr>
            <tr class="primary">
                <th class="text-center">High</th>
                <th class="text-center">Medium</th>
                <th class="text-center">Low</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">High</th>
                <th class="text-center">Medium</th>
                <th class="text-center">Low</th>
                <th class="text-center">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($rekomendasi as $k=> $item)
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-left">
                        @if (isset($dp[$item->pic_1_temuan_id]))
                            @if (isset($nbid[$dp[$item->pic_1_temuan_id]->bidang]))
                                {{$nbid[$dp[$item->pic_1_temuan_id]->bidang]->nama_bidang}}
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-left">
                        @if (isset($dp[$item->pic_1_temuan_id]))
                            {{$dp[$item->pic_1_temuan_id]->nama_pic}}
                        @else
                            -
                        @endif    
                    </td>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$no}}</td>
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