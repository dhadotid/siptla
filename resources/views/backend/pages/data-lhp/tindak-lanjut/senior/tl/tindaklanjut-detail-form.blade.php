<div class="panel-group accordion" id="accordion" role="tablist" aria-multiselectable="true"  style="border:1px solid #eee;">
	<div class="panel panel-info">
		<div class="panel-heading" role="tab" id="heading-1">
			<a class="accordion-toggle collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-rangkuman" aria-expanded="false" aria-controls="collapse-rangkuman">
				<h4 class="panel-title">Rangkuman Tindak Lanjut</h4>
				<i class="fa acc-switch"></i>
			</a>
		</div>
		<div id="collapse-rangkuman" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-1" aria-expanded="false" style="height: 0px;">
			<div class="panel-body">
            <input type="hidden" name="idrekomendasi" id="idrekomendasi" value="{{$rekom->id}}">
            <input type="hidden" name="publish" id="publish">
            <input type="hidden" name="tahun" id="tahun_junior">


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
                                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left fz11" style="font-size:10px;font-style:italic">Action Plan PIC:</label>
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
                                {{-- <div class="row" style="margin:0px;padding:0px;">
                                    <div class="col-md-2" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                            <label for="exampleTextInput1" class="col-sm-12 control-label text-left fz11" style="font-size:10px;font-style:italic">Action Plan PIC 2:</label>
                                        </div>
                                    </div>
                                    <div class="col-md-10" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                            <div class="col-sm-12">
                                                {{-- <textarea type="text" class="fz11" name="action_plan_pic2" placeholder="Action Plan PIC 2" id="action_plan_pic1" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%;min-height:50px"> 
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
                                                {{-- </textarea>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div> --}}
                                <div class="row" style="padding:0 10px;">
                                    <div class="col-md-12" style="border-radius:5px;padding:5px 0px 0px 0px;margin-top:5px;">
                                            <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Rangkuman</h4>
                                            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                                                <div class="col-md-12" style="margin:0px;padding:0px;margin-bottom:10px;">
                                                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                                        <div class="col-sm-12">
                                                                {{-- <textarea class="fz11" name="cata" placeholder="" id="cata"  style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%;min-height:50px"></textarea> --}}
                                                                {!!$rekom->rangkuman_rekomendasi!!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>

                    @endif
                    </div>
                </div>
                @if ($rekom->pic2_temuan_2!='')
                    <div class="row" style="padding:0 10px;" id="monev-pic1">
                        <div class="col-md-12" style="border:1px solid #bbb;border-radius:5px;padding:5px 20px 0px 20px;margin-top:5px;">

                                <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Catatan Monvev PIC 1</h4>
                            
                                <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                                    <div class="col-md-12" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                            <div class="col-sm-12">
                                                <textarea class="fz11" name="catatan_monev" placeholder="Catatan Monev PIC 1" id="catatan_monev"  style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%;min-height:50px"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                @endif
                <div class="row" style="padding:0;margin:15px 0 10px">
                    <table id="" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:30px;"><div style="width:30px;">No</div></th>
                                <th style="width:250px;"><div style="width:250px;">Unit Kerja</div></th>
                                <th style="width:100px;"><div style="width:100px;">Tanggal</div></th>
                                <th>Tindak Lanjut</th>
                                <th style="width:60px;"><div style="width:60px;">Dokumen<br>Pendukung</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @if (isset($pic1['tindak_lanjut']))
                                @foreach ($pic1['tindak_lanjut'] as $item)
                                    <tr>
                                        <td class="text-center"><div style="width:30px;">{{$no}}</div></td>
                                        <td class="text-left">
                                            <div style="width:250px;">
                                                {{(isset($pic[$item->pic_1_id]) ? $pic[$item->pic_1_id]->nama_pic : '')}} 
                                            </div>
                                        </td>
                                        <td style="width:100px;">{{tgl_indo($item->tgl_tindaklanjut)}}</td>
                                        <td>{{($item->tindak_lanjut)}}</td>
                                        <td class="text-center" style="width:60px;">
                                        @if (isset($dokumen[$item->id]))
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-file"></i></button>
                                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    @foreach ($dokumen[$item->id] as $dk)
                                                        <li><a href="{{url('read-pdf/'.$dk->path)}}" target="_blank"><i class="fa fa-chevron-right"></i> {{$dk->nama_dokumen}}</a> </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            
                                        @else
                                            -
                                        @endif
                                        {{-- 
                                            @if (isset($dokumen[$item->id]))
                                                <a data-toggle="tooltip" class="btn btn-xs btn-success" style="height:25px;" target="_blank" title="Lihat Dokumen Pendukung" href="{{url('read-file/'.$dokumen[$item->id]->path)}}"><i class="fa fa-search"></i></a>    
                                            @else
                                                -
                                            @endif
                                            --}}
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
                                        <td class="text-center"><div style="width:30px;">{{$no}}</div></td>
                                        <td class="text-left">
                                            <div style="width:250px;">
                                                {{(isset($pic[$item->pic_2_id]) ? $pic[$item->pic_2_id]->nama_pic : '')}} 
                                            </div>
                                        </td>
                                        <td style="width:100px;">{{tgl_indo($item->tgl_tindaklanjut)}}</td>
                                        <td>{{($item->tindak_lanjut)}}</td>
                                        <td class="text-center" style="width:60px;">
                                        @if (isset($dokumen[$item->id]))
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-file"></i></button>
                                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    @foreach ($dokumen[$item->id] as $dk)
                                                        <li><a href="{{url('read-pdf/'.$dk->path)}}" target="_blank"><i class="fa fa-chevron-right"></i> {{$dk->nama_dokumen}}</a> </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            
                                        @else
                                            -
                                        @endif
                                        {{--
                                            @if (isset($dokumen[$item->id]))
                                                <a data-toggle="tooltip" class="btn btn-xs btn-success" style="height:25px;" target="_blank" title="Lihat Dokumen Pendukung" href="{{url('read-file/'.$dokumen[$item->id]->path)}}"><i class="fa fa-search"></i></a>    
                                            @else
                                                -
                                            @endif
                                            --}}
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
            </div>
        </div>
	</div>
</div>
<div class="panel-group accordion" id="accordion2" role="tablist" aria-multiselectable="true"  style="border:1px solid #eee;">
	<div class="panel panel-info">
		<div class="panel-heading" role="tab" id="heading-2">
			<a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapse-review" aria-expanded="true" aria-controls="collapse-review">
				<h4 class="panel-title">Form Review SPI</h4>
				<i class="fa acc-switch"></i>
			</a>
		</div>
		<div id="collapse-review" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-2" aria-expanded="true">
			<div class="panel-body">
                 <div class="row" style="padding:0;" id="monev-pic1">
                    <div class="col-md-12" style="border:1px solid #bbb;border-radius:5px;padding:5px 20px 0px 20px;margin-top:5px;">

                                @if ($rekom->published!='1')
                                    <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Review SPI
                                    &nbsp;<span class="text-danger"><i>(Auditor Junior Belum Melakukan Review)</i></span>
                                    </h4>
                                @elseif ($rekom->rekom_publish==1)
                                    <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Review SPI
                                    &nbsp;<span class="text-info"><i>(Sudah DI Publish)</i></span>
                                    </h4>
                                @else
                                    <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Review SPI</h4>
                                @endif
                            {{-- <h4 style="margin:0px;padding:0px;margin-bottom:10px;"></h4> --}}
                        
                            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                                <div class="col-md-12" style="margin:0px;padding:0px;margin-bottom:10px;">
                                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                                        <div class="col-sm-12">
                                            @if ($rekom->published!='1')
                                                {!!$rekom->review_spi!!}
                                            @else
                                                <br>
                                                <b><u>Review Auditor Junior : </u></b><br>
                                                {!!$rekom->review_spi!!}
                                                <br>
                                                Form Review Auditor Senior
                                                @if ($rekom->rekom_publish==1)
                                                    {!!$rekom->review_auditor!!}
                                                @else
                                                    <textarea class="fz11" name="review_spi" placeholder="" id="review_spi"  style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%;min-height:50px">{!!$rekom->review_auditor!!}</textarea>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="row" style="margin-top:10px;">
                    <div class="col-md-12">
                        <div class="form-group" style="padding-top:20px;" id="div_status_rekomendasi">
                            <label for="exampleTextInput1" class="col-sm-2 control-label text-right">Status Rekomendasi :
                            </label>
                            <div class="col-sm-3">
                                <select name="status_rekomendasi" {{($rekom->rekom_publish==1 ? 'disabled' : '')}}  class="form-control" data-plugin="select2" id="status_rekomendasi">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($status as $key=>$item)
                                        @if ($item->id==$rekom->status_rekomendasi_id)
                                            <option value="{{$item->id}}" selected="selected">{{$item->rekomendasi}}</option>
                                        @else
                                            <option value="{{$item->id}}">{{$item->rekomendasi}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
