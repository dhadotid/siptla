@extends('backend.layouts.master')
@section('title')
    <title>Data Bidang Pengawasan</title>
@endsection
@section('modal')
	<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Tambah Data Bidang Pengawasan</h4>
				</div>
				<div class="modal-body">
					<form action="{{ route('bidang-pengawasan.store') }}" method="POST">
						@csrf
						<div class="form-group">
							<input name="code" type="text" class="form-control" placeholder="Code">
						</div>
						<div class="form-group">
							<textarea name="bidang" class="form-control" placeholder="Bidang Pengawasan"></textarea>
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
					<h4 class="modal-title">Ubah Data Bidang Pengawasan</h4>
				</div>
				<div class="modal-body">
					<form id="form-update" method="POST">
						@csrf
                        @method('PUT')
                        <div class="form-group">
							<input id="code" name="code" type="text" class="form-control" placeholder="Code">
						</div>
						<div class="form-group">
							<textarea id="bidang" name="bidang" class="form-control" placeholder="Bidang Pengawasan"></textarea>
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
					<h4 class="modal-title">Konfirmasi Hapus Data Bidang Pengawasan</h4>
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
				<span class="widget-title">Data Bidang Pengawasan</span>
				<a href="" class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modaltambah">+ Tambah Data</a>
			</header><!-- .widget-header -->
			<hr class="widget-separator">
			<div class="widget-body">
				<div class="table-responsive">
					<table id="table" data-plugin="DataTable" class="table table-striped" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th style="width:15px;">#</th>
								<th>Kode</th>
								<th>Bidang Pengawasan</th>
								<th>Flag</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($bidang as $key => $item)
								<tr>
									<td>{{ $key = $key + 1 }}</td>
									<td>{{ $item->code }}</td>
									<td>{{ $item->bidang }}</td>
									<td>
                                        @if ($item->flag==1)
                                            <span class="label label-primary">Aktif</span>
                                        @else
                                            <span class="label label-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
									
									<td>
										<a class="btn btn-xs btn-warning btn-edit" data-toggle="modal" data-target="#modalubah" data-value="{{ $item->id }}" style="height:24px !important">
											<i class="fa fa-edit"></i>
										</a>
										<a href="#" class="btn btn-xs btn-danger btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="{{ $item->id }}" style="height:24px !important">
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
                url: "{{ url('bidang-pengawasan') }}/"+id+"/edit",
                success: function(res) {
					$('#form-update').attr('action', "{{ url('bidang-pengawasan') }}/"+id)

					$('#code').val(res.code)
					$('#bidang').val(res.bidang)
					$('#flag').val(res.flag)
                }
            })
        })

		// delete action
        $('#table').on('click', '.btn-delete', function(){
            var id = $(this).data('value')
			$('#form-delete').attr('action', "{{ url('bidang-pengawasan') }}/"+id)			
        })
	</script>
@endsection