@if (Auth::user()->level!=1 && Auth::user()->level!=3)
	<header class="widget-header" style="margin-top:0px !important;padding-top:0px !important;">
		<a href="{{url('detail-form/'.(isset($daftar[0]->id) ? $daftar[0]->id : '-1').'/'.$dinas_id.'/'.$tahun.'/'.$bidang_id)}}" class="btn btn-success btn-xs pull-right">+ Tambah Detail</a>
	</header>
@endif
<table id="table" class="table table-striped table-bordered" width="100%">
	<thead>
		<tr>
			<th class="text-center"  style="width:15px;">#</th>
			<th class="text-center" >Bidang Pengawasan<br>No & Tgl LHP</th>
			<th class="text-center" >Temuan / Penyebab<br>(Uraian Ringkas)</th>
			<th class="text-center" >Kode Temuan</th>
			<th class="text-center" >Rekomendasi<br>(Uraian Ringkas)</th>
			<th class="text-center" >Kode Rekomendasi</th>
			@if (Auth::user()->level==3)
				<th class="text-center" >Tindak Lanjut</th>
			@else
				<th class="text-center" >Status</th>
			@endif
			<th class="text-center" >Harus Selesai<br>Tanggal</th>
			@if (Auth::user()->level!=3)
			<th class="text-center" >Aksi</th>
			@endif
        </tr>
	</thead>
	<tbody>
		@foreach ($daftar as $keydaftar => $uss)
			@if (isset($det[$uss->id]))
			
				@foreach ($det[$uss->id] as $key=>$us)
					@if (Auth::user()->level==3)
						@if ($us->flag!=0)
							<tr>
								<td>{{ $keydaftar = $keydaftar + 1 }}</td>
								<td>
									{{-- @if ($key==1) --}}
										{{$uss->pengawasan->bidang}}
										<br><br>
										No : {{$uss->no_pengawasan}}<br>
										Tgl : {{date('d/m/Y',strtotime($uss->tgl_pengawasan))}}<br>
									{{-- @endif --}}
								</td>
								<td>
									{!! $us->uraian_temuan !!}
								</td>
								<td class="text-center">{{$us->temuan->code}}</td>
								<td>{!! $us->uraian_rekomendasi !!}</td>
								<td class="text-center">{{$us->rekomendasi->code}}</td>
								<td class="text-left">
										
									{{-- * Status Temuan:
									* 0 : Menunggu Verifikasi
									* 2 : Belum Tindak Lanjut
									* 3 : Telah Tindak Lanjut
									* 4 : Selesai
										--}}

									@if (Auth::user()->level==1)
										@if ($us->flag==0)
											<span class="text-danger">Menunggu Verifikasi</span>
										@elseif ($us->flag==2)
											<span>Telah Diverifikasi</span><br>
											<span class="text-danger"><i>(Belum Ditindaklanjuti)</i></span>
										@elseif ($us->flag==3)
											<a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}" style="color:green;">Telah Ditindaklanjuti</a>
										@else
											<span class="text-success">Proses Selesai</span> <br>
											<a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}"><i>(Lihat Detail)</i></a>
										@endif
									@elseif(Auth::user()->level==2)
										@if ($us->flag==0)
											<span class="text-danger">Menunggu Verifikasi</span>
										@elseif ($us->flag==2)
											<span>Telah Diverifikasi</span><br>
											<span class="text-danger"><i>(Belum Ditindaklanjuti)</i></span>
										@elseif ($us->flag==3)
											<a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}" style="color:green;">Telah Ditindaklanjuti</a>
										@else
											<span class="text-success">Proses Selesai</span> <br>
											<a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}"><i>(Lihat Detail)</i></a>
										@endif
									@elseif(Auth::user()->level==3)
										@if ($us->flag==0)
											<span>Menunggu Verifikasi</span>
										@elseif ($us->flag==2)
											<a href="{{ route('tindak-lanjut.index', $us->id) }}" style="color:red;">Belum ada tindak lanjut</a>
										@elseif ($us->flag==3)
											<span class="text-green">Telah Ditindaklanjuti</span> <br>
											<a href="{{ route('tindak-lanjut.edit', $us->id) }}"><i>(Ubah Data)</i></a>
										@else
											<span class="text-success">Proses Selesai</span> <br>
											<a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}"><i>(Lihat Detail)</i></a>
										@endif
									@endif
								</td>
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
								@if (Auth::user()->level!=3)
								<td class="text-center">
									<div style="width:80px">
										@if (Auth::user()->level==2)
											<a class="btn btn-xs btn-info btn-edit" href="{{url('list-temuan/'.$us->id.'/edit')}}" style="height:24px !important;">
												<i class="fa fa-edit"></i>
											</a>
										@endif

										@if (Auth::user()->level==1)
											@if ($us->flag==0)
												<a href="javascript:verifikasi({{$us->id}})" class="btn btn-xs btn-info"><i class="fa fa-check"></i></a>
											@else
												<a href="#" class="btn btn-xs btn-default" disabled><i class="fa fa-check"></i></a>
											@endif
										@endif
										
										<a href="javascript:hapusdetail({{$us->id}})" class="btn btn-xs btn-danger btn-delete" style="height:24px !important;">
											<i class="fa fa-trash"></i>
										</a>
										
									</div>
								</td>
								@endif
							</tr>
						@endif
					@else
						<tr>
							<td>{{ $keydaftar = $keydaftar + 1 }}</td>
							<td>
								{{-- @if ($key==1) --}}
									{{$uss->pengawasan->bidang}}
									<br><br>
									No : {{$uss->no_pengawasan}}<br>
									Tgl : {{date('d/m/Y',strtotime($uss->tgl_pengawasan))}}<br>
								{{-- @endif --}}
							</td>
							<td>
								{!! $us->uraian_temuan !!}
							</td>
							<td class="text-center">{{$us->temuan->code}}</td>
							<td>{!! $us->uraian_rekomendasi !!}</td>
							<td class="text-center">{{$us->rekomendasi->code}}</td>
							<td class="text-left">
									
								{{-- * Status Temuan:
								* 0 : Menunggu Verifikasi
								* 2 : Belum Tindak Lanjut
								* 3 : Telah Tindak Lanjut
								* 4 : Selesai
									--}}

								@if (Auth::user()->level==1)
									@if ($us->flag==0)
										<span class="text-danger">Menunggu Verifikasi</span>
									@elseif ($us->flag==2)
										<span>Telah Diverifikasi</span><br>
										<span class="text-danger"><i>(Belum Ditindaklanjuti)</i></span>
									@elseif ($us->flag==3)
										<a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}" style="color:green;">Telah Ditindaklanjuti</a>
									@else
										<span class="text-success">Proses Selesai</span> <br>
										<a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}"><i>(Lihat Detail)</i></a>
									@endif
								@elseif(Auth::user()->level==2)
									@if ($us->flag==0)
										<span class="text-danger">Menunggu Verifikasi</span>
									@elseif ($us->flag==2)
										<span>Telah Diverifikasi</span><br>
										<span class="text-danger"><i>(Belum Ditindaklanjuti)</i></span>
									@elseif ($us->flag==3)
										<a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}" style="color:green;">Telah Ditindaklanjuti</a>
									@else
										<span class="text-success">Proses Selesai</span> <br>
										<a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}"><i>(Lihat Detail)</i></a>
									@endif
								@elseif(Auth::user()->level==3)
									@if ($us->flag==0)
										<span>Menunggu Verifikasi</span>
									@elseif ($us->flag==2)
										<a href="{{ route('tindak-lanjut.index', $us->id) }}" style="color:red;">Belum ada tindak lanjut</a>
									@elseif ($us->flag==3)
										<span class="text-green">Telah Ditindaklanjuti</span> <br>
										<a href="{{ route('tindak-lanjut.edit', $us->id) }}"><i>(Ubah Data)</i></a>
									@else
										<span class="text-success">Proses Selesai</span> <br>
										<a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}"><i>(Lihat Detail)</i></a>
									@endif
								@endif
							</td>
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
							@if (Auth::user()->level!=3)
							<td class="text-center">
								<div style="width:80px">
									@if (Auth::user()->level==2)
										@if ($us->flag==0)
											<a class="btn btn-xs btn-info btn-edit" href="{{url('list-temuan/'.$us->id.'/edit')}}" style="height:24px !important;">
												<i class="fa fa-edit"></i>
											</a>

											<a href="javascript:hapusdetail({{$us->id}})" class="btn btn-xs btn-danger btn-delete" style="height:24px !important;">
												<i class="fa fa-trash"></i>
											</a>
										@else
											<a class="btn btn-xs btn-default" disabled style="height:24px !important;">
												<i class="fa fa-edit"></i>
											</a>

											<a class="btn btn-xs btn-default" disabled style="height:24px !important;">
												<i class="fa fa-trash"></i>
											</a>
										@endif
									@endif

									@if (Auth::user()->level==1)
										@if ($us->flag==0)
											<a href="javascript:verifikasi({{$us->id}})" class="btn btn-xs btn-info"><i class="fa fa-check"></i></a>
										@else
											<a href="#" class="btn btn-xs btn-default" disabled><i class="fa fa-check"></i></a>
										@endif

										<a href="javascript:hapusdetail({{$us->id}})" class="btn btn-xs btn-danger btn-delete" style="height:24px !important;">
											<i class="fa fa-trash"></i>
										</a>
									@endif	
								</div>
							</td>
							@endif
						</tr>
					@endif
				@endforeach
			@endif
	@endforeach
	</tbody>
</table>