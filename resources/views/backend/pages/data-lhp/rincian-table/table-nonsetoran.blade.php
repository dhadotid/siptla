<h3 class="text-center">Rincian Nilai – Rekomendasi Non Setoran</h3><table class="table table-bordered"  id="table-rincian-nonsetoran">
            <thead>
                <tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Nilai Rekomendasi (Rp)</th>
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
                    <td class="text-center">{{$v->keterangan}}</td>
                    <td class="text-center">{{rupiah((int)$v->nilai_rekomendasi)}}</td>
                    <td class="text-center">
                        @if (Auth::user()->level == 'pic-unit')
                            <a href="javascript:addtindaklanjutrincian({{$v->id}},'nonsetoran','{{Config::get('constants.rincian.nonsetoran')}}')" class="btn-delete btn btn-xs btn-info"><i  class="glyphicon glyphicon-plus"></i></a>&nbsp;
                        @else
                        {{-- 
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
                                                    @if(count($rinciantindaklanjut[$v->id]) == 0)
                                                        @if($rekom->id == 3)
                                                            <td class="text-center">{{rupiah((int)$v->nilai_rekomendasi)}}</td>
                                                        @else
                                                            <td class="text-center">0</td>
                                                        @endif
                                                    @else
                                                    @if($rinciantindaklanjut[$v->id][0]->rekomendasi == $rekom->rekomendasi)
                                                                <td class="text-center">{{rupiah($rinciantindaklanjut[$v->id][0]->sum)}}</td>
                                                                @php $totalbtl = (int)$v->nilai_rekomendasi - $rinciantindaklanjut[$v->id][0]->sum; @endphp
                                                            @elseif($rekom->id == 3)
                                                                <td class="text-center">{{rupiah($totalbtl)}}</td>
                                                            @else
                                                                <td class="text-center">0</td>
                                                            @endif
                                                    @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </ul>
                            </div>
                            --}}
                        @endif
                        <a href="javascript:listtindaklanjutrincian({{$v->id}},'nonsetoran')" class="btn-edit btn btn-xs btn-success"><i class="glyphicon glyphicon-list"></i></a>
                    </td>
                </tr>
                @php
                    $no++;
                    $totalnilai+=(int)$v->jumlah_rekomendasi;
                @endphp
            @endforeach
            @if (isset($idtl))
                <input type="hidden" id="idformtindaklanjut" name="idformtindaklanjut" value="{{$idtl}}">
            @endif
  
            <input type="hidden" id="total_nilai" value="{{$totalnilai}}">
                
            </tbody>
            </table>

            @if (Auth::user()->level != 'pic-unit')
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut('nonsetoran','{{$idtemuan}}','{{$idrekomendasi}}',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>
            @endif