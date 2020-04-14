<h3 class="text-center">Rincian Nilai Hutang Titipan</h3><table class="table table-bordered" id="table-rincian">
            <thead>
                <tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Saldo Hutang Titipan (Rp)</th>
                    <th class="text-center">Sisa Yang Harus Disetor (Rp)</th>
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
                    <td class="text-center">{{date('d/m/Y',strtotime($v->tanggal))}}</td>
                    <td class="text-center">{{$v->keterangan}}</td>
                    <td class="text-center">{{rupiah($v->saldo_hutang)}}</td>
                    <td class="text-center">{{rupiah($v->sisa_setor)}}</td>
                    <td class="text-center">
                        <div style="width:80px;">
                            <a href="javascript:addtindaklanjutrincian({{$v->id}},'hutangtitipan')" class="btn-delete btn btn-xs btn-info"><i class="glyphicon glyphicon-plus"></i></a>&nbsp;
                            <a href="javascript:listtindaklanjutrincian({{$v->id}},'hutangtitipan')" class="btn-edit btn btn-xs btn-success"><i class="glyphicon glyphicon-list"></i></a>
                        </div>
                    </td>
                </tr>
                @php
                    $no++;
                    $totalnilai+=$v->sisa_setor;
                @endphp
            @endforeach
            @if (isset($idtl))
                <input type="hidden" id="idformtindaklanjut" name="idformtindaklanjut" value="{{$idtl}}">
            @endif
            <input type="hidden" id="total_nilai" value="{{$totalnilai}}">

            @if (Auth::user()->level != 'pic-unit')
                    <tr >
                        <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut('hutangtitipan','{{$idtemuan}}','{{$idrekomendasi}}',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                    </tr>
            @endif

            </tbody>
            </table>