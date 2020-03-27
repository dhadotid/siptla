@extends('backend.layouts.master')
@section('title')
    <title>Data Jenis Temuan</title>
@endsection
@section('modal')
	<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Tambah Data Jenis Temuan</h4>
				</div>
				<div class="modal-body">
					<form action="{{ route('jenis-temuan.store') }}" method="POST">
						@csrf
						
						<div class="form-group">
							<input name="temuan" type="text" class="form-control" placeholder="Jenis Temuan">
						</div>
						<div class="form-group">
							<textarea name="desc" class="form-control" placeholder="Keterangan"></textarea>
						</div>
                        <div class="form-group">
							<select name="flag" class="form-control">
								<option value="">-- Pilih --</option>
								<option value="1">Aktif</option>
								<option value="0">Tidak Aktif</option>
								
							</select>
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
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Ubah Data Jenis Temuan</h4>
				</div>
				<div class="modal-body">
					<form id="form-update" method="POST">
						@csrf
						@method('PUT')
						
						<div class="form-group">
							<input id="temuan" name="temuan" type="text" class="form-control" placeholder="Jenis Temuan">
						</div>
						<div class="form-group">
							<textarea id="desc" name="desc" class="form-control" placeholder="Keterangan"></textarea>
						</div>
                        <div class="form-group">
							<select name="flag" class="form-control" id="flag">
								<option value="">-- Pilih --</option>
								<option value="1">Aktif</option>
								<option value="0">Tidak Aktif</option>
								
							</select>
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
					<h4 class="modal-title">Konfirmasi Hapus Data Jenis Temuan</h4>
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
				<span class="widget-title">Data Jenis Temuan</span>
				@if (Auth::user()->level=='0')
					<a href="" class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modaltambah">+ Tambah Data</a>
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
								<li>- {!!$item!!}</li>
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
								<th>Jenis Temuan</th>
								<th>Flag</th>
								<th>Keterangan</th>
								@if (Auth::user()->level=='0')
									<th>Aksi</th>
								@endif
							</tr>
						</thead>
						<tbody>
							@foreach ($temuan as $key => $item)
								<tr>
									<td>{{ $key = $key + 1 }}</td>
									<td>{{ $item->temuan }}</td>
									
									<td>
                                        @if ($item->flag==1)
                                            <span class="label label-primary">Aktif</span>
                                        @else
                                            <span class="label label-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
									<td>{!! $item->desc !!}</td>
									@if (Auth::user()->level=='0')
										<td>
											<a class="btn btn-xs btn-warning btn-edit" data-toggle="modal" data-target="#modalubah" data-value="{{ $item->id }}" style="height:24px !important">
												<i class="fa fa-edit"></i>
											</a>
											<a href="#" class="btn btn-xs btn-danger btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="{{ $item->id }}" style="height:24px !important">
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
                url: "{{ url('jenis-temuan') }}/"+id+"/edit",
                success: function(res) {
					$('#form-update').attr('action', "{{ url('jenis-temuan') }}/"+id)

					$('#temuan').val(res.temuan)
					$('#desc').val(res.desc)
					$('#flag').val(res.flag)
                }
            })
        })

		// delete action
        $('#table').on('click', '.btn-delete', function(){
            var id = $(this).data('value')
			$('#form-delete').attr('action', "{{ url('jenis-temuan') }}/"+id)			
        })
	</script>
@endsection