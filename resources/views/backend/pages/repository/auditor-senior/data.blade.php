<div class="table-responsive">
	<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">No. LHP</th>
                <th class="text-center">Judul LHP </th>
				<th class="text-center">Pemeriksa </th>
				<th class="text-center">Aksi</th>
			
			</tr>
		</thead>
		<tbody>
            @foreach ($data as $key=>$item)
            <tr>
                <td class="text-center">{{ ($key + 1) }}</td>
                <td class="text-left">
                    {{$item->no_lhp}}
                    {{-- <small style="font-size:10px;"><em>Nomor LHP :</em></small><br> --}}
                </td>
                
                <td>{{$item->judul_lhp}}</td>
                <td class="text-left">{{isset($item->dpemeriksa->pemeriksa) ? $item->dpemeriksa->pemeriksa : '-'}}</td>
                
                <td class="text-align:center;" style="width:80px;">
                    <div class="btn-group"> 
                        <button type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{url('detail-repository/'.$item->lhp_id)}}"><i class="fa fa-bars"></i> &nbsp;&nbsp;Dokumen Pendukung</a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
                
            @endforeach
            
		</tbody>
	</table>
</div>