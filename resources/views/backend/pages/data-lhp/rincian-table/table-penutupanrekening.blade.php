<h3 class="text-center">Rincian Nilai Non Setoran – Penutupan Rekening</h3><table class="table table-bordered" id="table-rincian">
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
                    <td class="text-center">{{rupiah($v->saldo_akhir)}}</td>
                    <td class="text-center">
                        <a href="javascript:addtindaklanjutrincian({{$v->id}},'penutupanrekening')" class="btn-delete btn btn-xs btn-info"><i class="glyphicon glyphicon-plus"></i></a>&nbsp;
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

                @if (Auth::user()->level != 'pic-unit')
                    <tr >
                        <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut('penutupanrekening','{{$idtemuan}}','{{$idrekomendasi}}',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                    </tr>
                @endif
            </tbody>
        </table>