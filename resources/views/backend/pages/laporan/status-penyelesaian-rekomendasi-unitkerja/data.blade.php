<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/status-penyelesaian-rekomendasi-unitkerja-pdf')}}" method="post" id="cetakpdf" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="unit_kerja1" value="{{implode(',', $request->unit_kerja1)}}">
                <input type="hidden" name="no_lhp" value="{{implode(',', $no_lhp)}}">
                <input type="hidden" name="level_resiko" value="{{implode(',', $request->level_resiko)}}">
                <input type="hidden" name="overdue" value="{{$request->overdue}}">
                <input type="hidden" name="bidang" value="{{implode(',', $request->bidang)}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="tampilkannilai" value="{{$request->tampilkannilai}}">
                <input type="hidden" name="tampilkanwaktupenyelesaian" value="{{$request->tampilkanwaktupenyelesaian}}">
                <input type="hidden" name="export" value="pdf">
                <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Cetak Data</button>
            </form>
        </div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/status-penyelesaian-rekomendasi-unitkerja-pdf')}}" method="post" id="cetakxls" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="unit_kerja1" value="{{implode(',', $request->unit_kerja1)}}">
                <input type="hidden" name="no_lhp" value="{{implode(',', $no_lhp)}}">
                <input type="hidden" name="level_resiko" value="{{implode(',', $request->level_resiko)}}">
                <input type="hidden" name="overdue" value="{{$request->overdue}}">
                <input type="hidden" name="bidang" value="{{implode(',', $request->bidang)}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="tampilkannilai" value="{{$request->tampilkannilai}}">
                <input type="hidden" name="tampilkanwaktupenyelesaian" value="{{$request->tampilkanwaktupenyelesaian}}">
                <input type="hidden" name="export" value="xls">
                <button class="btn btn-xs btn-success"> <i class="fa fa-file-excel-o"></i> Export Ke Excel</button>
            </form>
        </div>
    </div>
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-12 text-center">
            <h5>
                Laporan Status Penyelesaian Rekomendasi - Bidang<br>
                PEMERIKSA <span style="font-weight: bold;" id="span_pemeriksa">{{$pemeriksaTitle}}</span><br>
                PERIODE <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
            </h5>
        </div>
    </div>
    <hr>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
</div>
<script>
    $('#table').DataTable();
    $('[data-toggle="tooltip"]').tooltip();
</script>