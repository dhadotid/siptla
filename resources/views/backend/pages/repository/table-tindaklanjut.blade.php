<div class="table-responsive">
	<table id="table-tindaklanjut{{$rekom_id.$idtemuan}}" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Unit Kerja</th>
                <th class="text-center">Tindak Lanjut</th>
				<th class="text-center">Dokumen Pendukung</th>
			
			</tr>
		</thead>
		<tbody>
            @foreach ($data as $key=>$item)
            <tr>
                <td class="text-center">{{ ($key + 1) }}</td>
                <td class="text-left">{{isset($item->pic1->nama_pic) ? $item->pic1->nama_pic : '-'}}</td>
                
                <td class="text-left">
                    {{$item->tindak_lanjut}}
                </td>
                
                <td class="text-center" style="width:200px;">
                    @if (count($item->dokumen_tindak_lanjut)>0)
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;"><span class="caret"></span></button>&nbsp;
                        <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                            @foreach ($item->dokumen_tindak_lanjut as $file)
                                <li><a href="{{url('read-pdf/'.$file->path)}}" target="_blank" ><i class="fa fa-search">&nbsp;&nbsp;{{ $file->nama_dokumen }}</i></a></li>
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
                </td>
            </tr>
                
            @endforeach
            
		</tbody>
	</table>
</div>