<h3 class="text-center">Rincian Nilai Setoran – Piutang Karyawan</h3><table class="table table-bordered" id="table-rincian">
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
                        <a href="javascript:addtindaklanjutrincian({{$v->id}},'piutangkaryawan')" class="btn-delete btn btn-xs btn-info"><i class="glyphicon glyphicon-plus"></i></a>&nbsp;
                        <a href="javascript:listtindaklanjutrincian({{$v->id}},'piutangkaryawan')" class="btn-edit btn btn-xs btn-success"><i class="glyphicon glyphicon-list"></i></a>
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