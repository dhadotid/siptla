
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
                    @if ($rekom->pic2_temuan_2!='')

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
                                                <input type="text"  readonly class="fz11" name="pic_2" placeholder="PIC 2" id="pic_1"readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%">
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
                
                <div class="row" style="padding:0;margin:5px 0px 10px 0px">
                    <table id="table-tl-detail" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Unit Kerja</th>
                                <th>Tanggal</th>
                                <th>Tindak Lanjut</th>
                                <th>Dokumen<br>Pendukung</th>
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
                                        <td class="text-left">
                                            {{(isset($pic[$item->pic_1_id]) ? $pic[$item->pic_1_id]->nama_pic : '')}} 
                                        </td>
                                        <td style="width:100px;">{{tgl_indo($item->tgl_tindaklanjut)}}</td>
                                        <td>{{($item->tindak_lanjut)}}</td>
                                        <td class="text-center" style="width:60px;">
                                            @if (isset($dokumen[$item->id]))
                                                <a data-toggle="tooltip" class="btn btn-xs btn-success" style="height:25px;" target="_blank" title="Lihat Dokumen Pendukung" href="{{url('read-file/'.$dokumen[$item->id]->path)}}"><i class="fa fa-search"></i></a>    
                                            @else
                                                -
                                            @endif
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
                                        <td class="text-left">
                                            {{(isset($pic[$item->pic_2_id]) ? $pic[$item->pic_2_id]->nama_pic : '')}} 
                                        </td>
                                        <td style="width:100px;">{{tgl_indo($item->tgl_tindaklanjut)}}</td>
                                        <td>{{($item->tindak_lanjut)}}</td>
                                        <td class="text-center" style="width:60px;">
                                            @if (isset($dokumen[$item->id]))
                                                <a data-toggle="tooltip" class="btn btn-xs btn-success" style="height:25px;" target="_blank" title="Lihat Dokumen Pendukung" href="{{url('read-file/'.$dokumen[$item->id]->path)}}"><i class="fa fa-search"></i></a>    
                                            @else
                                                -
                                            @endif
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
            