<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-1 text-right">
            <form action="{{url('laporan/rekomendasi-overdue-unitkerja-pdf')}}" method="post" id="cetakpdf" target="_blank">
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
                LAPORAN REKOMENDASI OVERDUE - UNIT KERJA<br>
                STATUS REKOMENDASI : BELUM SELESAI DAN BELUM DITINDAK LANJUTI<br>
                PERIODE <span style="font-weight: bold;text-decoration:underline" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;text-decoration:underline" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span> <br>
            </h5>
        </div>
    </div>
    <hr>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr class="primary">
				<th class="text-center" rowspan="2" style="width:15px;">#</th>
                <th class="text-center" rowspan="2">Bidang</th>
                <th class="text-center" rowspan="2">Unit Kerja</th>
                <th class="text-center" colspan="4">Belum Selesai</th>
                <th class="text-center" colspan="4">Belum Ditindaklanjuti</th>
                <th class="text-center" rowspan="2">% Overdue</th>
                <th class="text-center" rowspan="2">Jumlah</th>
            </tr>
            <tr class="primary">
                <th class="text-center">High</th>
                <th class="text-center">Medium</th>
                <th class="text-center">Low</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">High</th>
                <th class="text-center">Medium</th>
                <th class="text-center">Low</th>
                <th class="text-center">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($rekomendasi as $k=> $item)
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-left">
                        @if (isset($dp[$item->pic_1_temuan_id]))
                            @if (isset($nbid[$dp[$item->pic_1_temuan_id]->bidang]))
                                {{$nbid[$dp[$item->pic_1_temuan_id]->bidang]->nama_bidang}}
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-left">
                        @if (isset($dp[$item->pic_1_temuan_id]))
                            {{$dp[$item->pic_1_temuan_id]->nama_pic}}
                        @else
                            -
                        @endif    
                    </td>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$no}}</td>
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