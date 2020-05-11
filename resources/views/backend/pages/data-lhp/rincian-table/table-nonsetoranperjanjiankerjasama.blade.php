<h3 class="text-center">Rincian Nilai Non Setoran – Perpanjangan Perjanjian Kerjasama</h3><table class="table table-bordered" id="table-rincian">
    <thead>
        <tr class="inverse">
            <th class="text-center">No</th>
            <th class="text-center">Unit Kerja</th>
            <th class="text-center">No. PKS</th>
            <th class="text-center">Tgl. PKS</th>
            <th class="text-center">Periode</th>
            <th class="text-center">Keterangan</th>
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
            <td class="text-center">{{$v->no_pks}}</td>
            <td class="text-center">{{($v->tgl_pks!='' ? date('d/m/Y',strtotime($v->tgl_pks)) : '')}}</td>
            <td class="text-center">{{($v->masa_berlaku!='' ? date('d/m/Y',strtotime($v->masa_berlaku)) : '')}}</td>
            <td class="text-center">{{$v->keterangan}}</td>
            <td class="text-center" style="width:90px;">
                <a href="javascript:addtindaklanjutrincian({{$v->id}},'nonsetoranperjanjiankerjasama')" class="btn-delete btn btn-xs btn-info"><i class="glyphicon glyphicon-plus"></i></a>&nbsp;
                <a href="javascript:listtindaklanjutrincian({{$v->id}},'nonsetoranperjanjiankerjasama')" class="btn-edit btn btn-xs btn-success"><i class="glyphicon glyphicon-list"></i></a>
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