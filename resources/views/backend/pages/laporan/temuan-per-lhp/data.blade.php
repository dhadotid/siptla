<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/temuan-per-lhp-pdf')}}" method="post" id="cetakpdf" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="no_lhp" value="{{implode(',', $no_lhp)}}">
                <input type="hidden" name="level_resiko" value="{{implode(',', $request->level_resiko)}}">
                <input type="hidden" name="bidang" value="{{implode(',', $request->bidang)}}">
                <input type="hidden" name="unitkerja1" value="{{implode(',', $request->unitkerja1)}}">
                <input type="hidden" name="unitkerja2" value="{{implode(',', $request->unitkerja2)}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="tampilkannilai" value="{{$request->tampilkannilai}}">
                <input type="hidden" name="tampilkanwaktupenyelesaian" value="{{$request->tampilkanwaktupenyelesaian}}">
                <input type="hidden" name="export" value="pdf">
                <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Cetak Data</button>
            </form>
        </div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/temuan-per-lhp-pdf')}}" method="post" id="cetakxls" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="no_lhp" value="{{implode(',', $no_lhp)}}">
                <input type="hidden" name="level_resiko" value="{{implode(',', $request->level_resiko)}}">
                <input type="hidden" name="bidang" value="{{implode(',', $request->bidang)}}">
                <input type="hidden" name="unitkerja1" value="{{implode(',', $request->unitkerja1)}}">
                <input type="hidden" name="unitkerja2" value="{{implode(',', $request->unitkerja2)}}">
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
                REKAPITULASI PEMERIKSAAN – LHP<br>
                PERIODE: <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
                {{--<span style="font-weight: bold;" id="span_judul_lhp">{{isset($lhp[$no_lhp]) ? $lhp[$no_lhp]->judul_lhp : 'JUDUL LHP BERDASARKAN NO LHP YANG DIPILIH'}}</span><br>--}}
                NO. LHP:@foreach($lhp as $k=>$v)<span style="font-weight: bold;" id="span_no_lhp"> {{$v->no_lhp}}</span>@endforeach
                &nbsp;
                TANGGAL LHP: <span style="font-weight: bold;" id="span_tanggal_lhp">{{tgl_indo($request->tgl_awal)}} s.d. {{tgl_indo($request->tgl_akhir)}}</span>
            </h5>
        </div>
    </div>
    <hr>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Temuan</th>
                @if ($tampilkannilai==1)
				    <th class="text-center">Nilai Temuan</th>
                @endif
				<th class="text-center">Level Resiko</th>
				<th class="text-center">Saran dan Rekomendasi</th>
                <th class="text-center">Nilai Rekomendasi</th>
                <th class="text-center">Unit Kerja 1</th>
                <th class="text-center">Unit Kerja 2</th>
                @if ($tampilkanwaktupenyelesaian==1)
				    <th class="text-center">Waktu Penyelesaian</th>
                @endif
			</tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($alldata as $k=> $item)
                
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-left">{{$item->temuan}}</td>
                    @if ($tampilkannilai==1)
                        <td class="text-right">{{rupiah($item->nominal)}}</td>
                    @endif
                    <td class="text-center">{{$item->level_resiko}}</td>
                    <td class="text-left">{{$item->rekomendasi}}</td>
                    <td class="text-right">{{rupiah($item->nilai_rekomendasi)}}</td>
                    <td class="text-right">{{($item->nama_pic)}}</td>
                    <td class="text-right"></td>
                    @if ($tampilkanwaktupenyelesaian==1)
                        @if ($item->tanggal_penyelesaian=='')
                            <td class="text-center">-</td>
                        @else
                            <td class="text-center">{{tgl_indo($item->tanggal_penyelesaian)}}</td>
                        @endif
                    @endif
                </tr> 
               
                @php
                    $no++;
                @endphp
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th style="text-align:left">Total:</th>
                @if ($tampilkannilai==1)
                <th></th>
                @endif
                <th colspan="2" style="text-align:left"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                @if ($tampilkanwaktupenyelesaian==1)
                <th></th>
                @endif
            </tr>
        </tfoot>

    </table>
</div>
<script>
    $('#table').DataTable({
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\.,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            var columnRekom = 5;
            if( {!! $tampilkannilai !!} == 1 ){
                totalTemuan = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                pageTotalTemuan = api
                    .column( 2, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 2 ).footer() ).html(
                    formatRupiah(pageTotalTemuan, 'Rp.') +' (Total: '+ formatRupiah(totalTemuan, 'Rp.') +')'
                );
            }else {
                columnRekom = 4;
            }

            // Total over all pages
            totalRekomendasi = api
                .column( columnRekom )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotalRekomendasi = api
                .column( columnRekom, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            $( api.column( columnRekom ).footer() ).html(
                formatRupiah(pageTotalRekomendasi,'Rp.') +' (Total: '+ formatRupiah(totalRekomendasi,'Rp.') +')'
            );
        }
    });
    $('[data-toggle="tooltip"]').tooltip();
</script>