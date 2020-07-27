<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/temuan-per-bidang-pdf')}}" method="post" id="cetakpdf" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="no_lhp" value="{{implode(',', $no_lhp)}}">
                <input type="hidden" name="level_resiko" value="{{implode(',', $request->level_resiko)}}">
                <input type="hidden" name="bidang" value="{{implode(',', $request->bidang)}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="tampilkannilai" value="{{$request->tampilkannilai}}">
                <input type="hidden" name="tampilkanwaktupenyelesaian" value="{{$request->tampilkanwaktupenyelesaian}}">
                <input type="hidden" name="pejabat" value="{{$request->pejabat}}">
                <input type="hidden" name="export" value="pdf">
                <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Cetak Data</button>
            </form>
        </div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/temuan-per-bidang-pdf')}}" method="post" id="cetakxls" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="no_lhp" value="{{implode(',', $no_lhp)}}">
                <input type="hidden" name="level_resiko" value="{{implode(',', $request->level_resiko)}}">
                <input type="hidden" name="bidang" value="{{implode(',', $request->bidang)}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="tampilkannilai" value="{{$request->tampilkannilai}}">
                <input type="hidden" name="tampilkanwaktupenyelesaian" value="{{$request->tampilkanwaktupenyelesaian}}">
                <input type="hidden" name="pejabat" value="{{$request->pejabat}}">
                <input type="hidden" name="export" value="xls">
                <button class="btn btn-xs btn-success"> <i class="fa fa-file-excel-o"></i> Export Ke Excel</button>
            </form>
        </div>
    </div>
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-12 text-center">
            <h5>
                REKAPITULASI PEMERIKSAAN - BIDANG {{--<span style="font-weight: bold;" id="span_pemeriksa">{{strtoupper($npemeriksa ? $npemeriksa->pemeriksa : '')}}</span>--}}<br>
                PERIODE: <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
                {{--<span style="font-weight: bold;" id="span_judul_lhp">{{isset($lhp[$no_lhp]) ? $lhp[$no_lhp]->judul_lhp : 'JUDUL LHP BERDASARKAN NO LHP YANG DIPILIH'}}</span><br>--}}
                BIDANG: <span style="font-weight: bold;" id="span_bidang">{{$bidangTitle}}</span>
            </h5>
        </div>
    </div>
    <hr>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Pemeriksa</th>
                <th class="text-center">Nomor LHP</th>
                <th class="text-center">Temuan</th>
                @if ($tampilkannilai==1)
				    <th class="text-center">Nilai Temuan</th>
                @endif
				<th class="text-center">Level Resiko</th>
				<th class="text-center">Saran dan Rekomendasi</th>
                <th class="text-center">Nilai Rekomendasi</th>
                @if ($tampilkanwaktupenyelesaian==1)
				    <th class="text-center">Waktu Penyelesaian</th>
                @endif
			</tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($rekomendasi as $k=> $item)
                
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$item->code}}</td>
                    <td class="text-left">{{$item->no_lhp}}</td>
                    <td class="text-left">{{$item->temuan}}</td>
                    @if ($tampilkannilai==1)
                        <td class="text-right">{{rupiah($item->nominal)}}</td>
                    @endif
                    <td class="text-center">{{$item->level_resiko}}</td>
                    <td class="text-left">{{$item->rekomendasi}}</td>
                    <td class="text-right">{{rupiah($item->nominal_rekomendasi)}}</td>
                    @if ($tampilkanwaktupenyelesaian==1)
                        <td class="text-center">{{tgl_indo($item->tanggal_penyelesaian)}}</td>
                    @endif
                </tr> 
                {{-- @php
                    $dtemuan=$nilaitemuan=$levelresiko='';
                    if(isset($temuan[$k])){
                        $j=0;
                        foreach($temuan[$k] as $idx_temuan=>$val)
                        {
                            $dtemuan.='<li class="'.($j%2==0 ? 'text-info' : '').'" data-toggle="tooltip" title="'.$val->temuan.'" style="height:45px;">'.substr($val->temuan,0,70).' ...</li>';
                            $nilaitemuan.='<li class="'.($j%2==0 ? 'text-info' : '').'" style="height:45px;">'.rupiah($val->nominal).'</li>';
                            $levelresiko.='<li class="'.($j%2==0 ? 'text-info' : '').'" style="height:45px;">'.$val->level_resiko.'</li>';



                            $j++;
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$item->code}}</td>
                    <td class="text-center">{{$item->no_lhp}}</td>
                    <td class="text-left"><ul>{!!$dtemuan!!}</ul></td>
                    @if ($tampilkannilai==1)
                        <td class="text-right"><ul>{!!$nilaitemuan!!}</ul></td>
                    @endif
                    <td class="text-center"><ul>{!!$levelresiko!!}</ul></td>
                    <td class="text-center">Saran dan Rekomendasi</td>
                    <td class="text-center">Nilai Rekomendasi</td>
                    @if ($tampilkanwaktupenyelesaian==1)
                        <td class="text-center"></td>
                    @endif
                </tr> --}}
                @php
                    $no++;
                @endphp
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th colspan="4" style="text-align:left">Total:</th>
                @if ($tampilkannilai==1)
                <th></th>
                @endif
                <th colspan="2" style="text-align:left"></th>
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
            var columnRekom = 7;
            if( {!! $tampilkannilai !!} == 1 ){
                totalTemuan = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                pageTotalTemuan = api
                    .column( 4, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 4 ).footer() ).html(
                    formatRupiah(pageTotalTemuan, 'Rp.') +' (Total: '+ formatRupiah(totalTemuan, 'Rp.') +')'
                );
            }else {
                columnRekom = 6;
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