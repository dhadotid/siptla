<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/laporan-jenis-temuan-pdf')}}" method="post" id="cetakpdf" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="level_resiko" value="{{implode(',', $request->level_resiko)}}">
                <input type="hidden" name="jenis_temuan" value="{{implode(',', $request->jenis_temuan)}}">
                <input type="hidden" name="kode_lhp" value="{{implode(',', $kode_lhp)}}">
                <input type="hidden" name="no_lhp" value="{{implode(',', $no_lhp)}}">
                <input type="hidden" name="unit_kerja1" value="{{implode(',', $request->unit_kerja1)}}">
                <input type="hidden" name="status_rekomendasi" value="{{implode(',', $request->status_rekomendasi)}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="export" value="pdf">
                <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Cetak Data</button>
            </form>
        </div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/laporan-jenis-temuan-pdf')}}" method="post" id="cetakxls" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="level_resiko" value="{{implode(',', $request->level_resiko)}}">
                <input type="hidden" name="jenis_temuan" value="{{implode(',', $request->jenis_temuan)}}">
                <input type="hidden" name="kode_lhp" value="{{implode(',', $kode_lhp)}}">
                <input type="hidden" name="no_lhp" value="{{implode(',', $no_lhp)}}">
                <input type="hidden" name="unit_kerja1" value="{{implode(',', $request->unit_kerja1)}}">
                <input type="hidden" name="status_rekomendasi" value="{{implode(',', $request->status_rekomendasi)}}">
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
                Laporan Jenis Temuan<br>
                Periode: <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
            </h5>
        </div>
    </div>
    <hr>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
</div>
<script>
    $('#table').DataTable();
    $('[data-toggle="tooltip"]').tooltip();
</script>