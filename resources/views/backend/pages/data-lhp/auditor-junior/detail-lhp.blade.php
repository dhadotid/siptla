<div class="row" style="padding:0px 10px;">
    <fieldset class="col-md-6">    	
        <legend>Data LHP</legend>
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nomor LHP:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom-input" placeholder="Nomor LHP" id="detail_nomor_lhp" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Kode LHP:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom-input" placeholder="Kode LHP" id="detail_kode_lhp" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Judul LHP:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom-input" placeholder="Judul LHP" id="detail_judul_lhp" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Pemeriksa:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom-input" placeholder="Pemeriksa" id="detail_pemeriksa" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Tanggal LHP:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom-input" placeholder="Tanggal LHP" id="detail_tanggal" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Tahun Pemeriksa:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom-input" placeholder="Tahun" id="detail_tahun" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Jenis Audit:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom-input" placeholder="Jenis Audit" id="detail_jenis_audit" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Review:</label>
                    <div class="col-sm-8">
                        <textarea class="form-control custom-input" placeholder="Review" id="detail_review" readonly></textarea>
                    </div>
                </div>
            </div>
        </div>
            
    </fieldset>				
    <fieldset class="col-md-6">    	
        <legend>Data Temuan</legend>
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nomor Temuan:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom-input" placeholder="Nomor Temuan" id="detail_nomor_temuan" readonly>
                    </div>
                </div>
                 <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Temuan:</label>
                    <div class="col-sm-8">
                        <textarea class="form-control custom-input" placeholder="Temuan" id="detail_temuan" readonly></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nilai Temuan:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom-input" placeholder="Nilai Temuan" id="detail_nilai_temuan" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Level Resiko:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom-input" placeholder="Level Resiko" id="detail_level_resiko" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">PIC Temuan:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom-input" placeholder="PIC Temuan" id="detail_pic_temuan" readonly>
                    </div>
                </div>
            </div>
            <div class="row" style="padding:20px;">
                <div class="col-md-6 text-left">Data 1 dari 3 Temuan</div>
                <div class="col-md-6 text-right">
                    <a href="#" class="btn btn-outline btn-primary">Selanjutnya <i class="fa fa-caret-square-o-right"></i></a>
                </div>
            </div>
        </div>
            
    </fieldset>				
    <div class="col-md-12" style="margin-top:20px">
        <table class="table table-bordered" id="detail-rekomendasi" data-plugin="dataTable" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Rekomendasi</th>
                    <th class="text-center">Nilai Rekomendasi</th>
                    <th class="text-center">PIC 1</th>
                    <th class="text-center">PIC 2</th>
                    <th class="text-center">Status Rekomendasi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>	
<div class="clearfix"></div>
