<div class="row" style="padding:0 10px;">
    <div class="col-md-12" style="border:1px solid #bbb;border-radius:5px;padding:5px 20px 0px 20px;background:#eee;">
            <input type="hidden" name="idlhp" id="idlhp" value="{{$data->id}}">
            <input type="hidden" name="temuan_id" id="temuan_id" value="{{$temuan->id}}">
            <input type="hidden" name="rekomendasi_id" id="rekomendasi_id" value="{{$rekomendasi->id}}">
            <input type="hidden" name="idformtindaklanjut" id="idformtindaklanjut" value="{{time()}}">
            <input type="hidden" name="jenis" id="jenis" value="{{$rekomendasi->rincian}}">
            <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Data Temuan</h4>
            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                
                <div class="col-md-12" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Nomor Temuan:</label>
                        <div class="col-sm-12">
                            <input type="text" class="d_nomor_temuan" name="nomor_temuan" placeholder="Nomor Temuan" id="nomor_temuan"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%" value="{{isset($temuan->no_temuan) ? $temuan->no_temuan : ''}}">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:30px;">
                    <div class="col-md-6 text-right">
                        @if (count($dtemuan)!=0)
                            Data {{($temuan_idx+1)}} dari {{count($dtemuan)}} Temuan
                        @else
                            Data <b>0</b> Temuan
                        @endif
                    </div>
                    @if (count($dtemuan)!=0)
                        <div class="col-md-3 text-right">
                            @if ($temuan_idx!=0)
                                <a href="javascript:othertemuan_unitkerja('{{$idlhp.'__'.$temuan_id.'_'.($temuan_idx-1).'__'.$rekom_id.'_0'}}')" class="btn btn-outline btn-primary btn-xs"><i class="fa fa-caret-square-o-left"></i> Sebelumnya</a>
                            @endif
                        </div>
                        <div class="col-md-3 text-right">
                            @if (count($dtemuan)!=($temuan_idx+1)) 
                                    <a href="javascript:othertemuan_unitkerja('{{$idlhp.'__'.$temuan_id.'_'.($temuan_idx+1).'__'.$rekom_id.'_0'}}')" class="btn btn-outline btn-primary btn-xs">Selanjutnya <i class="fa    fa-caret-square-o-right"></i></a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

    </div>
</div>
<div class="row" style="padding:0 10px;margin-top:10px;">
    <div class="col-md-12" style="border:1px solid #bbb;border-radius:5px;padding:5px 20px 0px 20px;background:#eee;">

            <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Data Rekomendasi</h4>
            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                
                <div class="col-md-6" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Rekomendasi:</label>
                        <div class="col-sm-12">
                            <textarea class="d_nomor_rekomendasi" name="nomor_rekomendasi" placeholder="Rekomendasi" id="nomor_rekomendasi"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%">{{isset($rekomendasi->rekomendasi) ? $rekomendasi->rekomendasi : ''}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Tanggal Penyelesaian:</label>
                        <div class="col-sm-12">
                            <input type="text" class="d_tgl_penyelesaian" name="tgl_penyelesaian" placeholder="Tanggal Penyelesaian" id="tgl_penyelesaian"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%" value="{{isset($rekomendasi->tanggal_penyelesaian) ? tgl_indo($rekomendasi->tanggal_penyelesaian) : ''}}">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:30px;">
                    <div class="col-md-6 text-right">
                        @if (count($drekomendasi)!=0)
                            Data {{($rekom_idx+1)}} dari {{count($drekomendasi)}} Rekomendasi
                        @else
                            Data <b>0</b> Rekomendasi
                        @endif
                    </div>
                    @if (count($drekomendasi)!=0)
                        <div class="col-md-3 text-right">
                            @if ($rekom_idx!=0)
                                <a href="javascript:othertemuan('{{$idlhp.'__'.$temuan_id.'_'.($temuan_idx).'__'.$rekom_id.'_'.($rekom_idx-1)}}')" class="btn btn-outline btn-primary btn-xs"><i class="fa fa-caret-square-o-left"></i> Sebelumnya</a>
                            @endif
                        </div>
                        <div class="col-md-3 text-right">
                            @if (count($drekomendasi)!=($rekom_idx+1)) 
                                    <a href="javascript:othertemuan('{{$idlhp.'__'.$temuan_id.'_'.($temuan_idx).'__'.$rekom_id.'_'.($rekom_idx+1)}}')" class="btn btn-outline btn-primary btn-xs">Selanjutnya <i class="fa    fa-caret-square-o-right"></i></a>
                            @endif
                        </div>
                    @endif
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
                    <input type='date' class="form-control" name="tgl_tindak_lanjut" id="tgl_tindak_lanjut" value="{{date('Y-m-d')}}"/>
                    <span class="input-group-addon bg-info text-white">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group" style="margin-top:-20px;">
            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Rencana Tindak Lanjut:
            </label>
            <div class="col-sm-12">
                @php
                    $get_tl=\App\Models\TindakLanjutTemuan::where('rekomendasi_id',$rekomendasi->id)->get();
                    $action_plan='';
                    foreach($get_tl as $kg=>$vg)
                    {
                        if($vg!='')
                        {
                            $action_plan=$vg->action_plan.'<br>';
                        }
                    }
                @endphp
                <textarea class="form-control"  name="action_plan" placeholder="Rencana Tindak Lanjut" id="action_plan">{{$action_plan}}</textarea>
            </div>
        </div>
       <div class="form-group" style="margin-top:-20px;">
            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Tindak Lanjut:
            </label>
            <div class="col-sm-12">
                <textarea class="form-control"  name="tindak_lanjut" placeholder="Tindak Lanjut" id="tindak_lanjut"></textarea>
            </div>
        </div>
        @php
            $idformtl=substr(time(),0,5).''.$rekomendasi->id;
        @endphp
        <input type="hidden" id="form_tl" name="form_tl" value="{{$idformtl}}">
        <input type="hidden" id="csrf_token" name="csrf_token" value="{{csrf_token()}}">
        <div class="form-group" style="margin-top:-20px;">
            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Dokumen Pendukung:</label>
                <div class="col-sm-6">
                    <input type="text" name="nama_file" id="nama-file-1" class="form-control" placeholder="Nama File">
                </div>
                <div class="col-sm-5">
                    <input type="file" class="form-control" onchange="uploadfile(this,1)" id="add-dokumen-1"  name="dokumen_pendukung"  placeholder="Dokumen Pendukung" accept=".doc,.docx,.pdf,.xls,.xlsx">
                    <span style="text-style:italic;font-weight:bold;"></span>
                </div>
                <div class="col-sm-1">
                    <div class="btn btn-success" id="ok-1" style="display:none"><i class="fa fa-check"></i></div>
                    <div class="btn btn-danger" id="fail-1" style="display:none"><i class="fa fa-close"></i></div>
                </div> 
            <div class="field_wrapper"></div>
            <div class="col-sm-12"> 
                <div class="text-center"><a href="javascript:add_kolom()" class="label label-info add_button"><i class="fa fa-plus"></i> Tambah Dokumen Baru</a></div>
            </div>
        </div>    
        @if ($rekomendasi->rincian!='')  
            {{-- <div class="form-group">
                <div class="col-sm-12">
                    <a href="javascript:updaterincian_unitkerja('{{isset($rekomendasi->id) ? $rekomendasi->rincian : 0}}',{{isset($rekomendasi->id) ? $rekomendasi->id_temuan.','.$rekomendasi->id : '0,0'}})" onclick=""><u>Update Rincian Tindak Lanjut</u></a>
                </div>
            </div>     --}}
        @endif
    </div>
</div>
