<div class="table-responsive">
	<table id="table-tindaklanjut-rincian{{$rekom_id.$idtemuan}}" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;">#</th>
                <th class="text-center">Jenis Rincian</th>
                <th class="text-center">Unit Kerja</th>
				<th class="text-center">Dokumen Pendukung</th>
			
			</tr>
		</thead>
		<tbody>
            @foreach ($data as $key=>$item)
            <tr>
                <td class="text-center">{{ ($key + 1) }}</td>
                <td class="text-left">
                @php $completeRincian = 'constants.rincian_val.' . $item->jenis; @endphp
                    {{Config::get($completeRincian)}}
                </td>

                <td class="text-left">{{isset($item->unit_kerja->nama_pic) ? $item->unit_kerja->nama_pic : '-'}}</td>
                
                <td class="text-center" style="width:200px;">
                        @if (is_array(json_decode($item->dokumen_pendukung, true)) || is_object(json_decode($item->dokumen_pendukung)))
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;"><span class="caret"></span></button>&nbsp;
                            <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                @foreach (json_decode($item->dokumen_pendukung, true) as $fileName)
                                    <li><a href="{{url('read-pdf/'.$fileName['file'])}}" target="_blank" ><i class="fa fa-search">&nbsp;&nbsp;{{ str_replace('public/dokumen/','',$fileName['file'] ) }}</i></a></li>
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