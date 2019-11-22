<div class="table-responsive">
	<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Nomor</th>
                <th class="text-center">LHP </th>
				<th class="text-center">Tgl Pemeriksaan </th>
				<th class="text-center">Judul LHP </th>
				<th class="text-center">Pemeriksa </th>
				<th class="text-center">Jenis Audit/Review </th>
				<th class="text-center">Status LHP </th>
				<th class="text-center">Aksi</th>
			
			</tr>
		</thead>
		<tbody>
            @foreach ($data as $key=>$item)
                <tr>
                    <td class="text-center">{{ ($key + 1) }}</td>
                    <td class="">
                        <b>{{$item->no_lhp}}</b>
                        {{-- <small style="font-size:10px;"><em>Nomor LHP :</em></small><br> --}}
                    </td>
                    <td>
                        <b>{{$item->kode_lhp}}</b>
                        {{-- <small style="font-size:10px;"><em>Kode LHP :</em></small><br> --}}
                    </td>
                    <td class="text-center">{{tgl_indo($item->tanggal_lhp)}}</td>
                    <td><b>{{$item->judul_lhp}}</b></td>
                    <td class="text-center"><b>{{isset($item->dpemeriksa->pemeriksa) ? $item->dpemeriksa->pemeriksa : '-'}}</b></td>
                    <td class="">{{isset($item->djenisaudit->jenis_audit) ? $item->djenisaudit->jenis_audit : '-'}}</td>
                    <td class="text-center">
                        @if ($item->create_flag==1)
                            <span class="label label-success menu-label">Create LHP</span>
                        @elseif ($item->review_flag==1)
                            <span class="label label-primary menu-label">Review LHP</span>
                        @elseif ($item->publish_flag==1)
                            <span class="label label-info menu-label">Publish LHP</span>
                        @else
                            <span class="label label-default menu-label">n/a</span>
                        @endif
                        
                    </td>
                    <td class="text-align:center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-bars"></i></button>
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{url('data-temuan-lhp/'.$item->lhp_id)}}"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Data Temuan</a></li>
                                <li><a href="{{url('data-lhp-detail/'.$item->lhp_id)}}" target="_blank"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail LHP</a></li>
                                <li><a href="#"><i class="glyphicon glyphicon-comment"></i> &nbsp;&nbsp;Tanggapan dan Review LHP</a></li>
                                <li><a href="#"><i class="glyphicon glyphicon-edit"></i> &nbsp;&nbsp;Edit LHP</a></li>
                                <li><a href="#"><i class="glyphicon glyphicon-trash"></i> &nbsp;&nbsp;Hapus LHP</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            
		</tbody>
	</table>
</div>