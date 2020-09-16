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
                <th class="text-center" colspan="9">Rincian Nilai Tindak Lanjut</th>
                <th class="text-center" colspan="7">Tindak Lanjut Unit Kerja</th>
                <th class="text-center" colspan="4">Hasil Pemantauan Tindak Lanjut</th>
            </tr>
            <tr class="primary">
                <th class="text-center" style="width:15px;">#</th>
                <th class="text-center">PIC 1</th>
                <th class="text-center">PIC 2</th>
                <th class="text-center">Jenis Rekening</th>
                <th class="text-center">Nama Bank</th>
                <th class="text-center">No. Rekening</th>
                <th class="text-center">Nama Rekening</th>
                <th class="text-center">Mata Uang</th>
                <th class="text-center">Saldo Temuan</th>
                
                <th class="text-center">Tanggal</th>
                <th class="text-center">Deskripsi Tindak Lanjut</th>
                <th class="text-center">Tanggal Penutupan Rekening</th>
                <th class="text-center">Saldo Akhir Rekening Yang Ditutup</th>
                <th class="text-center">Nomor Rekening Pemindahan Saldo</th>
                <th class="text-center">Nama Rekening Pemindahan Saldo</th>
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