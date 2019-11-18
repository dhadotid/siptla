@extends('backend.layouts.master')

@section('title')
    <title>Data User</title>
@endsection
@section('modal')
	<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:70%">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Tambah Data Pengguna</h4>
				</div>
				<div class="modal-body">
					<form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select name="level" class="form-control">
                                        <option value="">-- Pilih Level --</option>
                                        <option value="1">Administrator</option>
                                        <option value="2">Operator</option>
                                        <option value="3">Admin OPD</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input name="name" type="text" class="form-control" placeholder="Nama">
                                </div>
                                <div class="form-group">
                                    <input name="email" type="text" class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <input name="password" type="password" class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input name="password_confirmation" type="password" class="form-control" placeholder="Konfirmasi Password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select name="dinas_id" class="form-control" placeholder="OPD">
                                        <option value="">-- Pilih OPD --</option>
                                        @foreach ($dinas as $opd)
                                            <option value="{{$opd->id}}">{{$opd->nama_dinas}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input name="nip" type="text" class="form-control" placeholder="NIP">
                                </div>
                                <div class="form-group">
                                    <input name="pangkat" type="text" class="form-control" placeholder="Pangkat">
                                </div>
                                <div class="form-group">
                                    <input name="golongan" type="text" class="form-control" placeholder="Golongan">
                                </div>
                                <div class="form-group">
                                    <input name="jabatan" type="text" class="form-control" placeholder="Jabatan">
                                </div>
                                <div class="form-group">
                                    <select name="flag" class="form-control">
                                        <option value="">-- Status Flag --</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
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

	<div class="modal fade" id="modalubah" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:70%">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Ubah Data Pengguna</h4>
				</div>
				<div class="modal-body">
					<form id="form-update" method="POST">
						@csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select name="level" class="form-control" id="level">
                                        <option value="">-- Pilih Level --</option>
                                        <option value="1">Administrator</option>
                                        <option value="2">Operator</option>
                                        <option value="3">Admin OPD</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input id="name" name="name" type="text" class="form-control" placeholder="Nama">
                                </div>
                                <div class="form-group">
                                    <input id="email" name="email" type="text" class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                                    <span class="help-block">* Silahkan kosongkan jika tidak mengubah password</span>
                                </div>
                                <div class="form-group">
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Konfirmasi Password">
                                    <span class="help-block">* Silahkan kosongkan jika tidak mengubah password</span>							
                                </div>
                            </div>
                            <div class="col-md-6">
                                
                                <div class="form-group">
                                    <select id="dinas_id" name="dinas_id" class="form-control" placeholder="OPD">
                                        <option value="">-- Pilih OPD --</option>
                                        @foreach ($dinas as $opd)
                                            <option value="{{$opd->id}}">{{$opd->nama_dinas}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input id="nip" name="nip" type="text" class="form-control" placeholder="NIP">
                                </div>
                                <div class="form-group">
                                    <input id="pangkat" name="pangkat" type="text" class="form-control" placeholder="Pangkat">
                                </div>
                                <div class="form-group">
                                    <input id="golongan" name="golongan" type="text" class="form-control" placeholder="Golongan">
                                </div>
                                <div class="form-group">
                                    <input id="jabatan" name="jabatan" type="text" class="form-control" placeholder="Jabatan">
                                </div>
                                <div class="form-group">
                                    <select id="flag" name="flag" class="form-control">
                                        <option value="">-- Status Flag --</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
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

	<div class="modal fade" id="modalhapus" tabindex="-1" role="dialog">
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
				<a href="" class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modaltambah">+ Tambah Data</a>
			</header><!-- .widget-header -->
			<hr class="widget-separator">
			<div class="widget-body">
				<div class="table-responsive">
					<table id="table" data-plugin="DataTable" class="table table-striped" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th style="width:15px;">#</th>
								<th>NIP</th>
								<th>Nama</th>
								<th>Email</th>
								<th>OPD</th>
								<th>Hak Akses</th>
								<th>Flag</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($users as $key => $us)
								<tr>
									<td>{{ $key = $key + 1 }}</td>
									<td>{{ $us->nip }}</td>
									<td>{{ $us->name }}</td>
									<td>{{ $us->email }}</td>
									<td>{{ isset($us->user->dinas_id) ? $us->user->dinas->nama_dinas : '-' }}</td>
									
									@switch($us->level)
										@case(1)
											<td>Administrator</td>
										@break
										@case(2)
											<td>Operator</td>
										@break
										@case(3)
											<td>Admin OPD</td>
										@break
									@endswitch
                                    <td>
                                        @if ($us->flag==1)
                                            <span class="label label-primary">Aktif</span>
                                        @else
                                            <span class="label label-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
									<td>
										<a class="btn btn-xs btn-warning btn-edit" data-toggle="modal" data-target="#modalubah" data-value="{{ $us->id }}" style="height:24px !important;">
											<i class="fa fa-edit"></i>
										</a>
										<a href="#" class="btn btn-xs btn-danger btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="{{ $us->id }}" style="height:24px !important;">
											<i class="fa fa-trash"></i>
										</a>
									</td>
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
		// binding data to modal edit
        $('#table').on('click', '.btn-edit', function(){
            var id = $(this).data('value')
			
            $.ajax({
                url: "{{ url('users') }}/"+id+"/edit",
                success: function(res) {
					$('#form-update').attr('action', "{{ url('users') }}/"+id)

					$('#name').val(res.name)
					$('#nip').val(res.nip)
					$('#email').val(res.email)
					$('#password').val(res.password)
					$('#password_confirmation').val(res.password)
					$('#level').val(res.level)
					$('#flag').val(res.flag)
					$('#dinas_id').val(res.user.dinas_id)
                    $('#pangkat').val(res.pangkat)
					$('#golongan').val(res.golongan)
					$('#jabatan').val(res.jabatan)
                }
            })
        })

		// delete action
        $('#table').on('click', '.btn-delete', function(){
            var id = $(this).data('value')
			$('#form-delete').attr('action', "{{ url('users') }}/"+id)			
        })
	</script>
@endsection