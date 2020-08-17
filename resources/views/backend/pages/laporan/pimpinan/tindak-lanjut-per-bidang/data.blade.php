<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
    </div>
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-12 text-center">
            <h5>
            LAPORAN PEMANTAUAN TINDAK LANJUT PER BIDANG <br>
            Temaun <span style="font-weight: bold;" id="span_unitkerja">{{$bidangTitle}}</span><br>
            </h5>
        </div>
    </div>
    <hr>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" style="width:150%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;" rowspan="2">#</th>
                <th class="text-center" colspan="2">LHP</th>
                <th class="text-center" colspan="3">Temuan Pemeriksa</th>
                <th class="text-center" colspan="4">Rekomendasi</th>
                <th class="text-center" colspan="4">Tindak Lanjut</th>
                <th class="text-center" rowspan="2">Waktu Penyelesaian</th>
				<th class="text-center" rowspan="2">Overdue</th>
            </tr>
            <tr class="primary">
                <th class="text-center">No. LHP</th>
                <th class="text-center">Judul LHP</th>
                <th class="text-center">Temuan</th>
                <th class="text-center">Nilai Temuan</th>
                <!-- <th class="text-center">PIC Temuan</th> -->
                <th class="text-center">Level Resiko</th>
                <th class="text-center">Nilai<br> Rekomendasi</th>
                <th class="text-center">Saran dan<br>Rekomendasi</th>
                <th class="text-center">No.<br>Rekomendasi</th>
                <th class="text-center">Status<br>Rekomendasi</th>
                <th class="text-center">Tindak Lanjut</th>
                <th class="text-center">Nilai<br>Tindak Lanjut</th>
                <th class="text-center">Dokumen<br>Pendukung</th>
                <th class="text-center">PIC</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($rekomendasi as $k=> $item)
                @php
                    $dtindaklanjut=$ntindaklanjut=$doktindaklanjut=$pictindaklanjut='';
                    if(isset($tindaklanjut[$item->id_rekom]))
                    {
                        foreach ($tindaklanjut[$item->id_rekom] as $key => $value) {
                            $dtindaklanjut.='<li>'.$value->tindak_lanjut.'</li>';
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-left">{{$item->no_lhp}}</td>
                    <td class="text-left">{{$item->judul_lhp}}</td>
                    <td class="text-left">{{$item->temuan}}</td>
                    <td class="text-right">{{rupiah($item->nominal)}}</td>
                    
                    <td class="text-center">{{$item->level_resiko}}</td>
                    <td class="text-right">{{rupiah($item->nilai_rekomendasi)}}</td>
                    <td class="text-left">{{$item->rekom}}</td>
                    <td class="text-right">{{rupiah($item->nilai_rekomendasi)}}</td>
                    <td class="text-center">{{$item->st_rekom}}</td>
                    <td class="text-left"><ul>{!!$dtindaklanjut!!}</ul></td>
                    <td class="text-right"><ul>{!!$ntindaklanjut!!}</ul></td>
                    <td class="text-center"><ul>{!!$doktindaklanjut!!}</ul></td>
                    {{--<td class="text-left"><ul>{!!$pictindaklanjut!!}</ul></td>--}}
                    <td class="text-center">{{isset($pic_unit[$item->pic_temuan_id]) ? $pic_unit[$item->pic_temuan_id]->nama_pic : '-'}}</td>
                   
                    @if ($item->tanggal_penyelesaian=='')
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                    @else
                        <td class="text-center">{{tgl_indo($item->tanggal_penyelesaian)}}</td>
                        @php
                            $over=selisihhari($item->tanggal_penyelesaian,date('Y-m-d'),0);
                        @endphp
                        <td class="text-center">{{$over}} Hari</td>
                    @endif

                </tr> 
               
                @php
                    $no++;
                @endphp
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th colspan="3" style="text-align:left">Total:</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th colspan="4" style="text-align:left"></th>
                <th></th>
                <th colspan="4" style="text-align:left"></th>
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
            var columnTindaklanjut = 11;
            var columnRekom = 6;
            var columnTemuan = 4;
            totalTemuan = api
                .column( columnTemuan )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            pageTotalTemuan = api
                .column( columnTemuan, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column( columnTemuan ).footer() ).html(
                formatRupiah(pageTotalTemuan, 'Rp.') +' (Total: '+ formatRupiah(totalTemuan, 'Rp.') +')'
            );

            totalRekomendasi = api
                .column( columnRekom )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            pageTotalRekomendasi = api
                .column( columnRekom, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            $( api.column( columnRekom ).footer() ).html(
                formatRupiah(pageTotalRekomendasi,'Rp.') +' (Total: '+ formatRupiah(totalRekomendasi,'Rp.') +')'
            );

            totalTdklanjut = api
                .column( columnTindaklanjut )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            pageTotalTdklanjut = api
                .column( columnTindaklanjut, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            $( api.column( columnTindaklanjut ).footer() ).html(
                formatRupiah(pageTotalTdklanjut,'Rp.') +' (Total: '+ formatRupiah(totalTdklanjut,'Rp.') +')'
            );
        }
    });
    $('[data-toggle="tooltip"]').tooltip();
</script>