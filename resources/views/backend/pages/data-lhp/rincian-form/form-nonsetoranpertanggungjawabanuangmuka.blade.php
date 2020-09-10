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
        <input type="hidden" name="idrekomendasi" value="{{$idrekomendasi}}">
        <input type="hidden" name="idform" value="{{$idform}}">
        <input type="hidden" name="id" id="id" value="{{$id}}">
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nomor Invoice:
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="no_invoice"  placeholder="Nomor Invoice" id="no_invoice"
                @if($id!=-1) value="{{$no_invoice}}" @endif>
            </div>
        </div>
        <div class="form-group">
           <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Tanggal UM</small>
            </label>
            <div class="col-md-7">
            <input type='date' class="form-control" name="tgl_um" id="tgl_um" @if($id!=-1) value="{{$tgl_um}}" @endif/>
            </div>
        </div>
        <div class="form-group">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Keterangan
            </label>
            <div class="col-md-9">
                <textarea class="form-control"  name="keterangan" placeholder="Keterangan" id="keterangan">@if($id!=-1){{$keterangan}}@endif</textarea>
            </div>
        </div>

        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Jumlah UM (Rp):
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control nominal"  class="form-control"  name="jumlah_um"  placeholder="Jumlah UM" id="jumlah_um"
                @if($id!=-1) value="{{$jumlah_um}}" @endif>
            </div>
        </div>
    </div>
</div>