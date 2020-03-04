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
                        @if (strlen($item->action_plan)>=30)
                            <a href="#" data-toggle="tooltip" data-title="{{$item->action_plan}}" title="{{$item->action_plan}}">{!!substr($item->action_plan,0,35)!!}...</a>
                        @else
                            {!!$item->action_plan!!}
                        @endif    
                    </td>
                    <td class="text-center">
                        @if (isset($dok[$item->id]))
                            <a class="btn btn-xs btn-success" data-toggle="tooltip" data-title="Lihat Dokumen" title="Lihat Dokumen"><i class="fa fa-file-o"></i></a>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-left">

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