<div class="row">
    <div class="col-md-12" style="margin-top:10px;">
        
        <input type="hidden" name="jenis" value="{{$jenis}}">
        <input type="hidden" name="idform" value="{{$idform}}">
        <input type="hidden" name="id" value="{{$idrincian}}">
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Tindak Lanjut</small>
            </label>
            <div class="col-sm-7">
                <textarea class="form-control"  name="tindak_lanjut" placeholder="Tindak Lanjut" id="tindak_lanjuttxt"></textarea>
            </div>
        </div> 
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nilai (Rp):
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control nominal"  class="form-control"  name="nilai"  placeholder="Nilai (Rp)" id="nilai">
            </div>
        </div>   
        <div class="form-group" style="margin-bottom:10px;">
           <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Tanggal</small>
            </label>
            <div class="col-md-7">
                <div class='input-group date' id='datetimepicker_tgl_pks'>
                    <input type='date' class="form-control" name="tanggal" id="tanggal"  value="{{date('d/m/Y')}}"/>
                    <span class="input-group-addon bg-info text-white">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Jenis Setoran:
            </label>
            <div class="col-sm-9">
                <select name="jenis_setoran" class="form-control" id="jenis_setoran" data-plugin="select2">
                    <option value="Bank">Bank</option>
                    <option value="Non Setoran">Non Setoran</option>
                </select>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Bank Tujuan:
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="bank_tujuan"  placeholder="Bank Tujuan" id="bank_tujuan">
            </div>
        </div>  
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">No Ref:
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="no_ref"  placeholder="No. Referensi" id="no_ref">
            </div>
        </div>  
         
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Jenis Rekening:
            </label>
            <div class="col-sm-9">
                <select name="jenis_setoran" class="form-control" id="jenis_setoran" data-plugin="select2">
                    <option value="NTPN">NTPN</option>
                    <option value="Rekening Bank">Rekening Bank</option>
                </select>
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