<div class="row" style="padding:0 10px;">
    <div class="col-md-12" style="border:1px solid #bbb;border-radius:5px;padding:5px 20px 0px 20px;background:#eee;">
            <input type="hidden" name="idlhp" id="idlhp" value="{{$tl->lhp_id}}">
            <input type="hidden" name="temuan_id" id="temuan_id" value="{{$temuan->id}}">
            <input type="hidden" name="rekomendasi_id" id="rekomendasi_id" value="{{$rekomendasi->id}}">
            <input type="hidden" name="idformtindaklanjut" id="idformtindaklanjut" value="{{time()}}">
            <input type="hidden" name="jenis" id="jenis" value="{{$rekomendasi->rincian}}">
            <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Data Temuan</h4>
            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                
                <div class="col-md-3" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Nomor Temuan:</label>
                        <div class="col-sm-12">
                            <input type="text" class="d_nomor_temuan" name="nomor_temuan" placeholder="Nomor Temuan" id="nomor_temuan"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%" value="{{isset($temuan->no_temuan) ? $temuan->no_temuan : ''}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-9" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Temuan:</label>
                        <div class="col-sm-12">
                            <input type="text" class="d_temuan" name="temuan" placeholder="Temuan" id="temuan"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%" value="{{isset($temuan->temuan) ? $temuan->temuan : ''}}">
                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>
<div class="row" style="padding:0 10px;margin-top:10px;">
    <div class="col-md-12" style="border:1px solid #bbb;border-radius:5px;padding:5px 20px 0px 20px;background:#eee;">

            <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Data Rekomendasi</h4>
            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                
                <div class="col-md-8" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Rekomendasi:</label>
                        <div class="col-sm-12">
                            <input type="text" class="d_nomor_rekomendasi" name="nomor_rekomendasi" placeholder="Rekomendasi" id="nomor_rekomendasi"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%" value="{{isset($rekomendasi->rekomendasi) ? $rekomendasi->rekomendasi : ''}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Tanggal Penyelesaian:</label>
                        <div class="col-sm-12">
                            <input type="text" class="d_tgl_penyelesaian" name="tgl_penyelesaian" placeholder="Tanggal Penyelesaian" id="tgl_penyelesaian"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%" value="{{isset($rekomendasi->tanggal_penyelesaian) ? tgl_indo($rekomendasi->tanggal_penyelesaian) : ''}}">
                        </div>
                    </div>
                </div>
               
            </div>

    </div>
</div>
<div class="row">
    <div class="col-md-12" style="margin-top:10px;">
        <div class="form-group">
            <label for="datetimepicker2" class="col-sm-12 control-label text-left">Tanggal Tindak Lanjut</label>
            <div class="col-sm-12">
                <div class='input-group date' id='datetimepicker2' data-plugin="datepicker" data-date-format="dd/mm/yyyy">
                    <input type='date' class="form-control" name="tgl_tindak_lanjut" id="tgl_tindak_lanjut" value="{{($tl->tgl_tindaklanjut)}}"/>
                    <span class="input-group-addon bg-info text-white">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <input type="hidden" name="idtl" value="{{$tl->id}}">
        <div class="form-group" style="margin-top:-20px;">
            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Rencana Tindak Lanjut:
            </label>
            <div class="col-sm-12">
                <textarea class="form-control"  name="action_plan" placeholder="Rencana Tindak Lanjut" id="action_plan">{{$tl->action_plan}}</textarea>
            </div>
        </div>
       <div class="form-group" style="margin-top:-20px;">
            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Tindak Lanjut:
            </label>
            <div class="col-sm-12">
                <textarea class="form-control"  name="tindak_lanjut" placeholder="Tindak Lanjut" id="tindak_lanjut">{{$tl->tindak_lanjut}}</textarea>
            </div>
        </div>
       
        <div class="form-group" style="margin-top:-20px;">
            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Dokumen Pendukung:</label>
            <div class="col-sm-12">
                <input type="file" class="form-control"  id="add-dokumen-1" onchange="insertFile(1)" name="dokumen_pendukung_1"  placeholder="Dokumen Pendukung">

                <br><div class="field_wrapper"></div>
            
                <small><i>*Biarkan Kosong Jika Tidak Ingin Di Ganti</i></small>
            </div>

            <div class="col-sm-12"> 
                    <div class="text-center"><a href="javascript:tindaklanjutAddColumn()" class="label label-info add_button"><i class="fa fa-plus"></i> Tambah Dokumen Baru</a></div>
                </div>
                <input type="hidden" class="form-control"  class="form-control"  name="total_file"  placeholder="Total File" id="total_file" value="0">

        </div>    
        
    </div>
</div>
