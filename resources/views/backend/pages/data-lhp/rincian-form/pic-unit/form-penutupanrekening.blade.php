<div class="row">
    <div class="col-md-12" style="margin-top:10px;">
        <input type="hidden" name="jenis" value="{{$jenis}}">
        <input type="hidden" name="idform" id="idform">
        <input type="hidden" name="idrincian" value="{{$idrincian}}">
        <div class="form-group" style="margin-bottom:10px;">
           <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Tanggal:</small>
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
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Deskripsi Tindak Lanjut:</small>
            </label>
            <div class="col-sm-7">
                <textarea class="form-control"  name="tindak_lanjut" placeholder="Deskripsi Tindak Lanjut" id="tindak_lanjuttxt"></textarea>
            </div>
        </div> 
        <div class="form-group" style="margin-bottom:10px;">
           <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Tanggal Penutupan Rekening:</small>
            </label>
            <div class="col-md-7">
                <div class='input-group date' id='datetimepicker_tgl_pks'>
                    <input type='date' class="form-control" name="tanggal_penutupan_rekening" id="tanggal_penutupan_rekening"  value="{{date('d/m/Y')}}"/>
                    <span class="input-group-addon bg-info text-white">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Saldo Akhir (Rp):
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control nominal"  class="form-control"  name="saldo_akhir"  placeholder="Saldo Akhir (Rp)" id="saldo_akhir">
            </div>
        </div>   
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">No. Rekening Pemindahan Saldo:
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="no_rekening_pemindahan_saldo"  placeholder="No. Rekening Pemindahan Saldo" id="no_rekening_pemindahan_saldo">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nama Rekening Pemindahan Saldo
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  class="form-control"  name="nama_rekening_pemindahan_saldo"  placeholder="Nama Rekening Pemindahan Saldo" id="nama_rekening_pemindahan_saldo">
            </div>
        </div>
        
        <div class="form-group" style="margin-bottom:10px;">
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Dokumen Pendukung:
            </label>
            <div class="col-sm-4">
                <input type="text" class="form-control"  class="form-control"  name="nama_file_1"  placeholder="Nama File" id="nama_file_1">
            </div>
            <div class="col-sm-5">
                <input type="file" class="form-control"  class="form-control" onchange="insertFile(1)" id="add_dokumen_1" name="add_dokumen_1"  placeholder="File Pendukung" >
            </div>
        </div> 
        <div class="field_wrapper">
        
        </div>

            <div class="col-sm-12"> 
                <div class="text-center"><a href="javascript:rincianAddColumn()" class="label label-info add_button"><i class="fa fa-plus"></i> Tambah Dokumen Baru</a></div>
            </div>
            <input type="hidden" class="form-control"  class="form-control"  name="total_file"  placeholder="Total File" id="total_file" value="0">
    </div>
</div>