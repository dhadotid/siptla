<div class="row">
    <div class="col-md-12" style="margin-top:10px;">
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Unit Kerja :
            </label>
            <div class="col-sm-9">
                <select name="unit_kerja" class="form-control" id="unit_kerja" data-plugin="select2">
                    <option value="">-- Pilih --</option>
                    @foreach ($pic as $key=>$item)
                            <option value="{{$item->id}}__{{$item->nama_pic}}">{{$item->nama_pic}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <input type="hidden" name="idtemuan" value="{{$idtemuan}}">
        <input type="hidden" name="jenis" value="{{$jenis}}">
        <input type="hidden" name="idrekomendasi" value="{{$idrekomendasi}}">
        <input type="hidden" name="idform" value="{{$idform}}">
        <input type="hidden" name="id" value="{{$id}}">
        
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Lokasi :
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="lokasi"  placeholder="Lokasi" id="lokasi">
            </div>
        </div>
        <div class="form-group">
           <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Tanggal Invoice</small>
            </label>
            <div class="col-md-7">
                <div class='input-group date' id='datetimepicker_tgl_pks'>
                    <input type='date' class="form-control" name="tgl_invoice" id="tgl_invoice"  value="{{date('d/m/Y')}}"/>
                    <span class="input-group-addon bg-info text-white">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Jumlah Tagihan:
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control nominal"  class="form-control"  name="tagihan"  placeholder="Jumlah Tagihan" id="tagihan">
            </div>
        </div>
        <div class="form-group">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Keterangan
            </label>
            <div class="col-md-9">
                <textarea class="form-control"  name="keterangan" placeholder="Keterangan" id="keterangan"></textarea>
            </div>
        </div>
    </div>
</div>