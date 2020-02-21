<div class="table-responsive">
	<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Nomor LHP </th>
                <th class="text-center">Kode LHP </th>
				<th class="text-center">Pemeriksa</th>
				<th class="text-center">Tgl Pemeriksaan </th>
				<th class="text-center">Judul LHP </th>
				<th class="text-center">Aksi</th>
			
			</tr>
		</thead>
		<tbody>
            @foreach ($data as $key=>$item)
                @if ($statusrekom!=null)
                    @if (in_array($item->lhp_id,$arraylhp))
                        <tr>
                            <td class="text-center">{{ ($key + 1) }}</td>
                            <td class="text-center">
                                {{$item->no_lhp}}
                                {{-- <small style="font-size:10px;"><em>Nomor LHP :</em></small><br> --}}
                            </td>
                            <td>
                                @if ($statusrekom!=null)
                                     <a href="#" onclick="detaillhp({{$item->lhp_id}},0,{{$statusrekom}})">{{$item->kode_lhp}}</a>
                                @else
                                    <a href="#" onclick="detaillhp({{$item->lhp_id}},0)">{{$item->kode_lhp}}</a>
                                @endif
                                {{-- <small style="font-size:10px;"><em>Kode LHP :</em></small><br> --}}
                            </td>
                            <td class="text-center">{{isset($item->dpemeriksa->pemeriksa) ? $item->dpemeriksa->pemeriksa : '-'}}</td>
                            <td class="text-center">{{tgl_indo($item->tanggal_lhp)}}</td>
                            <td>{{$item->judul_lhp}}</td>
                            <td class="text-align:center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-bars"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            @if ($statusrekom!=null)
                                                <a href="{{url('data-temuan-lhp/'.$item->lhp_id.'/'.$statusrekom)}}"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Data Temuan</a>
                                            @else
                                                <a href="{{url('data-temuan-lhp/'.$item->lhp_id)}}"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Data Temuan</a>
                                            @endif
                                        </li>
                                        {{-- <li><a href="{{url('data-lhp-detail/'.$item->lhp_id)}}" target="_blank"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail LHP</a></li> --}}
                                        <li>
                                            @if ($statusrekom!=null)
                                                <a href="#" onclick="detaillhp({{$item->lhp_id}},0,{{$statusrekom}})"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail LHP</a>    
                                            @else
                                                <a href="#" onclick="detaillhp({{$item->lhp_id}},0)"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail LHP</a>
                                            @endif
                                            
                                        </li>
                                        <li>
                                            <a href="#" data-toggle="modal" data-target="#modalreview" data-value="{{$item->lhp_id}}" class="btn-review"><i class="glyphicon glyphicon-comment"></i> &nbsp;&nbsp;Tanggapan dan Review LHP</a>
                                        </li>
                                        @if (Auth::user()->level=='auditor-senior')  
                                            @if ($item->status_lhp!='Publish LHP') 
                                                <li>
                                                    <a href="#" data-toggle="modal" data-target="#modaladdreview" data-value="{{$item->lhp_id}}" class="btn-add-review"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tanggapan dan Review LHP</a>
                                                </li>
                                            @endif    
                                        @endif
                                        <li>
                                            <a href="#" class="btn-edit" data-toggle="modal" data-target="#modalubah" data-value="{{$item->lhp_id}}"><i class="glyphicon glyphicon-edit"></i> &nbsp;&nbsp;Edit LHP</a>
                                        </li>
                                        <li>
                                            <a href="#" class="btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="{{$item->lhp_id}}"><i class="glyphicon glyphicon-trash"></i> &nbsp;&nbsp;Hapus LHP</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td class="text-center">{{ ($key + 1) }}</td>
                        <td class="text-center">
                            {{$item->no_lhp}}
                            {{-- <small style="font-size:10px;"><em>Nomor LHP :</em></small><br> --}}
                        </td>
                        <td>
                                @if ($statusrekom!=null)
                                     <a href="#" onclick="detaillhp({{$item->lhp_id}},0,{{$statusrekom}})">{{$item->kode_lhp}}</a>
                                @else
                                    <a href="#" onclick="detaillhp({{$item->lhp_id}},0)">{{$item->kode_lhp}}</a>
                                @endif
                            {{-- {{$item->kode_lhp}} --}}
                            {{-- <small style="font-size:10px;"><em>Kode LHP :</em></small><br> --}}
                        </td>
                        <td class="text-center">{{isset($item->dpemeriksa->pemeriksa) ? $item->dpemeriksa->pemeriksa : '-'}}</td>
                        <td class="text-center">{{tgl_indo($item->tanggal_lhp)}}</td>
                        <td>{{$item->judul_lhp}}</td>
                        <td class="text-align:center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-bars"></i></button>
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        @if ($statusrekom!=null)
                                            <a href="{{url('data-temuan-lhp/'.$item->lhp_id.'/'.$statusrekom)}}"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Data Temuan</a>
                                        @else
                                            <a href="{{url('data-temuan-lhp/'.$item->lhp_id)}}"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Data Temuan</a>
                                        @endif
                                    </li>
                                    {{-- <li><a href="{{url('data-lhp-detail/'.$item->lhp_id)}}" target="_blank"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail LHP</a></li> --}}
                                    <li>
                                        @if ($statusrekom!=null)
                                            <a href="#" onclick="detaillhp({{$item->lhp_id}},0,{{$statusrekom}})"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail LHP</a>
                                        @else
                                            <a href="#" onclick="detaillhp({{$item->lhp_id}},0)"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail LHP</a>
                                        @endif
                                    </li>
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#modalreview" data-value="{{$item->lhp_id}}" class="btn-review"><i class="glyphicon glyphicon-comment"></i> &nbsp;&nbsp;Tanggapan dan Review LHP</a>
                                    </li>
                                    @if (Auth::user()->level=='auditor-senior')  
                                        @if ($item->status_lhp!='Publish LHP') 
                                            <li>
                                                <a href="#" data-toggle="modal" data-target="#modaladdreview" data-value="{{$item->lhp_id}}" class="btn-add-review"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tanggapan dan Review LHP</a>
                                            </li>
                                        @endif    
                                    @endif
                                    <li>
                                        <a href="#" class="btn-edit" data-toggle="modal" data-target="#modalubah" data-value="{{$item->lhp_id}}"><i class="glyphicon glyphicon-edit"></i> &nbsp;&nbsp;Edit LHP</a>
                                    </li>
                                    <li>
                                        <a href="#" class="btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="{{$item->lhp_id}}"><i class="glyphicon glyphicon-trash"></i> &nbsp;&nbsp;Hapus LHP</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endif
                
            @endforeach
            
		</tbody>
	</table>
</div>