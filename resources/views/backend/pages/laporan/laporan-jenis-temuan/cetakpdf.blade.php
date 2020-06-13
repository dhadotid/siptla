<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Laporan Temuan Unit Kerja</title>
</head>
<body style="padding:0px; margin:0px;">
    <div class="row" style="padding:0px; margin:0px;">
            <div class="col-md-12 text-center" style="text-align:center">
            <h5>
                Laporan Jenis Temuan<br>
                Periode: <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
            </h5>
            </div>
        </div>
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" border="1">
        <thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Risiko</th>
                <th class="text-center">Pemeriksa</th>
                <th class="text-center">Jenis Temuan</th>
                <th class="text-center">Kode LHP</th>
                <th class="text-center">No. LHP</th>
                <th class="text-center">Unit Kerja (PIC Temuan)</th>
                <th class="text-center">No. Temuan</th>
                <th class="text-center">Temuan</th>
                <th class="text-center">No. Rekomendasi</th>
                <th class="text-center">Rekomendasi</th>
                <th class="text-center">Status Rekomendasi</th>
			</tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach($alldata as $k=>$v)
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$v->level_resiko}}</td>
                    <td class="text-center">{{$v->pemeriksa}}</td>
                    <td class="text-center">{{$v->jenis_temuan}}</td>
                    <td class="text-center">{{$v->kode_lhp}}</td>
                    <td class="text-center">{{$v->no_lhp}}</td>
                    <td class="text-center">{{$v->nama_pic}}</td>
                    <td class="text-center">{{$v->no_temuan}}</td>
                    <td class="text-center">{{$v->temuan}}</td>
                    <td class="text-center">{{$v->nomor_rekomendasi}}</td>
                    <td class="text-center">{{$v->rekomendasi}}</td>
                    <td class="text-center">{{$v->status_rekom}}</td>
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