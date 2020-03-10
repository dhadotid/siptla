{{-- Modal--}}
 <div class="modal fade" id="modaldetailtindaklanjut"  role="dialog">
		<div class="modal-dialog" style="width:80% !important;top:-50px !important;">
			<div class="modal-content">
                <form action="{{ url('review-pic1-simpan') }}" method="POST" class="form-horizontal" id="tindaklanjut-pic1" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Data Tindak Lanjut</h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-detail-tindaklanjut"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <button type="button"  class="btn btn-success" onclick="validasireview()"><i class="fa fa-save"></i> Save</button>
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Umum--}}

 <div class="modal fade" id="modaleditormonev"  role="dialog">
		<div class="modal-dialog" style="width:65% !important;top:-50px !important;">
			<div class="modal-content">
                <form action="" method="POST" class="form-horizontal" id="monev-pic1">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Catatan Monev Tindak Lanjut</h4>
                    </div>
                    <div class="modal-body">
                        <div id="div-editor"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <button type="button" onclick="simpanmonev()" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                    </div>
				</form>
			</div>
		</div>
	</div>  
 <div class="modal fade" id="modaldetailcatatan"  role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Catatan Monev Tindak Lanjut</h4>
                    </div>
                    <div class="modal-body">
                        <div id="detailcatatan"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                    </div>
			</div>
		</div>
	</div>  