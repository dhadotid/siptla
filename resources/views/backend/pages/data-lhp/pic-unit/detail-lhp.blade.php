<div class="row" style="padding:0px 10px;">
    <fieldset class="col-md-12" style="min-height: unset !important">    	
        <legend>Data LHP</legend>
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Nomor LHP:</label>
                            <div class="col-sm-12">
                                <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Nomor LHP" id="detail_nomor_lhp" value="{{$data->no_lhp}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Pemeriksa:</label>
                            <div class="col-sm-12">
                                <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Pemeriksa" id="detail_pemeriksa" readonly value="{{$data->dpemeriksa->pemeriksa}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Tanggal LHP:</label>
                            <div class="col-sm-12">
                                <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Tanggal LHP" id="detail_tanggal" readonly value="{{date('d/m/Y',strtotime($data->tanggal_lhp))}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Tahun:</label>
                            <div class="col-sm-12">
                                <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Tahun" id="detail_tahun" readonly value="{{$data->tahun_pemeriksa}}">
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
            
    </fieldset>				
    <fieldset class="col-md-12" style="min-height: unset !important">    	
        <legend>Data Temuan</legend>
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Nomor Temuan:</label>
                            <div class="col-sm-12">
                                <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Nomor Temuan" id="detail_nomor_temuan" readonly value="{{isset($temuan->data) ? '' : $temuan->no_temuan}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                         <div class="form-group">
                            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Temuan:</label>
                            <div class="col-sm-12">
                                <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Temuan" id="detail_temuan" readonly value="{{isset($temuan->data) ? '' : $temuan->temuan}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Nilai Temuan:</label>
                            <div class="col-sm-12">
                                <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Nilai Temuan" id="detail_nilai_temuan" readonly value="{{isset($temuan->data) ? '' : number_format($temuan->nominal,2,',','.')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="exampleTextInput1" class="col-sm-12 control-label text-left">Level Resiko:</label>
                            <div class="col-sm-12">
                                <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Level Resiko" id="detail_level_resiko" readonly value="{{isset($temuan->data) ? '' : (isset($temuan->levelresiko->level_resiko) ? $temuan->levelresiko->level_resiko : '')}}">
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
            </div>
            <div class="row" style="padding:20px;">
                <div class="col-md-6 text-left">
                    @if ($jlhtemuan!=0)
                        Data {{($offset+1)}} dari {{$jlhtemuan}} Temuan
                    @else
                        Data <b>0</b> Temuan
                    @endif
                </div>
                @if ($jlhtemuan!=0)
                    <div class="col-md-3 text-right">
                        @if ($offset!=0)
                            @if ($statusrekom!=null)
                                <a href="javascript:detaillhp({{$id}},{{($offset-1)}},{{$statusrekom}})" class="btn btn-outline btn-primary"><i class="fa fa-caret-square-o-left"></i> Sebelumnya</a>
                            @else
                                <a href="javascript:detaillhp({{$id}},{{($offset-1)}})" class="btn btn-outline btn-primary"><i class="fa fa-caret-square-o-left"></i> Sebelumnya</a>
                            @endif
                            
                        @endif
                    </div>
                    <div class="col-md-3 text-right">
                        @if ($jlhtemuan!=($offset+1)) 
                            @if ($statusrekom!=null)
                                <a href="javascript:detaillhp({{$id}},{{($offset+1)}},{{$statusrekom}})" class="btn btn-outline btn-primary">Selanjutnya <i class="fa    fa-caret-square-o-right"></i></a>
                            @else
                                <a href="javascript:detaillhp({{$id}},{{($offset+1)}})" class="btn btn-outline btn-primary">Selanjutnya <i class="fa    fa-caret-square-o-right"></i></a>
                            @endif   
                            
                        @endif
                    </div>
                @endif
            </div>
        </div>
            
    </fieldset>				
    <div class="col-md-12" style="margin-top:20px">
        <table class="table table-bordered" id="detail-rekomendasi" style="width:100%">
            <thead>
                <tr class="primary">
                    <th class="text-center">No</th>
                    <th class="text-center">Rekomendasi</th>
                    <th class="text-center">Nilai Rekomendasi</th>
                    <th class="text-center">PIC 1</th>
                    <th class="text-center">PIC 2</th>
                    <th class="text-center">Status Rekomendasi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no=1;
                @endphp
                @foreach ($drekomendasi as $key => $item)
                    @php
                        $pic2=explode(',',$item->pic_2_temuan_id);
                    @endphp
                    @if (($item->pic_1_temuan_id==$picunit_id) || (in_array($picunit_id,$pic2)))
                        @if ($statusrekom!=null && $statusrekom!='null')
                            @if($statusrekom==$item->status_rekomendasi_id)
                                <tr>
                                    <td class="text-center">{{$no}}</td>
                                    <td class="text-left">{{$item->rekomendasi}}</td>
                                    <td class="text-center"><a href="javascript:detailrekom({{$item->id}})" class="btn btn-xs btn-success" style="height:unset !important;">Rp. {{number_format($item->nominal_rekomendasi,0,',','.')}} <i class="fa fa-list"></i></a></td>
                                    <td class="text-center">{{isset($item->picunit1->nama_pic) ? $item->picunit1->nama_pic : '' }}</td>
                                    <td class="text-center">{{isset($item->picunit2->nama_pic) ? $item->picunit2->nama_pic : '' }}</td>
                                    <td class="text-center">{{$item->statusrekomendasi->rekomendasi}}</td>
                                </tr>
                                @php
                                    $no++;
                                @endphp
                            @endif
                        @else       
                            <tr>
                                <td class="text-center">{{$no}}</td>
                                <td class="text-left">{{$item->rekomendasi}}</td>
                                <td class="text-center"><a href="javascript:detailrekom({{$item->id}})" class="btn btn-xs btn-success" style="height:unset !important;">Rp. {{number_format($item->nominal_rekomendasi,0,',','.')}} <i class="fa fa-list"></i></a></td>
                                <td class="text-center">{{isset($item->picunit1->nama_pic) ? $item->picunit1->nama_pic : ''}}</td>
                                <td class="text-center">{{isset($item->picunit2->nama_pic) ? $item->picunit2->nama_pic : ''}}</td>
                                <td class="text-center">{{$item->statusrekomendasi->rekomendasi}}</td>
                            </tr>
                            @php
                                $no++;
                            @endphp
                        @endif
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>	
<div class="clearfix"></div>
