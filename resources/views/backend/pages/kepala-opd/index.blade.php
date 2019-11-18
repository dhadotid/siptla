@extends('backend.layouts.master')
@section('title')
    <title>Data Kepala OPD</title>
@endsection
@section('modal')
	<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Tambah Data Kepala OPD</h4>
				</div>
				<div class="modal-body">
					<form action="{{ route('kepala-opd.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
							<select name="dinas_id" class="form-control" placeholder="OPD">
								<option value="">-- Pilih --</option>
								@foreach ($opds as $opd)
                                    <option value="{{$opd->id}}">{{$opd->nama_dinas}}</option>
                                @endforeach
							</select>
						</div>
						<div class="form-group">
							<input name="nama" type="text" class="form-control" placeholder="Nama Kepala OPD">
						</div>
						<div class="form-group">
							<input name="nip" type="text" class="form-control" placeholder="NIP">
                        </div>
						<div class="form-group">
							<input name="email" type="text" class="form-control" placeholder="Email">
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
					<h4 class="modal-title">Ubah Data Kepala OPD</h4>
				</div>
				<div class="modal-body">
					<form id="form-update" method="POST">
						@csrf
						@method('PUT')

						<div class="form-group">
							<select name="dinas_id" id="dinas_id" class="form-control" placeholder="OPD" readonly>
								{{-- <option value="">-- Pilih --</option> --}}
								@foreach ($opds as $opd)
                                    <option value="{{$opd->id}}">{{$opd->nama_dinas}}</option>
                                @endforeach
							</select>
						</div>
						<div class="form-group">
							<input name="nama"  id="nama" type="text" class="form-control" placeholder="Nama Kepala OPD">
						</div>
						<div class="form-group">
							<input name="nip"  id="nip" type="text" class="form-control" placeholder="NIP">
                        </div>
						<div class="form-group">
							<input name="email"  id="email" type="text" class="form-control" placeholder="Email">
						</div>
						<div class="form-group">
							<input name="pangkat"  id="pangkat" type="text" class="form-control" placeholder="Pangkat">
                        </div>
						<div class="form-group">
							<input name="golongan"  id="golongan" type="text" class="form-control" placeholder="Golongan">
						</div>
						<div class="form-group">
							<input name="jabatan"  id="jabatan" type="text" class="form-control" placeholder="Jabatan">
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
					<h4 class="modal-title">Konfirmasi Hapus Data Kepala OPD</h4>
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
				<span class="widget-title">Data Kepala OPD</span>
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
								<th>Nama Kepala OPD</th>
								<th>Nama OPD</th>
								<th>Golongan/Jabatan/Pangkat</th>
								<th>Email</th>
								<th>Flag</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($kepala_opd as $key => $opd)
                                @if (isset($opd->userkepala->level))
                                    @if ($opd->userkepala->level==4 && $opd->flag==1)   
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $opd->userkepala->nip }}</td>
                                            <td>{{ $opd->nama }}</td>
                                            <td>{{ $opd->dinas->nama_dinas }}</td>
                                            <td>
                                                Golongan : {{ $opd->userkepala->golongan }}<br>
                                                Jabatan : {{ $opd->userkepala->jabatan }}<br>
                                                Pangkat : {{ $opd->userkepala->pangkat }}
                                            </td>
                                            <td>{{ $opd->userkepala->email }}</td>
                                            <td>
                                                @if ($opd->flag==1)
                                                    <span class="label label-primary">Aktif</span>
                                                @else
                                                    <span class="label label-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-xs btn-warning btn-edit" data-toggle="modal" data-target="#modalubah" data-value="{{ $opd->id }}" style="height:24px !important;">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="#" class="btn btn-xs btn-danger btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="{{ $opd->id }}" style="height:24px !important;">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @php
                                            $no++;
                                        @endphp
                                    @endif
                                @endif
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
			// alert(id);
            $.ajax({
                url: "{{ url('kepala-opd') }}/"+id+"/edit",
                success: function(res) {
					$('#form-update').attr('action', "{{ url('kepala-opd') }}/"+id)

					$('#dinas_id').val(res.dinas_id)
					$('#nama').val(res.nama)
					$('#nip').val(res.userkepala.nip)
					$('#email').val(res.userkepala.email)
					$('#pangkat').val(res.userkepala.pangkat)
					$('#golongan').val(res.userkepala.golongan)
					$('#jabatan').val(res.userkepala.jabatan)
					$('#flag').val(res.userkepala.flag)
                }
            })
        })

		// delete action
        $('#table').on('click', '.btn-delete', function(){
            var id = $(this).data('value')
			$('#form-delete').attr('action', "{{ url('kepala-opd') }}/"+id)			
        })
	</script>
@endsection