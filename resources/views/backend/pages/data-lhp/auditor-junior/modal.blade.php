{{-- Modal Rekomendasi --}}
    <div class="modal fade" id="modaltambahrekomendasi" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:60% !important">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="form_rekom_add">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Tambah Data Rekomendasi </h4>
                    </div>
                    <div class="modal-body">
                        @php
                            $dt['act']='add';
                        @endphp
                        <input type="hidden" name="id_lhp" value="{{$idlhp}}">
                        @include('backend.pages.data-lhp.auditor-junior.rekomendasi-form',$dt)
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasirekom('add')" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
    </div>  
    <div class="modal fade" id="modalubahrekomendasi" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:60% !important">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="form_rekom_edit">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Ubah Data Rekomendasi </h4>
                    </div>
                    <div class="modal-body">
                        @php
                            $dt['act']='edit';
                        @endphp
                        <input type="hidden" name="id_lhp" value="{{$idlhp}}">
                        <input type="hidden" name="idrekom" id="idrekom">
                        @include('backend.pages.data-lhp.auditor-junior.rekomendasi-form',$dt)
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasirekom('edit')" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
    </div>  
    <div class="modal fade" id="modaldetailrekomendasi" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:60% !important">
			<div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Detail Data Rekomendasi </h4>
                    </div>
                    <div class="modal-body">
                        @include('backend.pages.data-lhp.auditor-junior.rekomendasi-detail',$dt)
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
                    </div>
				</form>
			</div>
		</div>
    </div>  
    
    <div class="modal fade" id="modalhapusrekomendasi" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Konfirmasi Hapus Data Temuan</h4>
				</div>
				<div class="modal-body">
                    <h4>Apakah anda yakin akan menghapus data Rekomendasi ini?</h4>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
					<a class="btn btn-danger" id="hapusrekom" style="cursor:pointer;">Ya, Saya Yakin</a>
				</div>
			</div>
		</div>
    </div>
{{-- End Modal Rekomendasi --}}

{{-- Modal Temuan --}}
    <div class="modal fade" id="modaltambah" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form action="{{ url('data-temuan-lhp-simpan/'.$idlhp) }}" method="POST" class="form-horizontal" id="formadd">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Tambah Data Temuan </h4>
                    </div>
                    <div class="modal-body">
                        @php
                            $dt['act']='add';
                        @endphp
                        @include('backend.pages.data-lhp.auditor-junior.temuan-form',$dt)
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasiadd('add')" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
    <div class="modal fade" id="modalubah" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form action="{{ url('data-temuan-lhp-update/'.$idlhp) }}" method="POST" class="form-horizontal" id="formedit">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Ubah Data Temuan </h4>
                    </div>
                    <div class="modal-body">
                        @php
                            $dt['act']='edit';
                        @endphp
                        <input type="hidden" id="temuan_id" name="temuan_id">
                        @include('backend.pages.data-lhp.auditor-junior.temuan-form',$dt)
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasiadd('edit')" class="btn btn-success" value="Simpan">
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
					<h4 class="modal-title">Konfirmasi Hapus Data Temuan</h4>
				</div>
				<div class="modal-body">
					<h4>Apakah anda yakin akan menghapus data temuan ini?</h4>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
					<a class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('form-delete').submit();" style="cursor:pointer;">Ya, Saya Yakin</a>
					<form id="form-delete" method="POST" style="display: none;">
						@csrf
					</form>
				</div>
			</div>
		</div>
    </div>
    <div class="modal fade" id="modaldetail" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Ubah Data Temuan </h4>
                    </div>
                    <div class="modal-body">
                        @include('backend.pages.data-lhp.auditor-junior.temuan-detail')
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
                    </div>
			</div>
		</div>
	</div>
{{-- End Modal Temuan --}}