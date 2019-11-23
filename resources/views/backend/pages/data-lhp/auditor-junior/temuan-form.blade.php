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

    </div>
</div>
<div class="row">
    <div class="col-md-12" style="margin-top:10px;">
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nomor Temuan:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="nomor_temuan" placeholder="Nomor Temuan" id="{{$act}}_nomor_temuan">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Temuan:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <textarea class="form-control"  name="temuan" placeholder="Temuan" id="{{$act}}_temuan"></textarea>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Jenis Temuan:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <select name="jenis_temuan" class="form-control" id="{{$act}}_jenis_temuan" data-plugin="select2">
                    <option value="">-- Pilih --</option>
                    @foreach ($jenistemuan as $item)
                        <option value="{{$item->id}}">{{$item->temuan}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">PIC Temuan:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <select name="pic_temuan" class="form-control" id="{{$act}}_pic_temuan" data-plugin="select2">
                    <option value="">-- Pilih --</option>
                    @foreach ($picunit as $item)
                        @if (isset($item->levelpic->nama_level))
                            @if ($item->levelpic->nama_level!='UKK')
                                <option value="{{$item->id}}">{{$item->nama_pic}}</option>
                            @endif
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
       <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nominal:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control nominal"  class="form-control"  name="nominal"  placeholder="Nominal" id="{{$act}}_nominal">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">
                Level Resiko:
                <br><small style="font-size:9px;color:red;font-style:italic">*wajib diisi</small>
            </label>
            <div class="col-sm-9">
                <select name="level_resiko" class="form-control" id="{{$act}}_level_resiko" data-plugin="select2">
                    <option value="">-- Pilih --</option>
                        @foreach ($levelresiko as $item)
                        <option value="{{$item->id}}">{{$item->level_resiko}}</option>
                    @endforeach
                </select>
            </div>
        </div>
       
    </div>
</div>
