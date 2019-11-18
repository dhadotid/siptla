
<table id="table" class="table table-striped table-bordered" width="100%">
	<thead>
		<tr>
			<th class="text-center"  style="width:15px;">#</th>
			<th class="text-center" >Bidang Pengawasan<br>No & Tgl LHP</th>
			<th class="text-center" >Nama Dinas</th>
			{{-- <th class="text-center" >Temuan / Penyebab<br>(Uraian Ringkas)</th>
			<th class="text-center" >Kode Temuan</th>
			<th class="text-center" >Rekomendasi<br>(Uraian Ringkas)</th>
			<th class="text-center" >Kode Rekomendasi</th> --}}
			<th class="text-center">Detail</th>
			<th class="text-center" >Harus Selesai<br>Tanggal</th>
			<th class="text-center" >Aksi</th>
			
        </tr>
	</thead>
	<tbody>
        @php
            $no=1;
        @endphp
		@foreach ($daftar as $keydaftar => $uss)
            <tr>
                <td class='text-center'>{{$no}}</td>
                <td>
						{{$uss->pengawasan->bidang}}
						<br><br>
						No : {{$uss->no_pengawasan}}<br>
						Tgl : {{date('d/m/Y',strtotime($uss->tgl_pengawasan))}}<br>
				
                </td>
                <td><b>{{$uss->dinas->nama_dinas}}</b></td>
                <td class="text-center"><span class="label label-info">{{isset($det[$uss->id]) ? count($det[$uss->id]) : 0}} Detail</span></td>
                <td class="text-center">
					@php
						$tglbatas=adddate($uss->tgl_pengawasan,60);
						$selisih=selisihhari(date('Y-m-d'),$tglbatas,0);
						// echo $selisih;
					@endphp
					@if ($selisih<=30)
						{{date('d-m-Y',strtotime($tglbatas))}} <br>
						<i>({{$selisih}} Hari Lagi)</i>
					@else
						{{date('d-m-Y',strtotime($tglbatas))}} <br>
						<i>({{$selisih}} Hari Lagi)</i>
					@endif
					
                </td>
                
                <td class="text-center">
                    <div style="width:120px">
                            <a class="btn btn-xs btn-success btn-edit" href="{{url('temuan/'.$uss->id)}}" style="height:24px !important;">
                                <i class="fa fa-eye"></i>
                            </a>
                            @if (Auth::user()->level!=3)
                                @if (Auth::user()->level==2)
                                    <a class="btn btn-xs btn-info btn-edit" href="{{url('temuan/'.$uss->id.'/edit')}}" style="height:24px !important;">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif
                                @if (Auth::user()->level==1)
                                    
                                @endif
                                
                                <a href="javascript:hapusdaftar({{$uss->id}})" class="btn btn-xs btn-danger btn-delete" style="height:24px !important;">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif
                                
                        </div>
                    </td>
            </tr>
            @php
                $no++;
            @endphp
	    @endforeach
	</tbody>
</table>