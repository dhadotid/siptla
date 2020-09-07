<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
    </div>
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-12 text-center">
            <h5>
            {{$title}}<br>
            </h5>
        </div>
    </div>
    <hr>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" style="width:150%">
		<thead>
			<tr class="primary">
                <th class="text-center" colspan="4">Rincian Nilai Tindak Lanjut</th>
                <th class="text-center" colspan="7">Tindak Lanjut Unit Kerja</th>
                <th class="text-center" colspan="4">Hasil Pemantauan Tindak Lanjut</th>
            </tr>
            <tr class="primary">
                <th class="text-center" style="width:15px;">#</th>
                <th class="text-center">PIC 1</th>
                <th class="text-center">PIC 2</th>
                <th class="text-center">Keterangan</th>
                <th class="text-center">Nilai Rekomendasi (Rp.)</th>
                
                <th class="text-center">Tanggal</th>
                <th class="text-center">Deskripsi Tindak Lanjut</th>
                <th class="text-center">Nilai Tindak Lanjut (Rp)</th>
                <th class="text-center">Jenis Setoran</th>
                <th class="text-center">Nama Bank Tujuan</th>
                <th class="text-center">Jenis Rekening</th>
                <th class="text-center">Ref (NTPN atau Nomor Rekening Bank)</th>
                @foreach($statusrekom as $v)
                    <th class="text-center">{{$v->rekomendasi}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
        {{-- 
            @php
                $no=1;
            @endphp
            @foreach($twSPI as $v)
                <tr>
                    <td class="text-center">{{$no}}</td>
                    <td class="text-left">{{$v['triwulan']}}</td>
                    <td class="text-left">{{$v['ss']}}</td>
                    <td class="text-left">{{$v['tdd']}}</td>
                    <td class="text-right">{{$v['bs']}}</td>
                    <td class="text-right">{{$v['btl']}}</td>
                    <td class="text-right">{{$v['tot']}}</td>
                    <td class="text-right">{{$v['percentage']}}</td>
                </tr> 
               
                @php
                    $no++;
                @endphp
            @endforeach
            --}}
        </tbody>
    </table>
</div>
<script>
    $('#table').DataTable();
    $('[data-toggle="tooltip"]').tooltip();
</script>