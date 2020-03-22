<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/status-penyelesaian-rekomendasi-pemeriksa-pdf')}}" method="post" id="cetakpdf" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{$request->pemeriksa}}">
                <input type="hidden" name="no_lhp" value="{{$no_lhp}}">
                <input type="hidden" name="level_resiko" value="{{$request->level_resiko}}">
                <input type="hidden" name="bidang" value="{{$dbidang}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="unitkerja1" value="{{$unitkerja1}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="tampilkannilai" value="{{$request->tampilkannilai}}">
                <input type="hidden" name="tampilkanwaktupenyelesaian" value="{{$request->tampilkanwaktupenyelesaian}}">
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
                LAPORAN STATUS PENYELESIAN REKOMENDASI<br>
                PEMERIKSA <span style="font-weight: bold;text-decoration:underline" id="span_pemeriksa">{{strtoupper($npemeriksa ? $npemeriksa->pemeriksa : '')}}</span><br>
                PERIODE <span style="font-weight: bold;text-decoration:underline" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;text-decoration:underline" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
            </h5>
        </div>
    </div>
    <hr>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Nomor LHP</th>
                <th class="text-center">Pemeriksa</th>
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
            @foreach ($lhp as $k=> $item)
                
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-left">{{$item->no_lhp}}</td>
                    <td class="text-center">{{$item->code}}</td>
                    @php
                        $jlh=$selesai=0;
                    @endphp
                    @foreach ($statusrekom as $itm)
                        @if (isset($jlh_by_status[$k][$itm->id]))
                            <td class="text-center">{{count($jlh_by_status[$k][$itm->id])}}</td>
                            @php
                                if($itm->id==1)
                                    $selesai=count($jlh_by_status[$k][$itm->id]);

                                $jlh+=count($jlh_by_status[$k][$itm->id]);
                            @endphp
                        @else   
                            <td class="text-center">0</td>
                        @endif
                    @endforeach
                    <td class="text-center">{{rupiah($jlh)}}</td>

                    @php
                        if($selesai!=0)
                        {
                            $persen = ($selesai / $jlh * 100);
                        }
                        else
                            $persen=0;
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