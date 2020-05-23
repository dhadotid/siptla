<table class="table table-bordered" id="table-rincian-dokumen">
    <thead>
    <!-- Umum -> ['tanggal'=>'Tanggal Bayar','nilai'=>'Jumlah Pembayaran (Rp)','jenis_setoran'=>'Jenis Setoran','dokumen_pendukung'=>'Dokumen Pendukung'];
        Kontribusi -> =['tindak_lanjut_rincian'=>'Tindak Lanjut','nilai'=>'Nilai (Rp)','tanggal'=>'Tanggal','jenis_setoran'=>'Jenis Setoran','bank_tujuan_name'=>'Bank Tujuan','no_referensi'=>'No.Ref','jenis_rekening'=>'Jenis Rekening','dokumen_pendukung'=>'Dokumen Pendukung'];
        Sewa -> ['tindak_lanjut_rincian'=>'Tindak Lanjut Rincian','nilai'=>'Nilai (Rp)','tanggal'=>'Tanggal Bayar','jenis_setoran'=>'Jenis Setoran','bank_tujuan_name'=>'Bank Tujuan','no_referensi'=>'No. Ref','jenis_rekening'=>'Jenis Rekening','dokumen_pendukung'=>'Dokumen Pendukung '];
     -->
        @php
            $nonsetoranperjanjiankerjasama=['tanggal'=>'Tanggal','tindak_lanjut_rincian'=>'Deskripsi Tindak Lanjut','no_pks'=>'No. PKS','tanggal_pks'=>'Tanggal PKS', 'periode_pks'=>'Periode Perpanjangan','dokumen_pendukung'=>'Dokumen Pendukung'];
            $penutupanrekening=['tanggal_penutupan'=>'Tanggal Penutupan Rekening','saldo_akhir'=>'Saldo Akhir','no_rek_pemindah_saldo'=>'No. Rekening Pemindahan Saldo','nama_rekening_pemindah_saldo'=>'Nama Rekening Pemindahan Saldo','dokumen_pendukung'=>'Dokumen Pendukung'];
            $nonsetoranpertanggungjawabanuangmuka=$nonsetoran=['tanggal'=>'Tanggal Bayar', 'tindak_lanjut_rincian'=>'Deskripsi Tindak Lanjut','nilai'=>'Nilai Tindak Lanjut (Rp)','dokumen_pendukung'=>'Dokumen Pendukung '];
            $kontribusi=$uangmuka=$piutangkaryawan=$listrik=['tanggal'=>'Tanggal Bayar','tindak_lanjut_rincian'=>'Deskripsi Tindak Lanjut','nilai'=>'Nilai (Rp)','jenis_setoran'=>'Jenis Setoran','bank_tujuan_name'=>'Bank Tujuan','jenis_rekening'=>'Jenis Rekening','no_referensi'=>'No. Ref','dokumen_pendukung'=>'Dokumen Pendukung'];
            $sewa=$umum=$piutang=$hutangtitipan=['tanggal'=>'Tanggal Bayar','tindak_lanjut_rincian'=>'Deskripsi Tindak Lanjut','nilai'=>'Nilai (Rp)','jenis_setoran'=>'Jenis Setoran','bank_tujuan_name'=>'Bank Tujuan','jenis_rekening'=>'Jenis Rekening','no_referensi'=>'No. Ref','dokumen_pendukung'=>'Dokumen Pendukung'];
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
                        @if (is_array(json_decode($v->dokumen_pendukung, true)) || is_object(json_decode($v->dokumen_pendukung)))
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;"><span class="caret"></span></button>&nbsp;
                            <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                @foreach (json_decode($v->dokumen_pendukung, true) as $fileName)
                                    <li><a href="{{url('read-pdf/'.$fileName['file'])}}" target="#" ><i class="fa fa-search">&nbsp;&nbsp;{{ str_replace('public/dokumen/','',$fileName['file'] ) }}</i></a></li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                        @if (Auth::user()->level == 'pic-unit')
                            </div>
                                <a href="javascript:addtindaklanjutrincian({{$rincian->id}},'{{$jenis}}',{{$totalnilai}},'Update Detail Tindak Lanjut', true, {{$v->id}})" class="btn btn-info btn-xs" style="height:28px;"><i class="fa fa-edit"></i></a>&nbsp;
                                <a href="javascript:hapusrinciantindaklanjut({{$rincian->id}}, {{$v->id}}, '{{$jenis}}')" class="btn btn-danger btn-xs" style="height:28px;"><i class="fa fa-trash"></i></a>&nbsp;
                            </td>
                        @else
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;"><span class="caret"></span></button>&nbsp;
                            <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                @foreach($status_rekomendasi as $rekom)
                                    @if($rekom->rekomendasi != 'Belum Ditindaklanjuti (BTL)')
                                        <li><a href="javascript:updatestatusrinciantindaklanjut({{$v->id}},{{$rekom->id}})" >&nbsp;&nbsp;{{ $rekom->rekomendasi }}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    @elseif ($key=='tanggal' || $key=='tanggal_pks' || $key=='tanggal_penutupan')
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