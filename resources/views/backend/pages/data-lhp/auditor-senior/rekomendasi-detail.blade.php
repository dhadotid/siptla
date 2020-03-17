<div class="row" style="padding:0 10px;">
    <div class="col-md-12" style="border:1px solid #bbb;border-radius:5px;padding:5px 20px 0px 20px;background:#eee;">

            <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Data Temuan</h4>
            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                <div class="col-md-6" style="margin:0px;padding:0px;margin-bottom:10px;">
                     <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Nomor LHP:</label>
                        <div class="col-sm-12">
                            <input type="text"  readonly class="" name="nomor_lhp" placeholder="Nomor LHP" id="nomor_lhp" value="{{$data->no_lhp}}" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Nomor Temuan:</label>
                        <div class="col-sm-12">
                            <input type="text"  readonly class="d_nomor_temuan" name="nomor_temuan" placeholder="Nomor Temuan" id="nomor_temuan"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Temuan:</label>
                        <div class="col-sm-12">
                            <textarea type="text" class="d_temuan" name="temuan" placeholder="Temuan" id="temuan" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%;min-height:100px"></textarea>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>
<div class="row ">
    <div class="col-md-12 form-horizontal" style="margin-top:10px;">
        <div class="form-group" style="margin:10px 0;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Rekomendasi:
            </label>
            <div class="col-sm-8" style="padding-top:10px">
                <textarea class="form-control custom-input"  name="rekomendasi" placeholder="Rekomendasi" id="detail_rekomendasi"></textarea>
            </div>
        </div>
        <div class="form-group" style="margin:10px 0;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Nilai Rekomendasi:
            </label>
            <div class="col-sm-8" style="padding-top:10px">
                <input type="text"  readonly class="form-control custom-input nominal"  class="form-control custom-input"  name="nilai_rekomendasi"  placeholder="Nilai Rekomendasi" id="detail_nilai_rekomendasi">
            </div>
        </div>
        <div class="form-group" style="margin:10px 0;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">PIC 1:
            </label>
            <div class="col-sm-8" style="padding-top:10px">
                <input type="text"  readonly class="form-control custom-input" name="pic_1" class="form-control custom-input" id="detail_pic_1">
            </div>
        </div>
        <div class="form-group" style="margin:10px 0;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">PIC 2:
            </label>
            <div class="col-sm-8" style="padding-top:10px">
                <input type="text"  readonly class="form-control custom-input" name="pic_2" class="form-control custom-input" id="detail_pic_2">
                
            </div>
        </div>
        <div class="form-group" style="margin:10px 0;display:none" id="div_detail_rekanan">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Rekanan:
            </label>
            <div class="col-sm-8" style="padding-top:10px">
                <input type="text"  readonly class="form-control custom-input" name="rekanan" class="form-control custom-input" id="detail_rekanan">
            </div>
        </div>
        <div class="form-group" style="margin:10px 0;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Jangka Waktu Penyelesaian:
            </label>
            <div class="col-sm-8" style="padding-top:10px">
                <input type="text"  readonly class="form-control custom-input" name="jangka_waktu" class="form-control custom-input" id="detail_jangka_waktu">
               
            </div>
        </div>
        
       
        <div class="form-group" style="margin:10px 0;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">
                Status Rekomendasi:
            </label>
            <div class="col-sm-8" style="padding-top:10px">
                 <input type="text"  readonly class="form-control custom-input" name="status_rekomendasi" class="form-control custom-input" id="detail_status_rekomendasi">
            </div>
        </div>
       <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Review Auditor:
            </label>
            <div class="col-sm-8" style="padding-top:10px">
                <textarea class="form-control custom-input"  name="review_auditor" placeholder="Review Auditor" id="detail_review_auditor"></textarea>
            </div>
        </div>
    </div>
</div>
