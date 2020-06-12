<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Laporan Temuan Unit Kerja</title>
</head>
<body style="padding:0px; margin:0px;">
    <div class="row" style="padding:0px; margin:0px;">
            <div class="col-md-12 text-center" style="text-align:center">
            <h5>
            REKAPITULASI RISIKO TEMUAN<br>
                UNTUK PERIODE LHP: <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($request->tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($request->tgl_akhir)}}</span> <br>
            </h5>
            </div>
        </div>
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" border="1">
        <thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Bidang</th>
                <th class="text-center">Unit Kerja</th>
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
            @foreach($finalData as $k=>$v)
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$v['bidang']}}</td>
                    <td class="text-center">{{$v['nama_pic']}}</td>
                    <td class="text-center">{{$v['high']}}</td>
                    <td class="text-center">{{$v['medium']}}</td>
                    <td class="text-center">{{$v['low']}}</td>
                    <td class="text-center">{{$v['total']}}</td>
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