{{-- Modal--}}
 <div class="modal fade" id="modaldetailtindaklanjut"  role="dialog">
		<div class="modal-dialog" style="width:80% !important;top:-50px !important;">
			<div class="modal-content">
                <form action="{{ url('tindaklanjut-junior-simpan') }}" method="POST" class="form-horizontal" id="tindaklanjut-junior">
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
                        <button type="button"  class="btn btn-success" onclick="publishtljunior()" id="btn-publish"><i class="fa fa-save"></i> Publish</button>
                        <button type="button"  class="btn btn-info" onclick="drafttljunior()" id="btn-draft"><i class="fa fa-file"></i> Draft</button>
                        
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Umum--}}