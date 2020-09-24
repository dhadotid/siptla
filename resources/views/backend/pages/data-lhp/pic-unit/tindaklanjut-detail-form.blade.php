<div class="panel-group accordion" id="accordion" role="tablist" aria-multiselectable="true"  style="border:1px solid #eee;">
	<div class="panel panel-info">
		<div class="panel-heading" role="tab" id="heading-1">
			<a class="accordion-toggle collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-rangkuman" aria-expanded="false" aria-controls="collapse-rangkuman">
				<h4 class="panel-title">Rangkuman Tindak Lanjut</h4>
				<i class="fa acc-switch"></i>
			</a>
        </div>
        <input type="hidden" name="idrekomendasi" value="{{$rekom->id}}" id="idrekomendasi">
		<div id="collapse-rangkuman" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-1" aria-expanded="false" style="height: 0px;">
			<div class="panel-body">
                <div class="row" style="padding:0 10px;margin-top:10px;">
                    <div class="col-md-12" style="border:1px solid #bbb;border-radius:5px;padding:5px 20px 0px 20px;background:#eee;">

                            <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Data Temuan</h4>
                            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                                <div class="col-md-2" style="margin:0px;padding:0px;margin-bottom:10px;">
                                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Nomor Rekomendasi:</label>
                                        <div class="col-sm-12">
                                            <input type="text"  readonly class="" name="nomor_rekomendasi" placeholder="Nomor Rekomendasi" id="nomor_rekomendasi" value="{{$rekom->nomor_rekomendasi}}" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%;font-size:11px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2" style="margin:0px;padding:0px;margin-bottom:10px;">
                                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Tanggal Penyelesaian:</label>
                                        <div class="col-sm-12">
                                            <input type="text"  readonly class="" name="tgl_penyelesaian" placeholder="Tanggal Penyelesaian" value="{{tgl_indo($rekom->tanggal_penyelesaian)}}" id="tgl_penyelesaian"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%;font-size:11px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8" style="margin:0px;padding:0px;margin-bottom:10px;">
                                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Rekomendasi:</label>
                                        <div class="col-sm-12">
                                            <textarea type="text" class="" name="rekomendasi" placeholder="Rekomendasi" id="rekomendasi" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%;min-height:45px;font-size:11px;">{{$rekom->rekomendasi}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
                <div class="row" style="padding:0 10px;">
                    <div class="col-md-12" style="border:1px solid #bbb;border-radius:5px;padding:5px 20px 0px 20px;margin-top:5px;">

                            <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Action Plan</h4>
                            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                                <div class="col-md-2" style="margin:0px;padding:0px;margin-bottom:10px;">
                                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left fz11" style="font-size:10px;font-style:italic">PIC 1:</label>
                                    </div>
                                </div>
                                <div class="col-md-10" style="margin:0px;padding:0px;margin-bottom:10px;">
                                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <div class="col-sm-12">
                                            {{-- <input type="text"  readonly class="fz11" name="pic_1" placeholder="PIC 1" id="pic_1"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%"> --}}
                                            @if (isset($rekom->picunit1->nama_pic))
                                                <span class="label label-info" style="font-size:12px;">{{$rekom->picunit1->nama_pic}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                                <div class="col-md-2" style="margin:0px;padding:0px;margin-bottom:10px;">
                                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left fz11" style="font-size:10px;font-style:italic">Action Plan PIC 1:</label>
                                    </div>
                                </div>
                                <div class="col-md-10" style="margin:0px;padding:0px;margin-bottom:10px;">
                                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <div class="col-sm-12">
                                            {{-- <textarea type="text" class="fz11" name="action_plan_pic1" placeholder="Action Plan PIC 1" id="action_plan_pic1" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%;min-height:50px"> --}}
                                            <div style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%;min-height:50px;font-size:11px !important;">    
                                                @php
                                                if(isset($pic1['action_plan']))
                                                {
                                                    foreach($pic1['action_plan'] as $k=>$v)
                                                    {
                                                        echo ($v);
                                                    }
                                                }
                                                @endphp
                                            </div>
                                            {{-- </textarea> --}}
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                    @if ($rekom->pic_2_temuan_id!='')

                            <div id="pic2">
                                <div class="row" style="margin:0px;padding:0px;">
                                    <div class="col-md-2" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                            <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">PIC 2:</label>
                                        </div>
                                    </div>
                                    <div class="col-md-10" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                            <div class="col-sm-12">
                                                {{-- <input type="text"  readonly class="fz11" name="pic_2" placeholder="PIC 2" id="pic_1"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%"> --}}
                                                @php
                                                    $pic2list=explode(',',$rekom->pic_2_temuan_id);
                                                @endphp
                                                @foreach ($pic2list as $item)
                                                    @if(isset($pic[$item]))
                                                        <span class="label label-default" style="font-size:12px;">{{$pic[$item]->nama_pic}}</span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                                <div class="row" style="margin:0px;padding:0px;">
                                    <div class="col-md-2" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                            <label for="exampleTextInput1" class="col-sm-12 control-label text-left fz11" style="font-size:10px;font-style:italic">Action Plan PIC 2:</label>
                                        </div>
                                    </div>
                                    <div class="col-md-10" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                            <div class="col-sm-12">
                                                {{-- <textarea type="text" class="fz11" name="action_plan_pic2" placeholder="Action Plan PIC 2" id="action_plan_pic1" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%;min-height:50px"> --}}
                                                <div style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%;min-height:50px;font-size:11px !important;">   
                                                    @php
                                                    if(isset($pic2['action_plan']))
                                                    {
                                                        foreach($pic2['action_plan'] as $k=>$v)
                                                        {
                                                            echo $v;
                                                        }
                                                    }
                                                    @endphp    
                                                </div>
                                                {{-- </textarea> --}}
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>

                    @endif
                    </div>
                </div>
                
                <div class="row" style="padding:0;margin:15px 0 10px">
                    
                    <table id="table-tl-detail" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Unit Kerja</th>
                                <th>Tanggal</th>
                                <th>Tindak Lanjut</th>
                                <th>Dokumen<br>Pendukung</th>
                                <th>Catatan<br>Monev</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @if (isset($pic1['tindak_lanjut']))
                                @foreach ($pic1['tindak_lanjut'] as $item)
                                    <tr>
                                        <td class="text-center">{{$no}}</td>
                                        <td class="text-left" style="width:280px;">
                                            {{(isset($pic[$item->pic_1_id]) ? $pic[$item->pic_1_id]->nama_pic : '')}} 
                                        </td>
                                        <td style="width:120px;">{{tgl_indo($item->tgl_tindaklanjut)}}</td>
                                        <td>{{($item->tindak_lanjut)}}</td>
                                        <td class="text-center" style="width:60px;">
                                            @if (isset($dokumen[$item->id]))
                                                <a data-toggle="tooltip" class="btn btn-xs btn-success" style="height:25px;" target="_blank" title="Lihat Dokumen Pendukung" href="{{url('read-file/'.$dokumen[$item->id]->path)}}"><i class="fa fa-search"></i></a>    
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center" style="width:70px;">
                                            <a href="javascript:editormonev({{$rekom->id}},{{$item->id}})" class="btn btn-xs btn-info"><i class="fa fa-reply"></i></a>
                                        </td>
                                    </tr>
                                @php
                                    $no++;
                                @endphp
                                @endforeach
                            @endif
                            @if (isset($pic2['tindak_lanjut']))
                                @foreach ($pic2['tindak_lanjut'] as $item)
                                    <tr>
                                        <td class="text-center">{{$no}}</td>
                                        <td class="text-left" style="width:280px;">
                                            {{(isset($pic[$item->pic_2_id]) ? $pic[$item->pic_2_id]->nama_pic : '')}} 
                                        </td>
                                        <td style="width:120px;">{{tgl_indo($item->tgl_tindaklanjut)}}</td>
                                        <td>{{($item->tindak_lanjut)}}</td>
                                        <td class="text-center" style="width:60px;">
                                            @if (isset($dokumen[$item->id]))
                                                <a data-toggle="tooltip" class="btn btn-xs btn-success" style="height:25px;" target="_blank" title="Lihat Dokumen Pendukung" href="{{url('read-file/'.$dokumen[$item->id]->path)}}"><i class="fa fa-search"></i></a>    
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center" style="width:70px;">
                                            <a href="javascript:editormonev({{$rekom->id}},{{$item->id}})" class="btn btn-xs btn-info"><i class="fa fa-reply"></i></a>
                                        </td>
                                    </tr>
                                @php
                                    $no++;
                                @endphp
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="row" style="padding:0;margin:15px 0 10px">
                    <div class="form-group" style="padding-top:20px;" id="div_status_rekomendasi">
                            <label for="exampleTextInput1" class="col-sm-2 control-label text-right">Tanggal Penyelesaian :
                            </label>
                            <div class="col-sm-3">
                                @if ($rekom->publish_pic_1==1)
                                    <input type='date' class="form-control" name="tgl_selesai" id="tgl_selesai" value="{{$rekom->tanggal_penyelesaian}}" readonly/>
                                @else
                                    <input type='date' class="form-control" name="tgl_selesai" id="tgl_selesai" value="{{$rekom->tanggal_penyelesaian}}"/>
                                @endif
                            </div>
                        </div>
                </div>
            </div>
        </div>
	</div>
</div>
{{-- <div class="panel-group accordion" id="accordion2" role="tablist" aria-multiselectable="true"  style="border:1px solid #eee;">
	<div class="panel panel-info">
		<div class="panel-heading" role="tab" id="heading-2">
			<a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapse-review" aria-expanded="true" aria-controls="collapse-review">
				<h4 class="panel-title">Form Review</h4>
				<i class="fa acc-switch"></i>
			</a>
		</div>
		<div id="collapse-review" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-2" aria-expanded="true">
			<div class="panel-body">
                <div class="row" style="padding:0 10px;" id="monev-pic1">
                        <div class="col-md-12" style="border-radius:5px;padding:5px 0px 0px 0px;margin-top:5px;">

                                @if ($rekom->publish_pic_1==1)
                                    <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Catatan Monev
                                    &nbsp;<span class="text-info"><i>(Sudah Publish Ke Auditor Junior)</i></span>
                                    </h4>
                                @else
                                    <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Catatan Monev</h4>
                                @endif
                            
                                <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                                    <div class="col-md-12" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                            <div class="col-sm-12">
                                                @if ($rekom->publish_pic_1==1)
                                                   <textarea class="fz11" name="catatan_monev" placeholder="" id="catatan_monev"  style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%;min-height:50px" readonly>{{$rekom->review_monev}}</textarea>
                                                @else
                                                    <textarea class="fz11" name="catatan_monev" placeholder="" id="catatan_monev"  style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%;min-height:50px">{{$rekom->review_monev}}</textarea>
                                                @endif
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                </div> <div class="row" style="margin-top:10px;">
                    <div class="col-md-12">
                        
                    </div>
                </div>
                
            </div>
        </div>
	</div>
</div> --}}
<div class="panel-group accordion" id="accordion3" role="tablist" aria-multiselectable="true"  style="border:1px solid #eee;">
	<div class="panel panel-info">
		<div class="panel-heading" role="tab" id="heading-3">
			<a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse-rangkuman" aria-expanded="true" aria-controls="collapse-rangkuman">
				<h4 class="panel-title">Form Rangkuman</h4>
				<i class="fa acc-switch"></i>
			</a>
        </div>
        <input type="hidden" id="tahun_thn" name="tahun">
		<div id="collapse-rangkuman" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-3" aria-expanded="true">
			<div class="panel-body">
               <div class="row" style="padding:0 10px;">
                        <div class="col-md-12" style="border-radius:5px;padding:5px 0px 0px 0px;margin-top:5px;">

                                @if ($rekom->publish_pic_1==1)
                                    <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Rangkuman Tindak Lanjut
                                    &nbsp;<span class="text-info"><i>(Sudah Publish Ke Auditor Junior)</i></span>
                                    </h4>
                                @else
                                    <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Rangkuman Tindak Lanjut</h4>
                                @endif
                            
                                <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                                    <div class="col-md-12" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                            <div class="col-sm-12">
                                                @if ($rekom->publish_pic_1==1)
                                                   {!!$rekom->rangkuman_rekomendasi!!}
                                                @else
                                                    <textarea class="fz11" name="txt_rangkuman_rekomendasi" placeholder="" id="catatan_monev"  style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%;min-height:50px">{{$rekom->rangkuman_rekomendasi}}</textarea>
                                                @endif
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                </div>
                 <div class="row" style="margin-top:10px;">
                    <div class="col-md-12">
                        <div class="form-group" style="margin-top:-20px;">
                            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Dokumen Pendukung:</label>
                            <div class="col-sm-11">
                                @if ($rekom->publish_pic_1==0)
                                    <input type="file" class="form-control"  id="file_pendukung"  name="file_pendukung"  placeholder="Dokumen Pendukung">
                                    <small><i>*Biarkan Kosong Jika Tidak Ingin Di Ganti</i></small>
                                @else
                                    @if ($rekom->file_pendukung!='')
                                        <a data-toggle="tooltip" title="Lihat Dokumen" href="{{url('read-file/'.$rekom->file_pendukung)}}" target="_blank" class="btn btn-info"><i class="fa fa-search"></i> Lihat Dokumen</a>
                                    @endif
                                @endif
                            </div>
                            <div class="col-sm-1">
                                @if ($rekom->file_pendukung!='')
                                    <a data-toggle="tooltip" title="Lihat Dokumen" href="{{url('read-file/'.$rekom->file_pendukung)}}" target="_blank" class="btn btn-info"><i class="fa fa-search"></i></a>
                                @endif
                            </div>
                        </div>    
                    </div>
                 </div>
                
            </div>
        </div>
	</div>
</div>
