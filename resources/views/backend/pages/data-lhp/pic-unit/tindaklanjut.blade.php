@extends('backend.layouts.master')
@section('title')
    <title>Data Tindak Lanjut </title>
@endsection

@section('content')
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				
                
                {{-- @if (!Auth::user()->level==1 || Auth::user()->level==2) --}}
                    <div class="row">
                        <div class="col-md-9">
                            <span class="widget-title">Data Tindak Lanjut</span>
                        </div>
                        <div class="col-md-1 text-right" style="padding-top:10px;">Tahun</div>
                        <div class="col-md-2 text-left">
                            <select name="tahun" id="tahun" class="form-control text-left" onchange="getdata(this.value)" style="width:100%">
                                @for ($i = date('Y'); $i >= (date('Y')-20); $i--)
                                    @if ($tahun==$i)
                                        <option value="{{$i}}" selected="selected"}}>{{$i}}</option>
                                    @else
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                        
                    </div>
                    
                {{-- @endif --}}
            </header>
			<hr class="widget-separator">
			<div class="widget-body">
                 
                <div class="panel-group accordion" id="accordion" role="tablist" aria-multiselectable="true"  style="border:1px solid #eee;">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading-1">
							<a class="accordion-toggle collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-1" aria-expanded="false" aria-controls="collapse-1">
								<h4 class="panel-title ">Filter PENCARIAN</h4>
								<i class="fa acc-switch"></i>
							</a>
						</div>
						<div id="collapse-1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-1" aria-expanded="false" style="height: 0px;">
							<div class="panel-body">
                                
                                <div class="form-horizontal">
                                    
                                    <div class="form-group">
                                        <label for="my-input" class="col-md-12"><h5>Masukan Parameter Cetak Laporan di bawah ini :</h5></label>
                                    </div>
                                    {{-- <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Tanggal Awal</label>
                                        <div class="col-md-3">
                                            <div class='input-group date' id='datetimepicker' data-plugin="datepicker" data-date-format="dd/mm/yyyy">
                                                <input type='text' class="form-control" name="tanggal_awal" id="tanggal_awal" readonly value="01/{{date('m/'.$tahun)}}"/>
                                                <span class="input-group-addon bg-info text-white">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Tanggal Akhir</label>
                                        <div class="col-md-3">
                                            <div class='input-group date' id='datetimepicker2' data-plugin="datepicker" data-date-format="dd/mm/yyyy">
                                                <input type='text' class="form-control" name="tanggal_akhir" id="tanggal_akhir" readonly value="{{date('d/m/'.$tahun)}}"/>
                                                <span class="input-group-addon bg-info text-white">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Pemeriksa</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="pemeriksa" id="pemeriksa" onchange="loaddata()">
                                                <option>&nbsp;</option>
                                                @foreach ($pemeriksa as $item)
                                                    <option value="{{$item->id}}">{{$item->code}} - {{$item->pemeriksa}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Nomor LHP</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="no_lhp" id="no_lhp" onchange="loaddata()">
                                                <option>&nbsp;</option>
                                                @foreach ($lhp as $item)
                                                    <option value="{{$item->id_lhp}}">{{$item->no_lhp}} - {{substr($item->judul_lhp,0,80)}}...</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Nomor Temuan</label>
                                        <div class="col-md-6" id="select-temuan">
                                            <select class="select2 form-control" name="no_temuan" id="no_temuan" onchange="loaddata()"></select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Nomor Rekomendasi</label>
                                        <div class="col-md-6" id="select-rekomendasi">
                                            <select class="select2 form-control" name="no_rekomendasi" id="no_rekomendasi" onchange="loaddata()"></select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Status Rekomendasi</label>
                                        <div class="col-md-6">
                                             <select class="select2 form-control" name="status_rekomendasi" id="status_rekomendasi" onchange="loaddata()">
                                                <option value="">-Pilih-</option>
                                            @php
                                                $statusrekom=\App\Models\StatusRekomendasi::all();
                                                foreach($statusrekom as $k=>$v)
                                                {
                                                    echo '<option value="'.$v->id.'">'.$v->rekomendasi.'</option>';
                                                }
                                            @endphp
                                            </select>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
				</div>
				<div id="data">
                    <div class="table-responsive">
                        <div class="row" style="margin-bottom:20px;">
                            <div class="col-md-8">&nbsp;</div>
                            <div class="col-md-4 text-right">
                                <a class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Cetak Data</a>
                                <a class="btn btn-xs btn-success"><i class="fa fa-file-excel-o"></i> Export Ke Excel</a>
                            </div>
                        </div>

                        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr class="primary">
                                    <th class="text-center" style="width:15px;">#</th>
                                    <th class="text-center">Temuan / Rekomendasi</th>
                                    <th class="text-center">PIC 2</th>
                                    <th class="text-center">Tanggal<br>Penyelesaian</th>
                                    <th class="text-center">Tindak<br>Lanjut</th>
                                    <th class="text-center">Rincian</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                    $tem=$tgl=$aksi=$pic2=$tindak_lanjut=$rinc='';
                                    $user_pic=\App\Models\PICUnit::where('id_user',Auth::user()->id)->first();
                                @endphp
                                @foreach ($temuan as $idlhp=>$item)
                                    @php
                                        $norekom=$tglselesai=$aksi=$rincian=$rinc='';
                                        $tem='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">';
                                        $tem.='<div class="col-md-12 text-center"><small><i>Nomor LHP</i> : </small> <b><u>'.$item->no_lhp.'</u></b></div>
                                            <div class="col-md-2"><small><i>Nomor</i></small><br>
                                            <b>'.$item->no_temuan.'</b>
                                            </div>';

                                        if(strlen($item->temuan)>=100)
                                        {
                                            $tem.='<div class="col-md-10" data-toggle="tooltip" title="'.$item->temuan.'"><small><i>Temuan</i></small><br>
                                            <b>'.substr($item->temuan,0,100).' ...</b>
                                            </div>';
                                        }
                                        else
                                        {
                                            $tem.='<div class="col-md-10"><small><i>Temuan</i></small><br>
                                            <b>'.$item->temuan.'</b>
                                            </div>';
                                        }
                                        
                                        $tem.='</div>';
                                        $tgl='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        $pic2='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        $aksi='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        $tindak_lanjut='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        $rinc='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        
                                        
                                        $rinc.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $rinc.='<div class="col-md-12">&nbsp;</div>';
                                        $rinc.='</div>';

                                        $tem.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $tem.='<div class="col-md-1"><small><i>Status</i></small></div>';
                                        $tem.='<div class="col-md-1"><small><i>No.</i></small></div>';
                                        $tem.='<div class="col-md-10"><small><i>Rekomendasi</i></small></div>';
                                        $tem.='</div>';

                                        $tgl.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $tgl.='<div class="col-md-2">&nbsp;</div>';
                                        $tgl.='<div class="col-md-10">&nbsp;</div>';
                                        $tgl.='</div>';

                                        $pic2.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $pic2.='<div class="col-md-2">&nbsp;</div>';
                                        $pic2.='<div class="col-md-10">&nbsp;</div>';
                                        $pic2.='</div>';
                                        
                                        $tindak_lanjut.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $tindak_lanjut.='<div class="col-md-2">&nbsp;</div>';
                                        $tindak_lanjut.='<div class="col-md-10">&nbsp;</div>';
                                        $tindak_lanjut.='</div>';

                                        $aksi.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $aksi.='<div class="col-md-2">&nbsp;</div>';
                                        $aksi.='<div class="col-md-10">&nbsp;</div>';
                                        $aksi.='</div>';
                                        if(isset($rekomendasi[$item->id_temuan]))
                                        {
                                            foreach($rekomendasi[$item->id_temuan] as $k=>$v)
                                            {
                                                
                                                $drekom=strlen($v->rekomendasi);
                                                if($drekom>=150)
                                                    $text_rekom='<span data-toggle="tooltip" data-placement="top" title="'.$v->rekomendasi.'">'.substr($v->rekomendasi,0,150).'...</span>';
                                                else
                                                    $text_rekom=$v->rekomendasi;

                                                $tem.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';

                                                if(isset($strekom[$v->status_rekomendasi_id]))
                                                    $tem.='<div class="col-md-1 text-center"><span data-toggle="tooltip" title="'.$strekom[$v->status_rekomendasi_id]->rekomendasi.'" data-placement="right" class="label label-'.(warnasingkatanstatus(singkatanstatus($strekom[$v->status_rekomendasi_id]->rekomendasi))).'">'.singkatanstatus($strekom[$v->status_rekomendasi_id]->rekomendasi).'</span></div>';
                                                else
                                                    $tem.='<div class="col-md-1 text-center">-</div>';

                                                $tem.='<div class="col-md-1 text-center">'.$v->nomor_rekomendasi.'</div>';
                                                $tem.='<div class="col-md-10">'.$text_rekom.'</div>';
                                                $tem.='</div>';

                                                $tem.='</div>';
                                                $jlhtl=0;
                                                
                                                // if(isset($gettindaklanjut[$v->id]))
                                                if(isset($jumlahtl[$v->id]))
                                                {
                                                    $tindak_lanjut.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                    $tindak_lanjut.='<div class="col-md-12 text-center"><span class="label label-info fz-sm">'.count($gettindaklanjut[$v->id]).'</span></div>';
                                                    $tindak_lanjut.='</div>';
                                                    $jlhtl=count($jumlahtl[$v->id]);
                                                }
                                                else
                                                {
                                                    $tindak_lanjut.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                    // $tindak_lanjut.='<div class="col-md-12 text-center"><span class="label label-danger">0</span></div>';
                                                    $tindak_lanjut.='<div class="col-md-12 text-center" id="jlh_tl_'.$item->id_temuan.'_'.$v->id.'"><span class="label label-danger fz-sm">0</span></div>';
                                                    $tindak_lanjut.='</div>';
                                                }  
                                                

                                                //Rincian
                                                $jlhrincian=0;
                                                if($v->rincian!='')
                                                {
                                                    if(isset($rincian[$v->rincian][$v->id_rekomendasi]))
                                                    {
                                                        $rinc.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $rinc.='<div class="col-md-12 text-center" style="margin-top:5px;"><span class="label label-info fz-sm">'.count($rincian[$v->rincian][$v->id_rekomendasi]).'</i></div>';
                                                        $rinc.='</div>';
                                                        $jlhrincian=count($rincian[$v->rincian][$v->id_rekomendasi]);
                                                    }
                                                    else
                                                    {
                                                        $rinc.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $rinc.='<div class="col-md-12 text-center" style="margin-top:5px;"><span class="label label-inverse">0</i></div>';
                                                        $rinc.='</div>';
                                                    }
                                                }
                                                else
                                                {
                                                    $rinc.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                    $rinc.='<div class="col-md-12 text-center" style="margin-top:5px;"><span class="label label-success">Tidak Ada</i></div>';
                                                    $rinc.='</div>';
                                                }
                                                

                                                $listpic2=explode(',', trim($v->pic_2_temuan_id));
                                                if(count($listpic2)>1)
                                                {
                                                    // print_r($listpic2);
                                                   
                                                    $pic2.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:280px;"><div class="col-md-12 text-left">';
                                                        $c=1;
                                                        $t_pic='';
                                                        $c=0;
                                                        foreach($listpic2 as $kp=>$vp)
                                                        {
                                                            
                                                            if($c<3)
                                                                $pic2.=(isset($pic[$vp]) ? $pic[$vp]->nama_pic : '').'<br>';
                                                            else
                                                                $t_pic.=(isset($pic[$vp]) ? $pic[$vp]->nama_pic : '').'<br>';

                                                            
                                                            $c++;
                                                        }
                                                        
                                                    if($c>3)
                                                        $pic2.='<a href="#" class="label label-default" data-toggle="tooltip" data-html="true" title="'.$t_pic.'">Lainnya</a></div></div>';
                                                    else
                                                        $pic2.='</div></div>';
                                                }
                                                else
                                                    $pic2.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:280px;"><div class="col-md-12 text-left">-</div></div>';

                                                $tgl.='<div style="height:80px;"  id="tgl_penyelesaian_'.$item->id_temuan.'_'.$v->id.'">';
                                                if($v->tanggal_penyelesaian!='')
                                                {
                                                    $tgl.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;">
                                                            <div class="col-md-12">'.tgl_indo($v->tanggal_penyelesaian).'</div>
                                                        </div>';
                                                    // $tglselesai.='<li style="height:32px;">'.tgl_indo($val->tanggal_penyelesaian).'</li>';
                                                }
                                                else
                                                {
                                                    $tgl.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">
                                                            <div class="col-md-12">
                                                            <div class="input-group date" id="datetimepicker2" >
                                                                <input type="text" data-plugin="datepicker" data-date-format="dd/mm/yyyy" class="form-control" name="tanggal_penyelesaian" id="tanggal_penyelesaian_'.$item->id_temuan.'_'.$v->id.'" value="'.date('d/m/Y').'" style="height:30px !important;width:90px !important;min-width:70px !important;font-size:11px; "/>
                                                                <span class="input-group-addon bg-info text-white" style="cursor:pointer" onclick="settglpenyelesaian('.$item->id_temuan.','.$v->id.')"><i class="glyphicon glyphicon-ok-sign"></i> Set</span>
                                                            </div>    
                                                        </div>    
                                                    </div>';
                                                }
                                                $tgl.='</div>';
                                                    
                                                $styleaksi='display:none';
                                                    if(trim($v->pic_2_temuan_id)==$user_pic->id)
                                                    {
                                                        if($v->tanggal_penyelesaian!='')
                                                        {
                                                            $styleaksi='display:inline-block';
                                                        }
                                                        else
                                                        {
                                                            $styleaksi='display:none';
                                                        }
                                                    }
                                                    
                                                    if($v->pic_1_temuan_id==$user_pic->id)
                                                    {

                                                        if($v->tanggal_penyelesaian!='')
                                                        {
                                                            $styleaksi='display:inline-block';
                                                        }
                                                        else
                                                        {
                                                            $styleaksi='display:none';
                                                        }
                                                    }

                                                
                                                if($v->publish_pic_2==0 && trim($v->pic_2_temuan_id)!='' && trim($v->pic_2_temuan_id)!=',')
                                                {
                                                    if(in_array($user_pic->id,$listpic2))
                                                    {
                                                        $togl='';
                                                    }
                                                    else
                                                    {
                                                        $togl='data-toggle="tooltip" title="PIC 2 Belum Mengisi Tindak Lanjut"';
                                                    }
                                                    $aksi.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                            <div class="btn-group" style="'.$styleaksi.'" id="aksi_rekomendasi_'.$item->id_temuan.'_'.$v->id.'">
                                                                <button '.$togl.' type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">';
                                                    
                                                    
                                                        if(in_array($user_pic->id,$listpic2))
                                                        {
                                                            // print_r($listpic2);
                                                            $aksi.='<li>
                                                                        <a href="#" class="btn-add" data-toggle="modal" data-target="#modaltambahtindaklanjut" data-value="'.$v->id_lhp.'__'.$item->id_temuan.'_0__'.$v->id.'_0'.'" style="font-size:11px;"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tindak Lanjut</a>
                                                                    </li>';
                                                            $aksi.='<li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';
                                                            if($jlhrincian!=0)
                                                            {
                                                                $aksi.=' <li><a href="javascript:tambahrincian('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Tambah Rincian Tindak Lanjut</a></li>';
                                                            }
                                                            if($jlhtl!=0)
                                                            {
                                                                $aksi.=' <li><a class="" href="javascript:publishpic2('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke PIC 1</a></li>';
                                                            }
                                                        }
                                                        else
                                                            $aksi.='<li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';

                                                    if($v->rincian!='')
                                                    {
                                                        $aksi.=' <li><a href="javascript:updaterincian_unitkerja('.$v->id.','.$v->id_temuan.',\''.$v->rincian.'\')" style="font-size:11px;"><i class="menu-icon zmdi zmdi-view-dashboard zmdi-hc-lg"></i> &nbsp;&nbsp;Update Rincian</a></li>';
                                                    }
                                                    $aksi.='</ul></div>
                                                        </div>';
                                                }
                                                elseif($v->publish_pic_2==1 && trim($v->pic_2_temuan_id)!='')
                                                {
                                                    
                                                    
                                                    if(in_array($user_pic->id,$listpic2))
                                                    {
                                                        $aksi.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                            <div data-toggle="tooltip" title="Data Rekomendasi Sudah Publish Ke PIC 1" class="btn-group" style="'.$styleaksi.'" id="aksi_rekomendasi_'.$item->id_temuan.'_'.$v->id.'">
                                                                <button type="button" class="btn btn-success btn-xs" style="height:28px;"><i class="fa fa-check"></i></button>
                                                                <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">';
                                                        // print_r($listpic2);
                                                        $aksi.='<li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';
                                                        if($jlhrincian!=0)
                                                        {
                                                            $aksi.=' <li><a href="javascript:tambahrincian('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Tambah Rincian Tindak Lanjut</a></li>';
                                                        }
                                                        
                                                        $aksi.=' <li><a href="#" style="font-size:11px;"><i class="glyphicon glyphicon-check"></i> &nbsp;&nbsp;Sudah Publish Ke PIC 1</a></li>';     
                                                    }
                                                    else
                                                    {
                                                        $aksi.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                            <div class="btn-group" style="'.$styleaksi.'" id="aksi_rekomendasi_'.$item->id_temuan.'_'.$v->id.'">
                                                                <button type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">';

                                                        $aksi.='<li>
                                                                    <a href="#" class="btn-add" data-toggle="modal" data-target="#modaltambahtindaklanjut" data-value="'.$v->id_lhp.'__'.$item->id_temuan.'_0__'.$v->id.'_0'.'" style="font-size:11px;"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tindak Lanjut</a>
                                                                </li>';
                                                        $aksi.='<li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';

                                                        $aksi.=' <li><a href="javascript:reviewtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-file"></i> &nbsp;&nbsp;Review & Rangkuman Tindak Lanjut</a></li>';
                                                        if($jlhrincian!=0)
                                                        {
                                                            $aksi.=' <li><a href="javascript:tambahrincian('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Tambah Rincian Tindak Lanjut</a></li>';
                                                        }
                                                        if($v->rangkuman_rekomendasi!='')
                                                        {
                                                            $aksi.=' <li><a href="javascript:publishpic1('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke Auditor</a></li>';
                                                        }
                                                    }
                                                    
                                                    if($v->rincian!='')
                                                    {
                                                        $aksi.=' <li><a href="javascript:updaterincian_unitkerja('.$v->id.','.$v->id_temuan.',\''.$v->rincian.'\')" style="font-size:11px;"><i class="menu-icon zmdi zmdi-view-dashboard zmdi-hc-lg"></i> &nbsp;&nbsp;Update Rincian</a></li>';
                                                    }
                                                    $aksi.='</ul></div>
                                                        </div>';
                                                }
                                                else{
                                                    if($v->publish_pic_1==1)
                                                    {
                                                        $icon='fa-check';
                                                        $color='success';
                                                        $toggle='data-toggle="tooltip" title="Data Rekomendasi Sudah Publish Ke Auditor"';
                                                    }
                                                    else
                                                    {
                                                        $icon='fa-bars';
                                                        $color='primary';
                                                        $toggle='';
                                                    }
                                                    $aksi.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                        <div  class="btn-group" style="'.$styleaksi.'" id="aksi_rekomendasi_'.$item->id_temuan.'_'.$v->id.'">
                                                            <button '.$toggle.' type="button" class="btn btn-'.$color.' btn-xs" style="height:28px;"><i class="fa '.$icon.'"></i></button>
                                                            <button type="button" class="btn btn-'.$color.' btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                                               ';
                                                        if($v->pic_1_temuan_id==$user_pic->id)
                                                        {
                                                            
                                                            if($v->publish_pic_2==1)
                                                            {
                                                                $aksi.=' <li><a href="javascript:reviewtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-file"></i> &nbsp;&nbsp;Review & Rangkuman Tindak Lanjut</a></li>';
                                                                if($v->rangkuman_rekomendasi!='')
                                                                {
                                                                    $aksi.=' <li><a href="javascript:publishpic1('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke Auditor</a></li>';
                                                                }
                                                            }

                                                            if($v->publish_pic_1==0)
                                                            {
                                                                $aksi.='<li>
                                                                    <a href="#" class="btn-add" data-toggle="modal" data-target="#modaltambahtindaklanjut" data-value="'.$v->id_lhp.'__'.$item->id_temuan.'_0__'.$v->id.'_0'.'" style="font-size:11px;"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tindak Lanjut</a>
                                                                </li>';
                                                            }
                                                            
                                                            if(trim($v->pic_2_temuan_id)=='' || trim($v->pic_2_temuan_id)==',')
                                                            {
                                                                // $aksi.=' <li><a href="javascript:reviewtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-file"></i> &nbsp;&nbsp;Review & Rangkuman Tindak Lanjut</a></li>';
                                                                

                                                                if($v->publish_pic_1==1)
                                                                {
                                                                    $aksi.=' <li><a href="#" style="font-size:11px;"><i class="glyphicon glyphicon-check"></i> &nbsp;&nbsp;Sudah Publish Ke Auditor</a></li>';
                                                                }
                                                                else 
                                                                {
                                                                    // $aksi.='<li>
                                                                    //     <a href="#" class="btn-add" data-toggle="modal" data-target="#modaltambahtindaklanjut" data-value="'.$v->id_lhp.'__'.$item->id_temuan.'_0__'.$v->id.'_0'.'" style="font-size:11px;"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tindak Lanjut</a>
                                                                    // </li>';
                                                                     
                                                                    $aksi.=' <li><a href="javascript:publishpic1('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke Auditor</a></li>';
                                                                    
                                                                }
                                                            }

                                                            $aksi.='<li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';

                                                            

                                                            if(trim($v->pic_2_temuan_id)!='')
                                                            {
                                                                if($v->publish_pic_1==1)
                                                                {
                                                                    $aksi.=' <li><a href="#" style="font-size:11px;"><i class="glyphicon glyphicon-check"></i> &nbsp;&nbsp;Sudah Publish Ke Auditor</a></li>';
                                                                }
                                                                else
                                                                {
                                                                    if($v->rangkuman_rekomendasi!='')
                                                                    {
                                                                        $aksi.=' <li><a href="javascript:publishpic1('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke Auditor</a></li>';
                                                                    }
                                                                }
                                                                // $aksi.='<li><a href="javascript:rangkumantindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Rangkuman Tindak Lanjut</a></li>';
                                                            }

                                                        }
                                                        if($v->rincian!='')
                                                        {
                                                            $aksi.=' <li><a href="javascript:updaterincian_unitkerja('.$v->id.','.$v->id_temuan.',\''.$v->rincian.'\')" style="font-size:11px;"><i class="menu-icon zmdi zmdi-view-dashboard zmdi-hc-lg"></i> &nbsp;&nbsp;Update Rincian</a></li>';
                                                        }
                                                        $aksi.='</ul>
                                                        </div></div>';
                                                }
                                                

                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center" style="font-size:11px !important;">{{$no}}</td>
                                        <td class="text-left" style="font-size:11px !important;">{!!$tem!!}</td>
                                        <td class="text-left" style="font-size:11px !important;">{!!$pic2!!}</td>
                                        <td class="text-center" style="font-size:11px !important;">{!!$tgl!!}</td>
                                        <td class="text-left" style="font-size:11px !important;">{!!$tindak_lanjut!!}</td>
                                        <td class="text-center" style="font-size:11px !important;">{!!$rinc!!}</td>
                                        <td class="text-center" style="font-size:11px !important;">{!!$aksi!!}</td>
                                    </tr>
                                    @php
                                        $no++;
                                    @endphp
                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
			</div><!-- .widget-body -->
		</div><!-- .widget -->
	</div>
@endsection

@section('footscript')
    <link rel="stylesheet" href="{{asset('theme/backend/libs/misc/datatables/datatables.min.css')}}"/>
    <script src="{{asset('theme/backend/libs/misc/datatables/datatables.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/noty.css')}}"/>
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script src="{{asset('js/tindak-lanjut.js')}}"></script>
    <script src="{{asset('js/noty.js')}}"></script>
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		setTimeout(function(){
			$('.alert').fadeOut();
		},3000);
		$('.select2').select2();
        // loaddata();
        $('#table').DataTable();
        $('#no_lhp').on('change',function(){
            var idlhp=$(this).val();
            $('#select-temuan').load(flagsUrl+'/temuan-by-lhp-select/'+idlhp+'/{{$user_pic->id}}',function(){
                $('.select2').select2();
                $('#no_temuan').on('change',function(){
                    var idtemuan=$(this).val();
                    $('#select-rekomendasi').load(flagsUrl+'/rekomendasi-by-temuan-select/'+idtemuan+'/{{$user_pic->id}}',function(){
                        $('.select2').select2();
                    });
                });
            });
        });
        var pesan='{{Session::get("success")}}';
        var error='{{Session::get("error")}}';
        if(pesan!='')
            swal("Berhasil", pesan, "success");
        if(error!='')
            swal("Gagal", error, "error");
	</script>
	<style>
	.select2-container{
		width:100% !important;
	}
    .modal {
    overflow-y:auto;
    }
    .tooltip-inner {
        text-align: left !important;
    }
    div[role=tooltip]
    {
        width:300px !important;
    }
	</style>
@endsection
@section('modal')
    @include('backend.pages.data-lhp.pic-unit.modal')
    @include('backend.pages.data-lhp.pic-unit.modal-tindaklanjut')
@endsection