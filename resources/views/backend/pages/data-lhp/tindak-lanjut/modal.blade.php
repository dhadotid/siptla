    <div class="modal fade" id="modaltambahtindaklanjut" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="form_tindaklanjut_add" >
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Tambah Data Tindak Lanjut </h4>
                    </div>
                    <div class="modal-body">
                        @php
                            $dt['act']='add';
                        @endphp
                        @include('backend.pages.data-lhp.tindak-lanjut.add')
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasitindaklanjut('add')" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
    </div>  
    <div class="modal fade" id="modalubahtindaklanjut" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:50% !important">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="form_tindaklanjut_edit" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Ubah Data Tindak Lanjut </h4>
                    </div>
                    <div class="modal-body">
                        @php
                            $dt['act']='edit';
                        @endphp
                        @include('backend.pages.data-lhp.tindak-lanjut.edit')
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasitindaklanjut('edit')" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
    </div>  