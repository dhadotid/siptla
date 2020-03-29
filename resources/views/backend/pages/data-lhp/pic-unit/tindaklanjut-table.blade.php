 <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr class="primary">
            <th class="text-center" style="width:20px;">#</th>
            <th class="text-center">Tindak Lanjut</th>
            <th class="text-center" style="width:120px;">Tanggal<br>Tindak Lanjut</th>
            {{-- <th class="text-center" >Rencana<br>Tindak Lanjut</th> --}}
            <th class="text-center" style="width:80px;">Dokumen<br>Pendukung</th>
            {{-- <th class="text-center" style="width:110px;">Rincian</th> --}}
            <th class="text-center" style="width:75px;">Catatan</th>
            <th class="text-center" style="width:70px;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @if ($tindaklanjut->count()==0)
            <tr>
                <td colspan="7" class="text-center">Data Tindak Lanjut Belum Tersedia</td>
            </tr>
        @else
            
            
            @foreach ($tindaklanjut as $no=>$item)
                @php
                    $catatan=\App\Models\CatatanMonev::where('id_tindaklanjut',$item->id)->get();
                @endphp
                <tr>
                    <td class="text-center">{{$no+1}}</td>
                    <td class="text-left">
                        @if (strlen($item->tindak_lanjut)>=50)
                            <a href="#" data-toggle="tooltip" data-title="{{$item->tindak_lanjut}}" title="{{$item->tindak_lanjut}}">{!!substr($item->tindak_lanjut,0,50)!!}...</a>
                        @else
                            {!!$item->tindak_lanjut!!}
                        @endif
                    </td>
                    <td class="text-center"><i class="fa fa-calendar"></i> {{tgl_indo($item->tgl_tindaklanjut)}}</td>
                    {{-- <td class="text-left">
                            {!!$item->action_plan!!}  
                    </td> --}}
                    <td class="text-center">
                        @if (isset($dok[$item->id]))
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-file"></i></button>
                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @foreach ($dok[$item->id] as $dk)
                                        <li><a href="{{url('read-pdf/'.$dk->path)}}" target="_blank"><i class="fa fa-chevron-right"></i> {{$dk->nama_dokumen}}</a> </li>
                                    @endforeach
                                </ul>
                            </div>
                            
                        @else
                            -
                        @endif
                    </td>
                    {{-- <td class="text-center">
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
                                    <a href="#" class="badge badge-secondary" style="font-size:15px">-</a>
                                @endif
                        @endif
                        
                    </td> --}}
                    <td class="text-center" style="width:75px;">
                        @if ($catatan->count()!=0)
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-list"></i></button>
                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @foreach ($catatan as $dk)
                                        <li><a href="javascript:detailcatatan({{$dk->id}})"><i class="fa fa-chevron-right"></i> Detail Catatan</a> </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            -
                        @endif  
                    </td>
                    <td class="text-center" style="width:80px;">
                        <div style="width:80px;">
                            @php
                                $userunit=\App\Models\PICUnit::where('id_user',Auth::user()->id)->first();
                                $listpic2=array();
                                
                            @endphp
                            @if ($userunit->id==$item->pic_1_id)
                                <a href="javascript:edittl({{$item->id}},{{$item->rekomendasi_id}},{{$item->temuan_id}},{{$item->lhp_id}})" class="btn-delete btn btn-xs btn-info" data-toggle="tooltip" data-title="Edit Tindak Lanjut" title="Edit Tindak Lanjut"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                                <a href="javascript:hapustl({{$item->id}},{{$item->rekomendasi_id}},{{$item->temuan_id}},{{$item->lhp_id}})" class="btn-edit btn btn-xs btn-danger" data-toggle="tooltip" data-title="Hapus Tindak Lanjut" title="Hapus Tindak Lanjut"><i class="glyphicon glyphicon-trash"></i></a>
                            @elseif ($userunit->id==$item->pic_2_id)
                                <a href="javascript:edittl({{$item->id}},{{$item->rekomendasi_id}},{{$item->temuan_id}},{{$item->lhp_id}})" class="btn-delete btn btn-xs btn-info" data-toggle="tooltip" data-title="Edit Tindak Lanjut" title="Edit Tindak Lanjut"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                                <a href="javascript:hapustl({{$item->id}},{{$item->rekomendasi_id}},{{$item->temuan_id}},{{$item->lhp_id}})" class="btn-edit btn btn-xs btn-danger" data-toggle="tooltip" data-title="Hapus Tindak Lanjut" title="Hapus Tindak Lanjut"><i class="glyphicon glyphicon-trash"></i></a>
                            @else
                                <a href="#" disabled class="btn-delete btn btn-xs btn-info" data-toggle="tooltip" data-title="Anda Tidak Dapat Melakukan Edit Tindak Lanjut ini" title="Anda Tidak Dapat Melakukan Edit Tindak Lanjut ini"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                                <a href="#" disabled class="btn-edit btn btn-xs btn-danger" data-toggle="tooltip" data-title="Anda Tidak Dapat Menghapus Tindak Lanjut ini" title="Anda Tidak Dapat Menghapus Tindak Lanjut ini"><i class="glyphicon glyphicon-trash"></i></a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
 </table>