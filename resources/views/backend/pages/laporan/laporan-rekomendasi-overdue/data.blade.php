<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/laporan-rekomendasi-overdue-pdf')}}" method="post" id="cetakpdf" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{$request->pemeriksa}}">
                <input type="hidden" name="no_lhp" value="{{$no_lhp}}">
                <input type="hidden" name="bidang" value="{{$dbidang}}">
                <input type="hidden" name="unitkerja" value="{{$unitkerja}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="pejabat" value="{{$request->pejabat}}">
                <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Cetak Data</button>
            </form>
        </div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/temuan-per-unitkerja-xls')}}" method="post" id="cetakxls" target="_blank">
                @csrf
                <button class="btn btn-xs btn-success" onclick="xls()"> <i class="fa fa-file-excel-o"></i> Export Ke Excel</button>
            </form>
        </div>
    </div>
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-12 text-center">
            <h5>
                LAPORAN REKOMENDASI OVERDUE<br>
                STATUS REKOMENDASI : BELUM SELESAI DAN BELUM DITINDAK LANJUTI<br>
                PERIODE <span style="font-weight: bold;text-decoration:underline" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;text-decoration:underline" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
            </h5>
        </div>
    </div>
    <hr>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
</div>
<script>
    $('#table').DataTable();
    $('[data-toggle="tooltip"]').tooltip();
</script>