<div class="row" style="padding:0 10px;">
    <div class="col-md-12" style="border:1px solid #bbb;border-radius:5px;padding:5px 20px 0px 20px;background:#eee;">

            <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Data LHP</h4>
            
            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                <div class="col-md-6" style="margin:0px;padding:0px;margin-bottom:10px;">
                     <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Nomor LHP:</label>
                        <div class="col-sm-12">
                            <input type="text" class="" name="nomor_lhp" placeholder="Nomor LHP" id="nomor_lhp" value="{{$data->no_lhp}}" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Kode LHP:</label>
                        <div class="col-sm-12">
                            <input type="text" class="" name="kode_lhp" placeholder="Kode LHP" id="kode_lhp" value="{{$data->kode_lhp}}" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Judul LHP:</label>
                        <div class="col-sm-12">
                            <input type="text" class="" name="judul_lhp" placeholder="Judul LHP" id="judul_lhp" value="{{$data->judul_lhp}}" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%">
                        </div>
                    </div>
                </div>
            </div>

            <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Status LHP</h4>
            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                <div class="col-sm-12">
                    <select class="form-control" id="review_status_lhp" data-plugin="select2" name="review_status_lhp">
                        <option value="Create LHP" {{$data->status_lhp=='Create LHP' ? 'selected="selected"' : ''}}>Create LHP</option>
                        <option value="Review LHP" {{$data->status_lhp=='Review LHP' ? 'selected="selected"' : ''}}>Review LHP</option>
                        <option value="Publish LHP" {{$data->status_lhp=='Publish LHP' ? 'selected="selected"' : ''}}>Publish LHP</option>
                    </select>
                </div>
            </div>

    </div>
</div>
<div class="row">
    <div class="col-md-12" style="margin-top:20px;">
        <h3>Tambah Review LHP</h3>
        <input type="hidden" id="id-review-lhp" value="{{$idlhp}}">
        <input type="hidden" id="tahun-review-lhp" value="{{$data->tahun_pemeriksa}}">
        <input type="hidden" id="idreview" value="{{$idreview}}">
        <div id="summernote-review">{!!$idreview==0 ? '' : $review->review!!}</div>
    </div>
</div>
