<div class="row">
    <div class="col-md-12" style="margin-top:10px;">
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Unit Kerja :
            </label>
            <div class="col-sm-9">
                <select name="unit_kerja" class="form-control" id="unit_kerja" data-plugin="select2">
                    {{-- <option value="">-- Pilih --</option> --}}
                    @foreach ($pic as $key=>$item)
                            <option value="{{$item->id}}__{{$item->nama_pic}}">{{$item->nama_pic}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <input type="hidden" name="jenis" value="{{$jenis}}">
        <input type="hidden" name="idform" id="idform">
        <input type="hidden" name="idrincian" value="{{$idrincian}}">
        {{-- <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Tindak Lanjut</small>
            </label>
            <div class="col-sm-7">
                <textarea class="form-control"  name="tindak_lanjut" placeholder="Tindak Lanjut" id="tindak_lanjuttxt"></textarea>
            </div>
        </div>    --}}
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nomor PKS:
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="no_pks"  placeholder="Nomor PKS" id="no_pks">
            </div>
        </div>
        <div class="form-group">
           <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Tanggal PKS</small>
            </label>
            <div class="col-md-7">
                <div class='input-group date' id='datetimepicker_tgl_pks'>
                    <input type='date' class="form-control" name="tgl_pks" id="tgl_pks"  value="{{date('d/m/Y')}}"/>
                    <span class="input-group-addon bg-info text-white">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        {{--<div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nilai Rekomendasi:
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control nominal"  class="form-control"  name="nilai_perjanjian"  placeholder="Nilai Rekomendasi" id="nilai_perjanjian">
            </div> --}}
        </div>
        <div class="form-group">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Masa Kontrak
            </label>
            <div class="col-md-7">
                <div class='input-group date' id='datetimepicker_masa_berlaku'>
                    <input type='date' class="form-control" name="masa_berlaku" id="masa_berlaku"  value="{{date('d/m/Y')}}"/>
                    <span class="input-group-addon bg-info text-white">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Keterangan
            </label>
            <div class="col-sm-9">
                <textarea class="form-control"  class="form-control"  name="keterangan"  placeholder="Keterangan" id="keterangan"></textarea>
            </div>
        </div>
    </div>
</div>