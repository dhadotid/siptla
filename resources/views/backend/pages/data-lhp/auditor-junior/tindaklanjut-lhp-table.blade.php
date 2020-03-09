 <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr class="primary">
            <th class="text-center" style="width:15px;">#</th>
            <th class="text-center">Unit Kerja</th>
            <th class="text-center">Tindak Lanjut</th>
            <th class="text-center">Tanggal<br>Tindak Lanjut</th>
            <th class="text-center">Rencana<br>Tindak Lanjut</th>
            <th class="text-center">Dokumen<br>Pendukung</th>
            <th class="text-center">Rincian</th>
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
                        @if (isset($item->pic1->nama_pic))
                            <b>{{$item->pic1->nama_pic}}</b>
                        @elseif (isset($item->pic2->nama_pic))
                            <b>{{$item->pic2->nama_pic}}</b>
                        @endif
                    </td>
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
                                        <a href="javascript:listrinciantl({{$item->rekomendasi_id}},{{$item->pic_1_temuan_id}},{{$item->id}})" class="badge badge-secondary" style="font-size:15px"><i class="fa fa-list"></i></a>
                                    @else
                                        <a href="#" class="badge badge-secondary" style="font-size:15px">0</a>
                                    @endif
                                @else
                                    <a href="#" class="badge badge-secondary" style="font-size:15px">0</a>
                                @endif
                            @else
                                <a href="#" class="badge badge-primary" style="font-size:15px">{{count($tindaklanjut_rincian[$item->id])}} </a>
                                <a href="javascript:listrinciantl({{$item->rekomendasi_id}},{{$item->pic_1_temuan_id}},{{$item->id}})" class="badge badge-secondary" style="font-size:15px"><i class="fa fa-list"></i></a>
                            @endif
                        @else
                            @if (isset($item->drekomendasi->rincian))
                                    @if ($item->drekomendasi->rincian!='')
                                        <a href="#" class="badge badge-secondary" style="font-size:15px">0</a>
                                        <a href="javascript:listrinciantl({{$item->rekomendasi_id}},{{$item->pic_1_temuan_id}},{{$item->id}})" class="badge badge-secondary" style="font-size:15px"><i class="fa fa-list"></i></a>
                                    @else
                                        <a href="#" class="badge badge-secondary" style="font-size:15px">0</a>
                                    @endif
                                @else
                                    <a href="#" class="badge badge-secondary" style="font-size:15px">-</a>
                                @endif
                        @endif
                        
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
 </table>