<table class="table table-bordered" id="table-rincian">
    <thead>
        @php
            $kontribusi=['Tindak Lanjut','Nilai (Rp)','Tanggal','Jenis Setoran','Bank Tujuan','No.Ref','Jenis Rekening','Dokumen Pendukung'];
            $penutupanrekening=['Nama Bank','Nomor Rekening','Nama Rekening','Jenis Rekening','Saldo Akhir','Dokumen Pendukung'];
            $umum=['Unit Kerja','Keterangan','Nilai Rekomendasi (Rp) (Rp)','Dokumen Pendukung'];
            $sewa=['tindak_lanjut_rincian'=>'Tindak Lanjut Rincian','nilai'=>'Nilai (Rp)','tanggal'=>'Tanggal Bayar','jenis_setoran'=>'Jenis Setoran','bank_tujuan'=>'Bank Tujuan','no_referensi'=>'No. Ref','jenis_rekening'=>'Jenis Rekening','dokumen_pendukung'=>'Dokumen Pendukung '];
            $uangmuka=['tindak_lanjut_rincian'=>'Tindak Lanjut Rincian','nilai'=>'Nilai (Rp)','tanggal'=>'Tanggal Bayar','jenis_setoran'=>'Jenis Setoran','bank_tujuan'=>'Bank Tujuan','no_referensi'=>'No. Ref','jenis_rekening'=>'Jenis Rekening','dokumen_pendukung'=>'Dokumen Pendukung '];
            $listrik=$piutang=$piutangkaryawan=$hutangtitipan=['tanggal'=>'Tanggal Bayar','nilai'=>'Jumlah Pembayaran (Rp)','dokumen_pendukung'=>'Dokumen Pendukung '];
        @endphp
        <tr class="inverse">
            <th class="text-center">No</th>
            @foreach (${$jenis} as $item)
                <th class="text-center">{{$item}}</th>
            @endforeach

        </tr>
    </thead>
    <tbody>
        @foreach($rinciantindaklanjut as $k=>$v)
            <tr>
                <td class="text-center">{{$k+1}}</td>
                @foreach (${$jenis} as $key=>$item)
                    @if ($key=='nilai')
                        <td class="text-right">{{ rupiah(($v->{$key})) }}</td>
                    @elseif ($key=='dokumen_pendukung')
                        <td class="text-center">
                            <a href="{{url('read-pdf/'.$v->dokumen_pendukung)}}" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-search"></i></a>
                        </td>
                    @elseif ($key=='tanggal')
                        <td class="text-center">
                            <i class="fa fa-calendar"></i> {{tgl_indo(($v->{$key}))}}
                        </td>
                    @else
                        <td class="text-left">{{ ($v->{$key}) }}</td>    
                    @endif
                    
                @endforeach
            </tr>
        @endforeach   
    </tbody>
 </table>