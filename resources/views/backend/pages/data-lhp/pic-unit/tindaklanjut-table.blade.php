 <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr class="primary">
            <th class="text-center" style="width:15px;">#</th>
            <th class="text-center">Tindak Lanjut</th>
            <th class="text-center">Tanggal<br>Tindak Lanjut</th>
            <th class="text-center">Rencana<br>Tindak Lanjut</th>
            <th class="text-center">Dokumen<br>Pendukung</th>
            <th class="text-center">Rincian</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @if ($tindaklanjut->count()==0)
            <tr>
                <td colspan="7" class="text-center">Data Tindak Lanjut Belum Tersedia</td>
            </tr>
        @else
            
            
            @foreach ($tindaklanjut as $no=>$item)
                <tr>
                    <td class="text-center">{{$no+1}}</td>
                    <td class="text-left">
                        @if (strlen($item->tindak_lanjut)>=30)
                            <a href="#" data-toggle="tooltip" data-title="{{$item->tindak_lanjut}}" title="{{$item->tindak_lanjut}}">{!!substr($item->tindak_lanjut,0,35)!!}...</a>
                        @else
                            {!!$item->tindak_lanjut!!}
                        @endif
                    </td>
                    <td class="text-center"><i class="fa fa-calendar"></i> {{tgl_indo($item->tgl_tindaklanjut)}}</td>
                    <td class="text-left">
                            {!!$item->action_plan!!}  
                    </td>
                    <td class="text-center">
                        @if (isset($dok[$item->id]))
                            <a href="{{url('read-pdf/'.$dok[$item->id]->path)}}" target="_blank" class="btn btn-xs btn-success" data-toggle="tooltip" data-title="Lihat Dokumen" title="Lihat Dokumen"><i class="fa fa-file-o"></i></a>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if (isset($tindaklanjut_rincian[$item->id]))
                            @if (count($tindaklanjut_rincian[$item->id])==0)
                                @if (isset($item->drekomendasi->rincian))
                                    @if ($item->drekomendasi->rincian!='')
                                        <a href="#" class="badge badge-secondary" style="font-size:15px">0</a>
                                        <a href="javascript:listrinciantl({{$item->rekomendasi_id}},{{$user_pic->id}},{{$item->id}})" class="badge badge-secondary" style="font-size:15px"><i class="fa fa-list"></i></a>
                                    @else
                                        <a href="#" class="badge badge-secondary" style="font-size:15px">0</a>
                                    @endif
                                @else
                                    <a href="#" class="badge badge-secondary" style="font-size:15px">0</a>
                                @endif
                            @else
                                <a href="#" class="badge badge-primary" style="font-size:15px">{{count($tindaklanjut_rincian[$item->id])}} </a>
                                <a href="javascript:listrinciantl({{$item->rekomendasi_id}},{{$user_pic->id}},{{$item->id}})" class="badge badge-secondary" style="font-size:15px"><i class="fa fa-list"></i></a>
                            @endif
                        @else
                            @if (isset($item->drekomendasi->rincian))
                                    @if ($item->drekomendasi->rincian!='')
                                        <a href="#" class="badge badge-secondary" style="font-size:15px">0</a>
                                        <a href="javascript:listrinciantl({{$item->rekomendasi_id}},{{$user_pic->id}},{{$item->id}})" class="badge badge-secondary" style="font-size:15px"><i class="fa fa-list"></i></a>
                                    @else
                                        <a href="#" class="badge badge-secondary" style="font-size:15px">0</a>
                                    @endif
                                @else
                                    <a href="#" class="badge badge-secondary" style="font-size:15px">0</a>
                                @endif
                        @endif
                        
                    </td>
                    <td class="text-center" style="width:80px;">
                        <div style="width:80px;">
                            <a href="javascript:edittl({{$item->id}},{{$item->rekomendasi_id}},{{$item->temuan_id}},{{$item->lhp_id}})" class="btn-delete btn btn-xs btn-info" data-toggle="tooltip" data-title="Edit Tindak Lanjut" title="Edit Tindak Lanjut"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                            <a href="javascript:hapustl({{$item->id}},{{$item->rekomendasi_id}},{{$item->temuan_id}},{{$item->lhp_id}})" class="btn-edit btn btn-xs btn-danger" data-toggle="tooltip" data-title="Hapus Tindak Lanjut" title="Hapus Tindak Lanjut"><i class="glyphicon glyphicon-trash"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
 </table>