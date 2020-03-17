<div class="row" style="padding:0px 10px;">
    <fieldset class="col-md-6">    	
        <legend>Data LHP</legend>
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nomor LHP:</label>
                    <div class="col-sm-8">
                        <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Nomor LHP" id="detail_nomor_lhp" value="{{$data->no_lhp}}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Kode LHP:</label>
                    <div class="col-sm-8">
                        <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Kode LHP" id="detail_kode_lhp" value="{{$data->kode_lhp}}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Judul LHP:</label>
                    <div class="col-sm-8">
                        <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Judul LHP" id="detail_judul_lhp" value="{{$data->judul_lhp}}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Pemeriksa:</label>
                    <div class="col-sm-8">
                        <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Pemeriksa" id="detail_pemeriksa" readonly value="{{$data->dpemeriksa->pemeriksa}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Tanggal LHP:</label>
                    <div class="col-sm-8">
                        <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Tanggal LHP" id="detail_tanggal" readonly value="{{date('d/m/Y',strtotime($data->tanggal_lhp))}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Tahun Pemeriksa:</label>
                    <div class="col-sm-8">
                        <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Tahun" id="detail_tahun" readonly value="{{$data->tahun_pemeriksa}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Jenis Audit:</label>
                    <div class="col-sm-8">
                        <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Jenis Audit" id="detail_jenis_audit" readonly value="{{$data->djenisaudit->jenis_audit}}">
                    </div>
                </div>
                {{-- <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Review:</label>
                    <div class="col-sm-8">
                        <textarea style="font-weight:bold;" class="form-control custom-input" placeholder="Review" id="detail_review" readonly>{!!$data->review!!}</textarea>
                    </div>
                </div> --}}
            </div>
        </div>
            
    </fieldset>				
    <fieldset class="col-md-6">    	
        <legend>Data Temuan</legend>
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nomor Temuan:</label>
                    <div class="col-sm-8">
                        <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Nomor Temuan" id="detail_nomor_temuan" readonly value="{{isset($temuan->data) ? '' : $temuan->no_temuan}}">
                    </div>
                </div>
                 <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Temuan:</label>
                    <div class="col-sm-8">
                        <textarea style="font-weight:bold;" class="form-control custom-input" placeholder="Temuan" id="detail_temuan" readonly>{{isset($temuan->data) ? '' : $temuan->temuan}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Nilai Temuan:</label>
                    <div class="col-sm-8">
                        <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Nilai Temuan" id="detail_nilai_temuan" readonly value="{{isset($temuan->data) ? '' : number_format($temuan->nominal,2,',','.')}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Level Resiko:</label>
                    <div class="col-sm-8">
                        <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="Level Resiko" id="detail_level_resiko" readonly value="{{isset($temuan->data) ? '' : (isset($temuan->levelresiko->level_resiko) ? $temuan->levelresiko->level_resiko : '')}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right">PIC Temuan:</label>
                    <div class="col-sm-8">
                        <input type="text" style="font-weight:bold;" class="form-control custom-input" placeholder="PIC Temuan" id="detail_pic_temuan" readonly value="{{isset($temuan->data) ? '' : (isset($temuan->picunit->nama_pic) ? $temuan->picunit->nama_pic : '')}}">
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
                    @if ($statusrekom!=null && $statusrekom!='null')
                        @if($statusrekom==$item->status_rekomendasi_id)
                            <tr>
                                <td class="text-center">{{$no}}</td>
                                <td class="text-left">{{$item->rekomendasi}}</td>
                                <td class="text-right">{{number_format($item->nominal_rekomendasi,2,',','.')}}</td>
                                <td class="text-center">{{isset($item->picunit1->nama_pic) ? $item->picunit1->nama_pic : '' }}</td>
                                <td class="text-center">{{isset($item->picunit2->nama_pic) ? $item->picunit2->nama_pic : '' }}</td>
                                <td class="text-center">{{isset($item->statusrekomendasi->rekomendasi) ? $item->statusrekomendasi->rekomendasi : ''}}</td>
                            </tr>
                            @php
                                $no++;
                            @endphp
                        @endif
                    @else       
                        <tr>
                            <td class="text-center">{{$no}}</td>
                            <td class="text-left">{{$item->rekomendasi}}</td>
                            <td class="text-right">{{number_format($item->nominal_rekomendasi,2,',','.')}}</td>
                            <td class="text-center">{{isset($item->picunit1->nama_pic) ? $item->picunit1->nama_pic : ''}}</td>
                            <td class="text-center">{{isset($item->picunit2->nama_pic) ? $item->picunit2->nama_pic : ''}}</td>
                            <td class="text-center">{{isset($item->statusrekomendasi->rekomendasi) ? $item->statusrekomendasi->rekomendasi : ''}}</td>
                        </tr>
                        @php
                            $no++;
                        @endphp
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>	
<div class="clearfix"></div>
