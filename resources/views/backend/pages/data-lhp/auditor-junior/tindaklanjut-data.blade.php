<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-4 text-right">
            <a class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Cetak Data</a>
            <a class="btn btn-xs btn-success"><i class="fa fa-file-excel-o"></i> Export Ke Excel</a>
        </div>
    </div>

	<table id="table-data" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">No Temuan</th>
                <th class="text-center">Temuan</th>
				<th class="text-center">No. Rekomendasi</th>
				<th class="text-center">Rekomendasi</th>
				<th class="text-center">Tanggal Penyelesaian</th>
				<th class="text-center">Aksi</th>
			</tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($temuan as $item)
                @php
                    $rekom=$norekom=$tglselesai=$aksi='';
                    if(isset($rekomendasi[$item->id]))
                    {
                        foreach($rekomendasi[$item->id] as $key=>$val)
                        {
                            $norekom.=$val->nomor_rekomendasi.'<br>';
                            $rekom.=$val->rekomendasi.'<br>';
                            $tglselesai.=tgl_indo($val->tanggal_penyelesaian).'<br>';
                            $aksi='<ul class="dropdown-menu" role="menu">
                            <li><a href="#" target="_blank"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>
                            <li>
                                <a href="#" class="btn-edit" data-toggle="modal" data-target="#modalubah" data-value="'.$item->id.'"><i class="glyphicon glyphicon-edit"></i> &nbsp;&nbsp;Edit Tindak Lanjut</a>
                            </li>
                            <li>
                                <a href="#" class="btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="'.$item->id.'"><i class="glyphicon glyphicon-trash"></i> &nbsp;&nbsp;Hapus Tindak Lanjut</a>
                            </li>
                        </ul>';
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-center">{{$item->no_temuan}}</td>
                    <td class="text-center">{{$item->temuan}}</td>
                    <td class="text-center">{{$norekom}}</td>
                    <td class="text-center">{{$rekom}}</td>
                    <td class="text-center">{{$tglselesai}}</td>
                    <td class="text-center">{!!$aksi!!}</td>
                </tr>
                @php
                    $no++;
                @endphp
            @endforeach
        </tbody>
    </table>
</div>