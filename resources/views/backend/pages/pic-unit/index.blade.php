@extends('backend.layouts.master')
@section('title')
    <title>Data PIC Unit</title>
@endsection
@section('modal')
	<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Tambah Data PIC Unit</h4>
				</div>
				<div class="modal-body">
					<form action="{{ route('pic-unit.store') }}" method="POST">
						@csrf

                        <div class="form-group">
							<label>Level PIC</label>
							<select name="level_pic" class="form-control select2" onchange="leveladd(this.value)">
								<option value="">-- Pilih --</option>
								@foreach ($levelpic as $index=>$item)
									<option value="{{$item->id}}">{{$item->nama_level}}</option>
								@endforeach
							</select>
						</div>
                        <div class="form-group" id="add_bidang" style="display:none">
							<label>Bidang</label>
							<select name="bidang" class="form-control select2">
								<option value="">-- Pilih --</option>
								@foreach ($bidang as $index=>$item)
									<option value="{{$item->id}}">{{$item->nama_bidang}}</option>
								@endforeach
							</select>
						</div>
                        <div class="form-group" id="add_fakultas" style="display:none">
							<label>Fakultas</label>
							<select name="fakultas" class="form-control select2">
								<option value="">-- Pilih --</option>
								@foreach ($fakultas as $index=>$item)
									<option value="{{$item->id}}">{{$item->nama_fakultas}}</option>
								@endforeach
							</select>
						</div>
						
						<div class="form-group">
							<label>Nama PIC</label>
							<input type="text" name="nama_pic" class="form-control" placeholder="Nama PIC"/>
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
					<h4 class="modal-title">Ubah Data PIC Unit</h4>
				</div>
				<div class="modal-body">
					<form id="form-update" method="POST">
						@csrf
						@method('PUT')

						
                        <div class="form-group">
							<label>Level PIC</label>
							<select name="level_pic" class="form-control select2" onchange="leveledit(this.value)" id="level_pic">
								<option value="">-- Pilih --</option>
								@foreach ($levelpic as $index=>$item)
									<option value="{{$item->id}}">{{$item->nama_level}}</option>
								@endforeach
							</select>
						</div>
                        <div class="form-group" id="edit_bidang">
							<label>Bidang</label>
							<select name="bidang" class="form-control select2" id="bidang">
								<option value="">-- Pilih --</option>
								@foreach ($bidang as $index=>$item)
									<option value="{{$item->id}}">{{$item->nama_bidang}}</option>
								@endforeach
							</select>
						</div>
                        <div class="form-group" id="edit_fakultas">
							<label>Fakultas</label>
							<select name="fakultas" class="form-control select2" id="fakultas">
								<option value="">-- Pilih --</option>
								@foreach ($fakultas as $index=>$item)
									<option value="{{$item->id}}">{{$item->nama_fakultas}}</option>
								@endforeach
							</select>
						</div>
                       
						<div class="form-group">
							<label>Nama PIC</label>
							<input type="text" name="nama_pic" class="form-control" placeholder="Nama PIC" id="nama_pic"/>
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
					<h4 class="modal-title">Konfirmasi Hapus Data PIC Unit</h4>
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
				<span class="widget-title">Data PIC Unit</span>
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
								<th class="text-center">Level PIC Unit</th>
								<th class="text-center">Bidang</th>
								<th class="text-center">Fakultas</th>
								<th class="text-center">Nama PIC Unit</th>
								@if (Auth::user()->level=='0')
									<th class="text-center">Aksi</th>
								@endif
							</tr>
						</thead>
						<tbody>
							@foreach ($picunit as $key => $opd)
								<tr>
									<td class="text-center">{{ $key = $key + 1 }}</td>
									<td>{{ isset($opd->levelpic->nama_level) ? $opd->levelpic->nama_level : '-' }}</td>
									<td>{{ isset($opd->bid->nama_bidang) ? $opd->bid->nama_bidang : '-' }}</td>
									<td>{{ isset($opd->fak->nama_fakultas) ? $opd->fak->nama_fakultas : '-' }}</td>
									<td>{{ $opd->nama_pic }}</td>
									@if (Auth::user()->level=='0')
										<td class="text-center">
											<a class="btn btn-xs btn-warning btn-edit" data-toggle="modal" data-target="#modalubah" data-value="{{ $opd->p_id }}" style="height:24px !important;">
												<i class="fa fa-edit"></i>
											</a>
											<a href="#" class="btn btn-xs btn-danger btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="{{ $opd->p_id }}" style="height:24px !important;">
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
		hidelaert();
		$('.select2').select2();
		// binding data to modal edit
        $('#table').on('click', '.btn-edit', function(){
            var id = $(this).data('value')
			// alert(id);
            $.ajax({
                url: "{{ url('pic-unit') }}/"+id+"/edit",
                success: function(res) {
					$('#form-update').attr('action', "{{ url('pic-unit') }}/"+id)

					$('#level_pic').val(res.level_pic)
					$('#level_pic').select2().trigger('change');

					if(res.bidang==null)
						$('#edit_bidang').css('display','none');
					else
					{
						$('#bidang').val(res.bidang)
						$('#bidang').select2().trigger('change');
					}
					if(res.fakultas==null)
						$('#edit_fakultas').css('display','none');
					else
					{
						$('#fakultas').val(res.fakultas)
						$('#fakultas').select2().trigger('change');
					}
					$('#nama_pic').val(res.nama_pic)
                }
            })
        })

		// delete action
        $('#table').on('click', '.btn-delete', function(){
            var id = $(this).data('value')
			$('#form-delete').attr('action', "{{ url('pic-unit') }}/"+id)			
        })

		function leveladd(val)
		{
			if(val=='1')
			{
				$('#add_bidang').css('display','block');
				$('#add_fakultas').css('display','none');
			}
			else if(val=='5')
			{
				$('#add_bidang').css('display','none');
				$('#add_fakultas').css('display','block');
			}
			else
			{
				$('#add_bidang').css('display','none');
				$('#add_fakultas').css('display','none');
			}
		}
		function leveledit(val)
		{
			if(val=='1')
			{
				$('#edit_bidang').css('display','block');
				$('#edit_fakultas').css('display','none');
			}
			else if(val=='5')
			{
				$('#edit_bidang').css('display','none');
				$('#edit_fakultas').css('display','block');
			}
			else
			{
				$('#edit_bidang').css('display','none');
				$('#edit_fakultas').css('display','none');
			}
		}

	</script>
	<style>
	.select2-container{
		width:100% !important;
	}
	</style>
@endsection
