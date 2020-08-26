<div class="table-responsive">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-1">&nbsp;</div>
    </div>
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-12 text-center">
            <h5>
            Laporan Capaian Indikator Kinerja Universitas untuk Penyelesaian Tindak Lanjut Rekomendasi Audit<br>
            Universitas Indonesia<br>
            Tahun <span style="font-weight: bold;" id="tahun">{{$tahun}}</span><br>
            Level Resiko <span style="font-weight: bold;" id="levelresiko">{{$levelresiko}}</span><br>
            Bidang <span style="font-weight: bold;" id="bidang">{{$bidang}}</span> 
            Unit Kerja <span style="font-weight: bold;" id="unitkerja">{{$unitkerja}}</span><br>
            </h5>
        </div>
    </div>
    @if($showreport == 'all' || $showreport == 'spi')
    <hr>
    <h5>Persentase Penyelesaian rekomendasi audit Internal (SPI) </h5>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" style="width:150%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;" rowspan="2">#</th>
                <th class="text-center" rowspan="2">Triwulan</th>
                <th class="text-center" colspan="2">Sudah Selesai</th>
                <th class="text-center" rowspan="2">{{$statusrekom[1]->rekomendasi}}</th>
                <th class="text-center" rowspan="2">{{$statusrekom[2]->rekomendasi}}</th>
                <th class="text-center" rowspan="2">Total</th>
				<th class="text-center" rowspan="2">% Selesai</th>
            </tr>
            <tr class="primary">
                <th class="text-center">{{$statusrekom[0]->rekomendasi}}</th>
                <th class="text-center">{{$statusrekom[3]->rekomendasi}}</th>
            </tr>
        </thead>
        <tbody>
        
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
            
        </tbody>
    </table>
    @endif
    @if($showreport == 'all' || $showreport == 'exspi')
    <hr>
    <h5>Persentase Penyelesaian rekomendasi audit Esktenal (BPK, BPKP, Itjen, KAP)</h5>
	<table id="table" class="table table-striped table-bordered" cellspacing="0" style="width:150%">
		<thead>
			<tr class="primary">
				<th class="text-center" style="width:15px;" rowspan="2">#</th>
                <th class="text-center" rowspan="2">Triwulan</th>
                <th class="text-center" colspan="2">Sudah Selesai</th>
                <th class="text-center" rowspan="2">{{$statusrekom[1]->rekomendasi}}</th>
                <th class="text-center" rowspan="2">{{$statusrekom[2]->rekomendasi}}</th>
                <th class="text-center" rowspan="2">Total</th>
				<th class="text-center" rowspan="2">% Selesai</th>
            </tr>
            <tr class="primary">
                <th class="text-center">{{$statusrekom[0]->rekomendasi}}</th>
                <th class="text-center">{{$statusrekom[3]->rekomendasi}}</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach($twNonSPI as $i=>$v)
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
        </tbody>
    </table>
    @endif
</div>
<script>
    // $('#table').DataTable();
    $('[data-toggle="tooltip"]').tooltip();
</script>