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
                            <textarea type="text" class="d_temuan" name="temuan" placeholder="Temuan" id="temuan" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%;min-height:50px"></textarea>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>
<div class="row">
    <div class="col-md-12" style="margin-top:10px;">
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nomor Rekomendasi:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  name="no_rekomendasi" placeholder="Nomor Rekomendasi" id="{{$act}}_no_rekomendasi"/>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Rekomendasi:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <textarea class="form-control"  name="rekomendasi" placeholder="Rekomendasi" id="{{$act}}_rekomendasi"></textarea>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Kirim ke Auditor Senior :
            </label>
            <div class="col-sm-9">
                <select name="senior_auditor" class="form-control" id="{{$act}}_senior_auditor" data-plugin="select2">
                    <option value="">-- Pilih --</option>
                    @foreach ($senior as $key=>$item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nilai Rekomendasi:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control nominal nilai_rekomendasi"  name="nilai_rekomendasi"  placeholder="Nilai Rekomendasi" id="{{$act}}_nilai_rekomendasi">
            </div>
            <input type="hidden" name="idform" id="idform">
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Butuh Rincian ? :
            </label>
            <div class="col-sm-9" style="padding-top:5px;">
                <div style="width:100px;float:left"><input type="radio" style="float:left;width:20%" onclick="cekrbutuhrincian()" name="butuh_rincian" id="butuh_rincian" value="1"> Ya</div>
                <div style="width:100px;float:left"><input type="radio"  style="float:left;width:20%" onclick="cekrbutuhrincian()" name="butuh_rincian" id="butuh_rincian_false" value="0" checked> Tidak</div>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Rincian Tindak Lanjut :
            </label>
            <div class="col-sm-9">
                <select name="rincian_tl" class="form-control" disabled id="rincian_tl" data-plugin="select2" onchange="pilihrincianold(this.value)">
                    <option value="">-- Pilih --</option>
                    @foreach (rinciantindaklanjut() as $key=>$item)
                            <option value="{{$key}}">{{$item}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">PIC 1:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <select name="pic_1" class="form-control pic1" id="{{$act}}_pic_1" data-plugin="select2" onchange="aktifinrincian(this.value)">
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

            </label>
            <div class="col-sm-9">
                <select name="pic_2[]" class="form-control pic2" id="{{$act}}_pic_2" data-plugin="select2" multiple>
                    <option value=""></option>
                    @foreach ($picunit as $item)
                        @if (isset($item->levelpic->nama_level))
                            <option value="{{$item->id}}">{{$item->nama_pic}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        {{-- <div class="form-group" style="margin-bottom:10px;display:none" id="div_{{$act}}_rekanan">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Rekanan:
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="rekanan" class="form-control" id="{{$act}}_rekanan">
            </div>
        </div> --}}
        {{-- <div class="form-group" style="margin-bottom:10px;">
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
        </div> --}}
        
       
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">
                Status Rekomendasi:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                @if ($act=='add')
                    <input type="text" class="form-control" readonly value="Belum Ditindaklanjuti (BTL)">
                    <input type="hidden" readonly value="3" name="status_rekomendasi">

                @else                    
                    <select name="status_rekomendasi" class="form-control status_rekom" id="{{$act}}_status_rekomendasi" data-plugin="select2" readonly> 
                        {{-- <option value="">-- Pilih --</option> --}}
                            @if ($act=='add')
                                @foreach ($statusrekomendasi as $item)
                                        @if ($item->id==3)
                                            <option value="{{$item->id}}" selected="selected">{{$item->rekomendasi}}</option>
                                        @endif
                                @endforeach
                            @else
                                @foreach ($statusrekomendasi as $item)
                                        @if ($item->id==2)
                                            <option value="{{$item->id}}" selected="selected">{{$item->rekomendasi}}</option>
                                        @else
                                            <option value="{{$item->id}}">{{$item->rekomendasi}}</option>
                                        @endif
                                @endforeach
                            @endif
                    </select>
                @endif
            </div>
        </div>
       {{-- <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Review Auditor:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <textarea class="form-control"  name="review_auditor" placeholder="Review Auditor" id="{{$act}}_review_auditor"></textarea>
            </div>
        </div> --}}
    </div>
</div>
<style>
    .select2-selection--multiple{
        overflow: hidden !important;
        height: auto !important;
    }
</style>