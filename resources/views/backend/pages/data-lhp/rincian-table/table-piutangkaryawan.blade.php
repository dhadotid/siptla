<h3 class="text-center">Rincian Nilai – Rekomendasi Piutang Karyawan</h3><table class="table table-bordered" id="table-rincian-piutangkaryawan">
            <thead>
                <tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Karyawan</th>
                    <th class="text-center">Jumlah Pinjaman</th>
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
                    <td class="text-center">{{$v->karyawan}}</td>
                    <td class="text-center">{{rupiah($v->pinjaman)}}</td>
                    <td class="text-center">
                        @if (Auth::user()->level == 'pic-unit')
                        <a href="javascript:addtindaklanjutrincian({{$v->id}},'piutangkaryawan',{{$v->pinjaman}},'{{Config::get('constants.rincian.piutangkaryawan')}}')" class="btn-delete btn btn-xs btn-info"><i class="glyphicon glyphicon-plus"></i></a>&nbsp;
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
                                                            <td class="text-center">{{rupiah($v->pinjaman)}}</td>
                                                        @else
                                                            <td class="text-center">0</td>
                                                        @endif
                                                    @else
                                                        @foreach($rinciantindaklanjut as $rtl)
                                                            @if($rtl->rekomendasi == $rekom->rekomendasi)
                                                                <td class="text-center">{{rupiah($rtl->sum)}}</td>
                                                                @php $totalbtl = $v->pinjaman - $rtl->sum; @endphp
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
                        <a href="javascript:listtindaklanjutrincian({{$v->id}},'piutangkaryawan',{{$v->pinjaman}})" class="btn-edit btn btn-xs btn-success"><i class="glyphicon glyphicon-list"></i></a>
                    </td>
                </tr>
                @php
                    $no++;
    
                    $totalnilai+=$v->pinjaman;
                @endphp
            @endforeach
            @if (isset($idtl))
                <input type="hidden" id="idformtindaklanjut" name="idformtindaklanjut" value="{{$idtl}}">
            @endif
            <input type="hidden" id="total_nilai" value="{{$totalnilai}}">

            @if (Auth::user()->level != 'pic-unit')
                    <tr >
                        <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut('piutangkaryawan','{{$idtemuan}}','{{$idrekomendasi}}',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                    </tr>
            @endif

            </tbody>
            </table>