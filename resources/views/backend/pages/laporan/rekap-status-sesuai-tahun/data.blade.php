<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/rekap-status-sesuai-tahun-pdf')}}" method="post" id="cetakpdf" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="unit_kerja1" value="{{implode(',', $request->unit_kerja1)}}">
                <input type="hidden" name="lhp_from_year" value="{{$request->lhp_from_year}}">
                <input type="hidden" name="lhp_to_year" value="{{$request->lhp_to_year}}">
                <input type="hidden" name="bidang" value="{{implode(',', $request->bidang)}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="export" value="pdf">
                <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Cetak Data</button>
            </form>
        </div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/rekap-status-sesuai-tahun-pdf')}}" method="post" id="cetakxls" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="unit_kerja1" value="{{implode(',', $request->unit_kerja1)}}">
                <input type="hidden" name="lhp_from_year" value="{{$request->lhp_from_year}}">
                <input type="hidden" name="lhp_to_year" value="{{$request->lhp_to_year}}">
                <input type="hidden" name="bidang" value="{{implode(',', $request->bidang)}}">
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
                REKAPITULASI STATUS REKOMENDASI<br>
                TAHUN: <span style="font-weight: bold;" id="span_from_year">@if($request->lhp_from_year == 0) Semua @else $request->lhp_from_year @endif</span> - <span style="font-weight: bold;" id="span_to_year">@if($request->lhp_to_year == 0) Semua @else $request->lhp_to_year @endif</span><br>
                UNTUK PERIODE PENYELESAIAN TANGGAL: <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
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