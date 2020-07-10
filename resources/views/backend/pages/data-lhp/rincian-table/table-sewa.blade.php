<h3 class="text-center">Rincian Nilai - Rekomendasi Pembayaran Sewa</h3>
@php
    $totalRekom = 0;
    foreach($rincian as $val){
        $totalRekom += $val->nilai_pekerjaan;
    }
@endphp
<h5 class="text-center">Total Rekomendasi : Rp {{rupiah($totalRekom)}}</h5>
<table class="table table-bordered" id="table-rincian-sewa">
    <thead>
        <tr class="inverse">
            <th class="text-center">No</th>
            <th class="text-center">Unit Kerja</th>
            <th class="text-center">Mitra</th>
            <th class="text-center">No. PKS</th>
            <th class="text-center">Tgl. PKS</th>
            <th class="text-center">Nilai Rekomendasi</th>
            <th class="text-center">Masa Kontrak</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead><tbody>
    @php
        $no=1;
        $totalnilai=0;
    @endphp
    @foreach($rincian as $k=>$v)
        <tr>
            <td class="text-center">{{$no}}</td>
            <td class="text-center">{{$v->unit_kerja}}</td>
            <td class="text-center">{{$v->mitra}}</td>
            <td class="text-center">{{$v->no_pks}}</td>
            <td class="text-center">{{($v->tgl_pks!='' ? date('d/m/Y',strtotime($v->tgl_pks)) : '')}}</td>
            <td class="text-center">{{rupiah($v->nilai_pekerjaan)}}</td>
            <td class="text-center">{{($v->masa_berlaku!='' ? date('d/m/Y',strtotime($v->masa_berlaku)) : '')}}</td>
            <td class="text-center" style="width:90px;">
                @if (Auth::user()->level == 'pic-unit')
                <a href="javascript:addtindaklanjutrincian({{$v->id}},'sewa', {{$v->nilai_pekerjaan}},'{{Config::get('constants.rincian.sewa')}}')" class="btn-delete btn btn-xs btn-info"><i class="glyphicon glyphicon-plus"></i></a>&nbsp;
                @else
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;"><span class="caret"></span></button>&nbsp;
                        <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="inverse">
                                        <th class="text-center">Status Tindak Lanjut Rincian</th>
                                        <th class="text-center">Nilai Rekomendasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($status_rekomendasi as $rekom)
                                            <tr>
                                                    @if($rekom->flag==0)
                                                        <td class="text-center">{{$rekom->rekomendasi}}</td>
                                                    @endif
                                                    @if(count($rinciantindaklanjut) == 0)
                                                        @if($rekom->id == 3)
                                                            <td class="text-center">{{rupiah($v->nilai_pekerjaan)}}</td>
                                                        @else
                                                            <td class="text-center">0</td>
                                                        @endif
                                                    @else
                                                        @foreach($rinciantindaklanjut as $rtl)
                                                            @if($rtl->rekomendasi == $rekom->rekomendasi)
                                                                <td class="text-center">{{rupiah($rtl->sum)}}</td>
                                                                @php $totalbtl = $v->nilai_pekerjaan - $rtl->sum; @endphp
                                                            @elseif($rekom->id == 3)
                                                                <td class="text-center">{{rupiah($totalbtl)}}</td>
                                                            @else
                                                                <td class="text-center">0</td>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                            </tr>
                                            @endforeach
                                </tbody>
                            </table>
                        </ul>
                    </div>
                @endif
                <a href="javascript:listtindaklanjutrincian({{$v->id}},'sewa', {{$v->nilai_pekerjaan}})" class="btn-edit btn btn-xs btn-success"><i class="glyphicon glyphicon-list"></i></a>
            </td>
        </tr>
        @php
            $no++;
            $totalnilai+=$v->nilai_pekerjaan;
        @endphp
    @endforeach   
        @if (isset($idtl))
                <input type="hidden" id="idformtindaklanjut" name="idformtindaklanjut" value="{{$idtl}}">
            @endif
        <input type="hidden" id="total_nilai" value="{{$totalnilai}}">
      
    </tbody>
 </table>