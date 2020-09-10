<div class="row">
    <div class="col-md-12" style="margin-top:10px;">
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Unit Kerja – PIC 2
            </label>
            <div class="col-sm-9">
                <select name="unit_kerja" class="form-control" id="unit_kerja" data-plugin="select2">
                    {{-- <option value="">-- Pilih --</option> --}}
                    @foreach ($pic as $key=>$item)
                            <option value="{{$item->id}}__{{$item->nama_pic}}" @if($id!=-1) {{$idform == $item->id  ? 'selected' : ''}} @endif>{{$item->nama_pic}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <input type="hidden" name="idtemuan" value="{{$idtemuan}}">
        <input type="hidden" name="jenis" value="{{$jenis}}">
        <input type="hidden" name="idrekomendasi" id="idrekomendasi" value="{{$idrekomendasi}}">
        <input type="hidden" name="idform" value="{{$idform}}">
        <input type="hidden" name="id" id="id" value="{{$id}}">
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Pelanggan 
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="pelanggan"  placeholder="Pelanggan" id="pelanggan"
                @if($id != -1) value="{{$pelanggan}}" @endif>
            </div>
        </div>
       
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Jumlah Tagihan (Rp)
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control nominal"  class="form-control"  name="tagihan"  placeholder="Jumlah Tagihan" id="tagihan"
                @if($id != -1) value="{{$tagihan}}" @endif>
            </div>
        </div>
    </div>
</div>