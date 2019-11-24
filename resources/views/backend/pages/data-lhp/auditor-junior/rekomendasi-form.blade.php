<div class="row" style="padding:0 10px;">
    <div class="col-md-12" style="border:1px solid #bbb;border-radius:5px;padding:5px 20px 0px 20px;background:#eee;">

            <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Data Temuan</h4>
            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                <div class="col-md-6" style="margin:0px;padding:0px;margin-bottom:10px;">
                     <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Nomor LHP:</label>
                        <div class="col-sm-12">
                            <input type="text" class="d_nomor_lhp" name="nomor_lhp" placeholder="Nomor LHP" id="nomor_lhp" value="{{$data->no_lhp}}" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Nomor Temuan:</label>
                        <div class="col-sm-12">
                            <input type="text" class="d_nomor_temuan" name="nomor_temuan" placeholder="Nomor Temuan" id="nomor_temuan"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%">
                            <input type="hidden" class="d_id_temuan" name="id_temuan" id="id_temuan">
                            <input type="hidden" class="d_jenis_temuan" name="jenis_temuan" id="jenis_temuan">
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
<div class="row">
    <div class="col-md-12" style="margin-top:10px;">
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Rekomendasi:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <textarea class="form-control"  name="rekomendasi" placeholder="Rekomendasi" id="{{$act}}_rekomendasi"></textarea>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nilai Rekomendasi:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control nominal"  class="form-control"  name="nilai_rekomendasi"  placeholder="Nilai Rekomendasi" id="{{$act}}_nilai_rekomendasi">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">PIC 1:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <select name="pic_1" class="form-control" id="{{$act}}_pic_1" data-plugin="select2">
                    <option value="">-- Pilih --</option>
                    @foreach ($picunit as $item)
                        @if (isset($item->levelpic->nama_level))
                            <option value="{{$item->id}}">{{$item->nama_pic}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">PIC 2:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <select name="pic_2[]" class="form-control" id="{{$act}}_pic_2" data-plugin="select2" multiple>
                    <option value="">-- Pilih --</option>
                    @foreach ($picunit as $item)
                        @if (isset($item->levelpic->nama_level))
                            <option value="{{$item->id}}">{{$item->nama_pic}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;display:none" id="div_{{$act}}_rekanan">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Rekanan:
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="rekanan" class="form-control" id="{{$act}}_rekanan">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Jangka Waktu Penyelesaian:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <select name="jangka_waktu" class="form-control" id="{{$act}}_jangka_waktu" data-plugin="select2">
                    <option value="">-- Pilih --</option>
                    @foreach ($jangkawaktu as $item)
                        <option value="{{$item->id}}">{{$item->jangka_waktu}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
       
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">
                Status Rekomendasi:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <select name="status_rekomendasi" class="form-control" id="{{$act}}_status_rekomendasi" data-plugin="select2">
                    <option value="">-- Pilih --</option>
                        @foreach ($statusrekomendasi as $item)
                        <option value="{{$item->id}}">{{$item->rekomendasi}}</option>
                    @endforeach
                </select>
            </div>
        </div>
       <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Review Auditor:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <textarea class="form-control"  name="review_auditor" placeholder="Review Auditor" id="{{$act}}_review_auditor"></textarea>
            </div>
        </div>
    </div>
</div>
