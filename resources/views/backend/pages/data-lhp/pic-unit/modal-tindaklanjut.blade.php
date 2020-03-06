{{-- Modal--}}
 <div class="modal fade" id="modaldetailtindaklanjut"  role="dialog">
		<div class="modal-dialog" style="width:80% !important;top:-50px !important;">
			<div class="modal-content">
                <form action="{{ url('review-pic1-simpan') }}" method="POST" class="form-horizontal" id="tindaklanjut-pic1">
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