@extends('backend.layouts.master')

@section('title')
    <title>Data User</title>
@endsection
@section('modal')
	<div class="modal fade" id="modaltambah"  role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Tambah Data Pengguna</h4>
				</div>
				<div class="modal-body">
					<form action="{{ route('pengguna.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
									<label>Jenis Level</label>
                                    <select name="level" class="form-control select2" onchange="addjenis(this.value)">
										<option value="">-- Pilih Level --</option>
                                        @php
											foreach($jenislevel as $k=>$v)
											{
												if($k=='administrator')
													echo '<option value="0">'.$v.'</option>';	
												else
													echo '<option value="'.$k.'">'.$v.'</option>';	
											}
										@endphp
                                    </select>
                                </div>
                                <div class="form-group" id="add-name-txt" style="">
									<label>Nama Pengguna</label>
                                    <input name="name" type="text" class="form-control" placeholder="Nama Pengguna" id="addname">
                                </div>
                                <div class="form-group" id="add-name-pic" style="display:none">
									<label>PIC Unit</label>
                                    <select name="name_pic" class="form-control select2">
                                        <option value="">-- Pilih PIC Unit --</option>
                                        @php
											foreach($picunit as $k=>$v)
											{
												echo '<option value="'.$v->id.'__'.$v->nama_pic.'">'.$v->nama_pic.'</option>';	
											}
										@endphp
                                    </select>
                                </div>
								<div class="form-group" id="add-name-bidang" style="display:none">
									<label>Bidang</label>
                                    <select name="name_bidang" class="form-control select2">
                                        <option value="">-- Pilih Bidang --</option>
                                        @php
											foreach($bidang as $k=>$v)
											{
												echo '<option value="'.$v['id'].'__'.$v['category'].'__'.$v['name'].'">'.$v['name'].'</option>';	
											}
										@endphp
                                    </select>
                                </div>
                                <div class="form-group">
									<label>Email</label>
                                    <input name="email" type="text" class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group">
									<label>Telepon</label>
                                    <input name="telepon" type="text" class="form-control" placeholder="Telepon">
                                </div>
                                <div class="form-group">
									<label>Password</label>
                                    <input name="password" type="password" class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
									<label>Password Konfirmasi</label>
                                    <input name="password_confirmation" type="password" class="form-control" placeholder="Konfirmasi Password">
                                </div>
								<div class="form-group">
									<label>Flag Pengguna</label>
									<select name="flag" class="form-control">
										<option value="">-- Status Flag --</option>
										<option value="1">Aktif</option>
										<option value="0">Tidak Aktif</option>
									</select>
								</div>
								<input type="hidden" id="csrf_token" name="csrf_token" value="{{csrf_token()}}">
								<div class="form-group">
									<label>Surat Tugas</label>
									<input type="file" class="form-control" onchange="#" id="surat_tugas"  name="surat_tugas"  placeholder="Surat Tugas" accept=".doc,.docx,.pdf,.xls,.xlsx">
								</div>
                            </div>
                        </div>
						
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
					<input type="submit" class="btn btn-success" value="Simpan">
				</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalubah"  role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Ubah Data Pengguna</h4>
				</div>
				<div class="modal-body">
					<form id="form-update" method="POST" enctype="multipart/form-data">
						@csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
									<label>Jenis Level</label>
                                    <select class="form-control" id="level-edit" disabled>
                                        <option value="">-- Pilih Level --</option>
                                        @php
											foreach($jenislevel as $k=>$v)
											{
												if($k=='administrator')
													echo '<option value="0">'.$v.'</option>';	
												else
													echo '<option value="'.$k.'">'.$v.'</option>';	
											}
										@endphp
									</select>
									<input type="hidden" name="level" id="level">
                                </div>
                                <div class="form-group" id="edit-name-txt" style="">
									<label>Nama Pengguna</label>
                                    <input name="name" type="text" class="form-control" placeholder="Nama Pengguna" id="name">
                                </div>
                                <div class="form-group" id="edit-name-pic" style="display:none">
									<label>PIC Unit</label>
                                    <select name="" class="form-control" id="pic-edit" disabled>
                                        <option value="">-- Pilih PIC Unit --</option>
                                        @php
											foreach($picunit as $k=>$v)
											{
												echo '<option value="'.$v->id.'__'.$v->nama_pic.'">'.$v->nama_pic.'</option>';	
											}
										@endphp
									</select>
									<input type="hidden" name="name_pic" id="name_pic">
                                </div>
								<div class="form-group" id="edit-name-bidang" style="display:none">
									<label>Bidang</label>
                                    <select name="" id="bidang-edit" class="form-control" disabled>
                                        <option value="">-- Pilih Bidang --</option>
                                        @php
											foreach($bidang as $k=>$v)
											{
												echo '<option value="'.$v['id'].'__'.$v['category'].'__'.$v['name'].'">'.$v['name'].'</option>';	
											}
										@endphp
                                    </select>
									<input type="hidden" name="name_bidang" id="name_bidang">
                                </div>

                                <div class="form-group">
									<label>Email</label>
                                    <input name="email" type="text" class="form-control" placeholder="Email" id="email">
								</div>
								 <div class="form-group">
									<label>Telepon</label>
                                    <input name="telepon" type="text" class="form-control" placeholder="Telepon" id="telepon">
                                </div>
                                <div class="form-group">
									<label>Password</label>
                                    <input name="password" type="password" class="form-control" placeholder="Password" id="password">
                                </div>
                                <div class="form-group">
									<label>Password Konfirmasi</label>
                                    <input name="password_confirmation" type="password" class="form-control" placeholder="Konfirmasi Password" id="password_confirmation">
                                </div>
								<div class="form-group">
									<label>Flag Pengguna</label>
									<select name="flag" class="form-control" id="flag">
										<option value="">-- Status Flag --</option>
										<option value="1">Aktif</option>
										<option value="0">Tidak Aktif</option>
									</select>
								</div>
								<input type="hidden" id="csrf_token" name="csrf_token" value="{{csrf_token()}}">
								<div class="form-group">
									<div class="field_wrapper_surat_tugas">
										<label>Surat Tugas</label>
									</div>
									<input type="file" class="form-control" onchange="#" id="surat_tugas"  name="surat_tugas"  placeholder="Surat Tugas" accept=".doc,.docx,.pdf,.xls,.xlsx">
								</div>
                            </div>
                        </div>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
					<input type="submit" class="btn btn-success" value="Simpan Perubahan">
				</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalhapus"  role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Konfirmasi Hapus Data Pengguna</h4>
				</div>
				<div class="modal-body">
					<h5>Apakah anda yakin akan menghapus data ini?</h5>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
					<a class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('form-delete').submit();" style="cursor:pointer;">Ya, Saya Yakin</a>
					<form id="form-delete" method="POST" style="display: none;">
						@csrf
						@method('DELETE')
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('content')
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<span class="widget-title">Data Pengguna</span>
				
				@if (Auth::user()->level=='0')
				<a href="" class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modaltambah">+ Tambah Data</a><br><br>
				@endif
				@if ($level!=null)
					<select name="sel-level" class="form-control" style="width:300px;float:right;" onchange="location.href='{{url('pengguna')}}/'+this.value">
						@foreach ($jenislevel as $item)
							@if (str_slug($item)==$level)
								<option value="{{str_slug($item)}}" selected="selected">Level : {{$item}}</option>
							@else
								<option value="{{str_slug($item)}}">Level : {{$item}}</option>
							@endif
						@endforeach		
					</select>
				@endif
			</header><!-- .widget-header -->
			<hr class="widget-separator">
			<div class="widget-body">
				
				@if ($errors->any())
					<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						<strong>Alert ! </strong>
						<span>
							<ul>
							@foreach ($errors->all() as $item)
								<li>- {{$item}}</li>
							@endforeach
							</ul>
							
						</span>
					</div>	
				@endif
				@if (Session::has('success'))
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						<strong>Berhasil! </strong>
						<span>{!!Session::get('success')!!}</span>
					</div>
				@endif
				<div class="table-responsive">
					<table id="table" data-plugin="DataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th style="width:15px;">#</th>
								<th>Nama Pengguna</th>
								<th>Email</th>
								<th>Telp</th>
								<th>Jenis Level</th>
								<th>Surat Tugas</th>
								<th>Flag</th>
								@if (Auth::user()->level=='0')
								<th>Aksi</th>
								@endif
							</tr>
						</thead>
						<tbody>
							@foreach ($users as $key => $us)
								<tr>
									<td>{{ $key = $key + 1 }}</td>
									<td>{{ $us->name }}</td>
									<td>{{ $us->email }}</td>
									<td>{{ $us->telepon }}</td>
									
									@if($us->level=='0')
										<td>Administrator</td>
									@else
										@if (isset($jenislevel[$us->level]))
											<td>{{$jenislevel[$us->level]}}</td>
										@else
											<td>-</td>
										@endif
									@endif
									
									<td>
									@if($us->surat_tugas=='')
										-
									@else
										<a href="{{url('read-pdf/'.$us->surat_tugas)}}" target="#" ><i class="fa fa-search">&nbsp;&nbsp;{{ str_replace('public/dokumen/','',$us->surat_tugas ) }}</i></a>
									@endif
									</td>

                                    <td>
                                        @if ($us->flag==1)
                                            <span class="label label-primary">Aktif</span>
                                        @else
                                            <span class="label label-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
									@if (Auth::user()->level=='0')
									<td>
										<a class="btn btn-xs btn-warning btn-edit" data-toggle="modal" data-target="#modalubah" data-value="{{ $us->id }}" style="height:24px !important;">
											<i class="fa fa-edit"></i>
										</a>
										<a href="#" class="btn btn-xs btn-danger btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="{{ $us->id }}" style="height:24px !important;">
											<i class="fa fa-trash"></i>
										</a>
									</td>
									@endif
								</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div><!-- .widget-body -->
		</div><!-- .widget -->
	</div>
