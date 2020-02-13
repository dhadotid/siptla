@extends('backend.layouts.master')
@section('title')
    <title>Data Jangka Waktu </title>
@endsection
@section('modal')
	<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Tambah Data Jangka Waktu </h4>
				</div>
				<div class="modal-body">
					<form action="{{ route('jangka-waktu.store') }}" method="POST" id="add-form">
						@csrf

                       
                        <div class="form-group">
							<label>Jangka Waktu </label>
							<div class="row">
								<div class="col-md-2">
									<input type="number" name="mulai" class="form-control" placeholder="0" id="add_mulai"/>
								</div>
								<div class="col-md-2">
									<select name="waktu" class="form-control" readonly>
										<option value="-">s.d.</option>
									</select>
								</div>
								<div class="col-md-2">
									<input type="number" name="akhir" class="form-control" placeholder="0" id="add_akhir"/>
								</div>
								<div class="col-md-3">
									<select name="jenis" class="form-control">
										<option value="minggu">Minggu</option>
										<option value="bulan">Bulan</option>
										<option value="tahun">Tahun</option>
									</select>
								</div>
							</div>
							
						</div>
                        
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
					<input type="button" onclick="jangkawaktu('add')" class="btn btn-success" value="Simpan">
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
					<h4 class="modal-title">Ubah Data Jangka Waktu </h4>
				</div>
				<div class="modal-body">
					<form method="POST" id="edit-form">
						@csrf
						@method('PUT')
						
                        <div class="form-group">
							<label>Jangka Waktu </label>
							<div class="row">
								<div class="col-md-2">
									<input type="number" name="mulai" class="form-control" placeholder="0" id="edit_mulai"/>
								</div>
								<div class="col-md-2">
									<select name="waktu" class="form-control" readonly>
										<option value="-">s.d.</option>
									</select>
								</div>
								<div class="col-md-2">
									<input type="number" name="akhir" class="form-control" placeholder="0" id="edit_akhir"/>
								</div>
								<div class="col-md-3">
									<select name="jenis" class="form-control" id="jenis">
										<option value="minggu">Minggu</option>
										<option value="bulan">Bulan</option>
										<option value="tahun">Tahun</option>
									</select>
								</div>
							</div>
							
						</div>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
					<input type="button" onclick="jangkawaktu('edit')" class="btn btn-success" value="Simpan Perubahan">
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
					<h4 class="modal-title">Konfirmasi Hapus Data Jangka Waktu </h4>
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
				<span class="widget-title">Data Jangka Waktu </span>
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
								<th class="text-center" style="width:15px;">#</th>
								<th class="text-center">Jangka Waktu </th>
								@if (Auth::user()->level=='0')
									<th class="text-center">Aksi</th>
								@endif
							</tr>
						</thead>
						<tbody>
							@foreach ($jangkawaktu as $key => $opd)
								<tr>
									<td class="text-center">{{ $key = $key + 1 }}</td>
									<td class="text-left">{{$opd->jangka_waktu}}</td>

									@if (Auth::user()->level=='0')
										<td class="text-center">
											<a class="btn btn-xs btn-warning btn-edit" data-toggle="modal" data-target="#modalubah" data-value="{{ $opd->id }}" style="height:24px !important;">
												<i class="fa fa-edit"></i>
											</a>
											<a href="#" class="btn btn-xs btn-danger btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="{{ $opd->id }}" style="height:24px !important;">
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
			// alert(id);
            $.ajax({
                url: "{{ url('jangka-waktu') }}/"+id+"/edit",
                success: function(res) {
					$('#edit-form').attr('action', "{{ url('jangka-waktu') }}/"+id)

					$('#edit_mulai').val(res.mulai)
					$('#edit_akhir').val(res.akhir)
					$('#jenis').val(res.jenis)
					
                }
            })
        })

		// delete action
        $('#table').on('click', '.btn-delete', function(){
            var id = $(this).data('value')
			$('#form-delete').attr('action', "{{ url('jangka-waktu') }}/"+id)			
        })

		function jangkawaktu(jenis)
		{
			var mulai=$('#'+jenis+'_mulai');
			var akhir=$('#'+jenis+'_akhir');
			if(mulai.val()=='')
			{
				alert('Waktu Awal Harus Diisi')
				mulai.focus();
				// swal('alert','aaa')
			}
			else if(akhir.val()=='')
			{
				alert('Waktu Akhir Harus Diisi')
				akhir.focus();				
			}
			else if(parseInt(akhir.val()) < parseInt(mulai.val()))
			{
				alert('Waktu Akhir Tidak Boleh Lebih Besar Dari Waktu Awal');
				akhir.focus();
			}
			else
			{
				$('#'+jenis+'-form').submit();
			}
		}
	</script>
	<style>
	.select2-container{
		width:100% !important;
	}
	.dataTables_length .form-control,
	.dataTables_filter .form-control
	{
		min-width:unset !important;
	}
	</style>
@endsection
