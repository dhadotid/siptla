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
       
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Jumlah Rekomendasi:
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control nominal"  class="form-control"  name="jumlah_rekomendasi"  placeholder="Jumlah Rekomendasi" id="jumlah_rekomendasi">
            </div>
        </div>   
        
         <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Dokumen Pendukung:
            </label>
            <div class="col-sm-9">
                <input type="file" class="form-control"  class="form-control"  name="file_pendukung"  placeholder="File Pendukung" id="file_pendukung">
            </div>
        </div> 
    </div>
</div>