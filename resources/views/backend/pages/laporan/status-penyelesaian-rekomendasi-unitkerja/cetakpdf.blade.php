<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Laporan Temuan Unit Kerja</title>
</head>
<body style="padding:0px; margin:0px;">
    <div class="row" style="padding:0px; margin:0px;">
            <div class="col-md-12 text-center" style="text-align:center">
            <h5>
                Laporan Status Penyelesaian Rekomendasi - Bidang<br>
                PEMERIKSA <span style="font-weight: bold;" id="span_pemeriksa">{{$pemeriksaTitle}}</span><br>
                PERIODE <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
            </h5>
            </div>
        </div>
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" border="1">
        <thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Unit Kerja</th>
                @foreach ($statusrekom as $item)
                    <th class="text-center">{{$item->rekomendasi}}</th>
                @endforeach
                <th class="text-center">Jumlah</th>
                <th class="text-center">Selesai</th>
			</tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach($finalData as $k=>$v)
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$v['name']}}</td>
                    @foreach($statusrekom as $id=>$itm)
                    <td class="text-center">{{$v[$id]}}</td>
                    @endforeach
                    <td class="text-center">{{$v['jumlah']}}</td>
                    @php
                    if($v['selesai'] != 0){
                        $persen = ($v['selesai'] + $v['tdd']) / $v['jumlah'] * 100;
                    }else{
                        $persen = 0;
                    }
                    @endphp
                    <td class="text-center">{{number_format($persen,2,',','.')}} %</td>
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