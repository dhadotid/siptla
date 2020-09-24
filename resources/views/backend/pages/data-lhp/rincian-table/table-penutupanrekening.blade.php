<h3 class="text-center">Rincian Nilai – Rekomendasi Penutupan Rekening</h3><table class="table table-bordered" id="table-rincian-penutupanrekening">
            <thead>
                <tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Nama Bank</th>
                    <th class="text-center">Nomor Rekening</th>
                    <th class="text-center">Nama Rekening</th>
                    <th class="text-center">Jenis Rekening</th>
                    <th class="text-center">Saldo Akhir</th>
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
                    <td class="text-center">{{$v->nama_bank}}</td>
                    <td class="text-center">{{$v->nomor_rekening}}</td>
                    <td class="text-center">{{$v->nama_rekening}}</td>
                    <td class="text-center">{{$v->jenis_rekening}}</td>
                    <td class="text-center">{{rupiah((int)$v->saldo_akhir)}}</td>
                    <td class="text-center">
                        @if (Auth::user()->level == 'pic-unit')
                        <a href="javascript:addtindaklanjutrincian({{$v->id}},'penutupanrekening','{{Config::get('constants.rincian.penutupanrekening')}}')" class="btn-delete btn btn-xs btn-info"><i class="glyphicon glyphicon-plus"></i></a>&nbsp;
                        @else
                            {{--<div class="btn-group">
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
                                                            <td class="text-center">{{rupiah((int)$v->saldo_akhir)}}</td>
                                                        @else
                                                            <td class="text-center">0</td>
                                                        @endif
                                                    @else
                                                    @if($rinciantindaklanjut[$v->id][0]->rekomendasi == $rekom->rekomendasi)
                                                                <td class="text-center">{{rupiah($rinciantindaklanjut[$v->id][0]->sum)}}</td>
                                                                @php $totalbtl = (int)$v->saldo_akhir - $rinciantindaklanjut[$v->id][0]->sum; @endphp
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
                            </div>--}}
                        @endif
                        <a href="javascript:listtindaklanjutrincian({{$v->id}},'penutupanrekening')" class="btn-edit btn btn-xs btn-success"><i class="glyphicon glyphicon-list"></i></a>
                    </td>
                </tr>
                @php
                    $no++;
                    $totalnilai+=$v->saldo_akhir;
                @endphp
                @if (isset($idtl))
                    <input type="hidden" id="idformtindaklanjut" name="idformtindaklanjut" value="{{$idtl}}">
                @endif
                <input type="hidden" id="total_nilai" value="{{$totalnilai}}">

            @endforeach
            </tbody>
        </table>

        @if (Auth::user()->level != 'pic-unit')
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut('penutupanrekening','{{$idtemuan}}','{{$idrekomendasi}}',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>
                @endif