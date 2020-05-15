<div class="row">
    <div class="col-md-12" style="margin-top:10px;">
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Unit Kerja - PIC 2
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
        <input type="hidden" name="id" value="{{$id}}">
        
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Jenis Rekening
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="jenis_rekening"  placeholder="Jenis Rekening" id="jenis_rekening"
                @if($id != -1) value="{{$jenis_rekening}}" @endif>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nama Bank
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="nama_bank"  placeholder="Nama Bank" id="nama_bank"
                @if($id != -1) value="{{$nama_bank}}" @endif>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nomor Rekening
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="nomor_rekening"  placeholder="Nomor Rekening" id="nomor_rekening"
                @if($id != -1) value="{{$nomor_rekening}}" @endif>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nama Rekening
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="nama_rekening"  placeholder="Nama Rekening" id="nama_rekening"
                @if($id != -1) value="{{$nama_rekening}}" @endif>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Mata Uang
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="mata_uang"  placeholder="Mata Uang" id="mata_uang"
                @if($id != -1) value="{{$mata_uang}}" @endif>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Saldo Temuan
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control nominal"  class="form-control"  name="saldo_akhir"  placeholder="Saldo Temuan" id="saldo_akhir"
                @if($id != -1) value="{{$saldo_akhir}}" @endif>
            </div>
        </div>
    </div>
</div>