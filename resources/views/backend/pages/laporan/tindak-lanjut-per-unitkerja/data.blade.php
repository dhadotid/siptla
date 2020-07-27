<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/tindaklanjut-per-unitkerja-pdf')}}" method="post" id="cetakpdf" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="no_lhp" value="{{implode(',', $no_lhp)}}">
                <input type="hidden" name="statusrekomendasi" value="{{implode(',', $request->statusrekomendasi)}}">
                <input type="hidden" name="bidang" value="{{implode(',', $request->bidang)}}">
                <input type="hidden" name="level_resiko" value="{{implode(',', $request->level_resiko)}}">
                <input type="hidden" name="unit_kerja1" value="{{implode(',', $unit_kerja1)}}">
                <input type="hidden" name="unit_kerja2" value="{{implode(',', $unit_kerja2)}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="overdue" value="{{$request->overdue}}">
                <input type="hidden" name="export" value="pdf">
                <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Cetak Data</button>
            </form>
        </div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/temuan-per-unitkerja-pdf')}}" method="post" id="cetakxls" target="_blank">
                @csrf
                <input type="hidden" name="pemeriksa" value="{{implode(',', $request->pemeriksa)}}">
                <input type="hidden" name="no_lhp" value="{{implode(',', $no_lhp)}}">
                <input type="hidden" name="statusrekomendasi" value="{{implode(',', $request->statusrekomendasi)}}">
                <input type="hidden" name="bidang" value="{{implode(',', $request->bidang)}}">
                <input type="hidden" name="level_resiko" value="{{implode(',', $request->level_resiko)}}">
                <input type="hidden" name="unit_kerja1" value="{{implode(',', $unit_kerja1)}}">
                <input type="hidden" name="unit_kerja2" value="{{implode(',', $unit_kerja2)}}">
                <input type="hidden" name="tanggal_awal" value="{{$request->tgl_awal}}">
                <input type="hidden" name="tanggal_akhir" value="{{$request->tgl_akhir}}">
                <input type="hidden" name="overdue" value="{{$request->overdue}}">
                <input type="hidden" name="export" value="xls">
                <button class="btn btn-xs btn-success"> <i class="fa fa-file-excel-o"></i> Export Ke Excel</button>
            </form>
        </div>
    </div>
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-12 text-center">
            <h5>
                @php
                $pemeriksaTitle='';
                if(implode(',', $request->pemeriksa) == 0){
                    $pemeriksaTitle.='Semua';
                }else{
                    foreach($npemeriksa as $k=>$v){
                        $pemeriksaTitle.=$v->pemeriksa.' ';
                    }
                }
                @endphp
                LAPORAN TINDAK LANJUT - UNIT KERJA <br>
                PEMERIKSA: <span style="font-weight: bold;" id="span_pemeriksa">{{$pemeriksaTitle}}</span><br>
                PERIODE: <span style="font-weight: bold;" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
                UNIT KERJA: <span style="font-weight: bold;" id="span_unitkerja">{{$selectedPic}}</span>
            </h5>
        </div>
    </div>
    <hr>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" style="width:150%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;" rowspan="2">#</th>
                <th class="text-center" colspan="2">LHP</th>
                <th class="text-center" colspan="4">Temuan Pemeriksa</th>
                <th class="text-center" colspan="4">Rekomendasi</th>
                <th class="text-center" colspan="5">Tindak Lanjut</th>
                <th class="text-center" rowspan="2">Review SPI</th>
                <th class="text-center" rowspan="2">Waktu Penyelesaian</th>
				<th class="text-center" rowspan="2">Overdue</th>
            </tr>
            <tr class="primary">
                <th class="text-center">No.LHP</th>
                <th class="text-center">Judul</th>
                <th class="text-center">Temuan</th>
                <th class="text-center">Nilai Temuan</th>
                <th class="text-center">PIC Temuan</th>
                <th class="text-center">Level Resiko</th>
                <th class="text-center">Nilai<br> Rekomendasi</th>
                <th class="text-center">Saran dan<br>Rekomendasi</th>
                <th class="text-center">No<br>Rekomendasi</th>
                <th class="text-center">Status<br>Rekomendasi</th>
                <th class="text-center">Tindak Lanjut</th>
                <th class="text-center">Nilai<br>Tindak Lanjut</th>
                <th class="text-center">Dokumen<br>Pendukung</th>
                <th class="text-center">Unit Kerja - 1</th>
                <th class="text-center">Unit Kerja - 2</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($rekomendasi as $k=> $item)
                @php
                    $dtindaklanjut=$ntindaklanjut=$doktindaklanjut=$unitkerja=$unitkerja2='';
                    if(isset($tindaklanjut[$item->id_rekom]))
                    {
                        foreach ($tindaklanjut[$item->id_rekom] as $key => $value) {
                            $dtindaklanjut.='<li>'.$value->tindak_lanjut.'</li>';
                        }
                    }
                    if(isset($pic_unit[$item->pic_1_temuan_id]))
                    {
                        $unitkerja.='<b>'.$pic_unit[$item->pic_1_temuan_id]->nama_pic.'</b>';
                    }
                    else
                        $unitkerja.='-';
                    if($item->pic_2_temuan_id != '' && isset($pic_unit[$item->pic_2_temuan_id])){
                        $unitkerja2.='<b>'.$pic_unit[$item->pic_2_temuan_id]->nama_pic.'</b>';
                    }else $unitkerja2.='-';
                @endphp
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-left">{{$item->no_lhp}}</td>
                    <td class="text-left">{{$item->judul_lhp}}</td>
                    <td class="text-left">{{$item->temuan}}</td>
                    <td class="text-right">{{rupiah($item->nominal)}}</td>
                    <td class="text-center">{{isset($pic_unit[$item->pic_temuan_id]) ? $pic_unit[$item->pic_temuan_id]->nama_pic : '-'}}</td>
                    <td class="text-center">{{$item->level_resiko}}</td>
                    <td class="text-right">{{rupiah($item->nilai_rekomendasi)}}</td>
                    <td class="text-left">{{$item->rekom}}</td>
                    <td class="text-right">{{rupiah($item->nilai_rekomendasi)}}</td>
                    <td class="text-center">{{$item->st_rekom}}</td>
                    <td class="text-left"><ul>{!!$dtindaklanjut!!}</ul></td>
                    <td class="text-right"><ul>{!!$ntindaklanjut!!}</ul></td>
                    <td class="text-center"><ul>{!!$doktindaklanjut!!}</ul></td>
                    <td class="text-left"><div style="width:100px;">{!!$unitkerja!!}</div></td>
                    <td class="text-left"><div style="width:100px;">{!!$unitkerja2!!}</div></td>
                    <td class="text-center">{{$item->review_spi}}</td>
                   
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
                <th colspan="4" style="text-align:left">Total:</th>
                <th></th>
                <th colspan="2"></th>
                <th></th>
                <th colspan="4"></th>
                <th></th>
                <th colspan="6"></th>
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
            var columnTindaklanjut = 12;
            var columnRekom = 7;
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