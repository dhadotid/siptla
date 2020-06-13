<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/laporan-jenis-audit-pdf')}}" method="post" id="cetakpdf" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="jenis_audit" value="{{implode(',', $request->jenis_audit)}}">
                <input type="hidden" name="kode_lhp" value="{{implode(',', $kode_lhp)}}">
                <input type="hidden" name="no_lhp" value="{{implode(',', $no_lhp)}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="export" value="pdf">
                <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Cetak Data</button>
            </form>
        </div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/laporan-jenis-audit-pdf')}}" method="post" id="cetakxls" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="jenis_audit" value="{{implode(',', $request->jenis_audit)}}">
                <input type="hidden" name="kode_lhp" value="{{implode(',', $kode_lhp)}}">
                <input type="hidden" name="no_lhp" value="{{implode(',', $no_lhp)}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="export" value="xls">
                <button class="btn btn-xs btn-success"> <i class="fa fa-file-excel-o"></i> Export Ke Excel</button>
            </form>
        </div>
    </div>
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-12 text-center">
            <h5>
                Laporan Jenis Audit<br>
                Periode: <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
            </h5>
        </div>
    </div>
    <hr>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Pemeriksa</th>
                <th class="text-center">Jenis Audit</th>
                <th class="text-center">Kode LHP</th>
                <th class="text-center">No. LHP</th>
                <th class="text-center">Jumlah Temuan</th>
                <th class="text-center">Jumlah Rekomendasi</th>
			</tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach($finalData as $k=>$v)
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$v['pemeriksa']}}</td>
                    <td class="text-center">{{$v['jenis_audit']}}</td>
                    <td class="text-center">{{$v['kode_lhp']}}</td>
                    <td class="text-center">{{$v['no_lhp']}}</td>
                    <td class="text-center">{{$v['jumlah_temuan']}}</td>
                    <td class="text-center">{{$v['jumlah_rekomendasi']}}</td>
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