@endsection

@section('footscript')
	<script>
		setTimeout(function(){
			$('.alert').fadeOut();
		},3000);
		$('.select2').select2();
		// binding data to modal edit
        $('#table').on('click', '.btn-edit', function(){
            var id = $(this).data('value')
			
            $.ajax({
                url: "{{ url('pengguna') }}/"+id+"/edit",
                success: function(res) {
					$('#form-update').attr('action', "{{ url('pengguna') }}/"+id)
					$('#name').val(res.name)
					$('#name_pic').val(res.picunit)
					$('#pic-edit').val(res.picunit)
					$('#bidang-edit').val(res.bidang)
					$('#name_bidang').val(res.bidang)
					$('#nip').val(res.nip)
					$('#email').val(res.email)
					$('#telepon').val(res.telepon)
					$('#password').val(res.password)
					$('#password_confirmation').val(res.password)
					$('#level').val(res.level)
					$('#level-edit').val(res.level)
					$('#flag').val(res.flag)
					console.log('jajaja '+res.surat_tugas)
					if(res.level=='pic-unit')
					{
						$('#edit-name-txt').css('display','none');
						$('#edit-name-pic').css('display','block');
						$('#edit-name-bidang').css('display','none');
					}else if(res.level == 'pimpinan-kepala-bidang'){
						$('#edit-name-txt').css('display','block');
						$('#edit-name-bidang').css('display','block');
						$('#edit-name-pic').css('display','none');
					}else{
						$('#edit-name-txt').css('display','block');
						$('#edit-name-pic').css('display','none');
						$('#edit-name-bidang').css('display','none');
					}
					$('.select2').select2().trigger('change');

					var wrapper = $('.field_wrapper_surat_tugas');
					var fieldHTML = '&nbsp;&nbsp;<a href="{{url('read-pdf/')}}/'+res.surat_tugas+'" target="_blank" ><i class="fa fa-search">&nbsp;&nbsp;'+res.surat_tugas.replace('public/dokumen/', '')+'</i></a>';
					$(wrapper).append(fieldHTML);
				}
	
            })
        })

		// delete action
        $('#table').on('click', '.btn-delete', function(){
            var id = $(this).data('value')
			$('#form-delete').attr('action', "{{ url('pengguna') }}/"+id)			
        })

		function addjenis(val)
		{
			if(val=='pic-unit')
			{
				$('#add-name-txt').css('display','none');
				$('#add-name-pic').css('display','block');
				$('#add-name-bidang').css('display','none');
				$('#addname').val('');
			}else if(val == 'pimpinan-kepala-bidang'){
				$('#add-name-txt').css('display','block');
				$('#add-name-bidang').css('display','block');
				$('#add-name-pic').css('display','none');
				$('#addname').val('');
			}else{
				$('#add-name-txt').css('display','block');
				$('#add-name-pic').css('display','none');
				$('#add-name-bidang').css('display','none');
			}
		}
		function editjenis(val)
		{
			if(val=='pic-unit')
			{
				$('#edit-name-txt').css('display','none');
				$('#edit-name-pic').css('display','block');
				$('#edit-name-bidang').css('display','none');
				$('#name').val('');
			}else if(val == 'pimpinan-kepala-bidang'){
				$('#edit-name-txt').css('display','block');
				$('#edit-name-bidang').css('display','block');
				$('#edit-name-pic').css('display','none');
				$('#name').val('');
			}else{
				$('#edit-name-txt').css('display','block');
				$('#edit-name-pic').css('display','none');
				$('#edit-name-bidang').css('display','none');
			}
		}
	</script>
	<style>
	.select2-container{
		width:100% !important;
	}
	</style>
@endsection