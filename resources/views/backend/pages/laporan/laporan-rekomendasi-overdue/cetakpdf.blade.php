<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Laporan Temuan Unit Kerja</title>
</head>
<body style="padding:0px; margin:0px;">
    <div class="row" style="padding:0px; margin:0px;">
            <div class="col-md-12 text-center" style="text-align:center">
                <h5>
                    LAPORAN REKOMENDASI OVERDUE<br>
                    STATUS REKOMENDASI : BELUM SELESAI DAN BELUM DITINDAK LANJUTI<br>
                    PERIODE <span style="font-weight: bold;text-decoration:underline" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;text-decoration:underline" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span>
                </h5>
            </div>
        </div>
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" border="1">
            <thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Risiko</th>
                <th class="text-center">Belum Lewat Waktu</th>
                <th class="text-center">1 Minggu - 1 Bulan</th>
                <th class="text-center">2 - 3 Bulan</th>
                <th class="text-center">4 - 6 Bulan</th>
                <th class="text-center">6 - 12 Bulan</th>
                <th class="text-center">% Overdue</th>
                <th class="text-center">Jumlah</th>
            </tr>
            
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($lhp as $k=> $item)
                
                <tr>
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