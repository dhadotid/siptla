@extends('backend.layouts.master')
@section('title')
    <title>Data Rekanan</title>
@endsection
@section('modal')
	<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Tambah Data Rekanan</h4>
				</div>
				<div class="modal-body">
					<form action="{{ route('rekanan.store') }}" method="POST">
						@csrf
						<div class="form-group">
							<label>Nama</label>
							<input type="text" name="nama" class="form-control" placeholder="Nama Rekanan"/>
						</div>
						<div class="form-group">
							<label>Telepon</label>
							<input type="text" name="telepon" class="form-control" placeholder="Telepon"/>
						</div>
						<div class="form-group">
							<label>Alamat</label>
							<input type="text" name="alamat" class="form-control" placeholder="Alamat"/>
						</div>
						<div class="form-group">
							<label>Pekerjaan</label>
							<input type="text" name="pekerjaan" class="form-control" placeholder="Pekerjaan"/>
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
					<h4 class="modal-title">Ubah Data Rekanan</h4>
				</div>
				<div class="modal-body">
					<form id="form-update" method="POST">
						@csrf
						@method('PUT')
                       
						<div class="form-group">
							<label>Nama</label>
							<input type="text" name="nama" class="form-control" placeholder="Nama Rekanan" id="nama"/>
						</div>
						<div class="form-group">
							<label>Telepon</label>
							<input type="text" name="telepon" class="form-control" placeholder="Telepon" id="telepon"/>
						</div>
						<div class="form-group">
							<label>Alamat</label>
							<input type="text" name="alamat" class="form-control" placeholder="Alamat" id="alamat"/>
						</div>
						<div class="form-group">
							<label>Pekerjaan</label>
							<input type="text" name="pekerjaan" class="form-control" placeholder="Pekerjaan" id="pekerjaan"/>
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
					<h4 class="modal-title">Konfirmasi Hapus Data Rekanan</h4>
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
				<span class="widget-title">Data Rekanan</span>
				<a href="" class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modaltambah">+ Tambah Data</a>
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
								<th class="text-center" style="width:15px;">#</th>
								<th class="text-center">Nama</th>
								<th class="text-center">Alamat</th>
								<th class="text-center">No Telp</th>
								<th class="text-center">Pekerjaan</th>
								<th class="text-center">Aksi</th>
							
							</tr>
						</thead>
						<tbody>
							@foreach ($rekanan as $key => $opd)
								<tr>
									<td class="text-center">{{ $key = $key + 1 }}</td>
									<td>{{ $opd->nama }}</td>
									<td>{{ $opd->alamat }}</td>
									<td>{{ $opd->no_telp }}</td>
									<td>{{ $opd->pekerjaan }}</td>
									
									<td class="text-center">
										<a class="btn btn-xs btn-warning btn-edit" data-toggle="modal" data-target="#modalubah" data-value="{{ $opd->id }}" style="height:24px !important;">
											<i class="fa fa-edit"></i>
										</a>
										<a href="#" class="btn btn-xs btn-danger btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="{{ $opd->id }}" style="height:24px !important;">
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
		setTimeout(function(){
			$('.alert').fadeOut();
		},3000);
		$('.select2').select2();
		// binding data to modal edit
        $('#table').on('click', '.btn-edit', function(){
            var id = $(this).data('value')
			// alert(id);
            $.ajax({
                url: "{{ url('rekanan') }}/"+id+"/edit",
                success: function(res) {
					$('#form-update').attr('action', "{{ url('rekanan') }}/"+id)

					$('#nama').val(res.nama)
					$('#telepon').val(res.no_telp)
					$('#alamat').val(res.alamat)
					$('#pekerjaan').val(res.pekerjaan)
                }
            })
        })

		// delete action
        $('#table').on('click', '.btn-delete', function(){
            var id = $(this).data('value')
			$('#form-delete').attr('action', "{{ url('rekanan') }}/"+id)			
        })

	</script>
	<style>
	.select2-container{
		width:100% !important;
	}
	</style>
@endsection
