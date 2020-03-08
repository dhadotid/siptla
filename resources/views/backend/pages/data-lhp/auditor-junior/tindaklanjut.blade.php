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
                                    <div class="form-group" style="margin-bottom:5px;">
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
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Pemeriksa</label>
                                        <div class="col-md-7">
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
                                        <div class="col-md-9">
                                            <select class="select2 form-control" name="no_lhp" id="no_lhp" onchange="loaddata()">
                                                <option>&nbsp;</option>
                                                @foreach ($lhp as $item)
                                                    <option value="{{$item->id_lhp}}">{{$item->no_lhp}} - {{$item->judul_lhp}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Nomor Temuan</label>
                                        <div class="col-md-9" id="select-temuan">
                                            <select class="select2 form-control" name="no_temuan" id="no_temuan" onchange="loaddata()"></select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Nomor Rekomendasi</label>
                                        <div class="col-md-9" id="select-rekomendasi">
                                            <select class="select2 form-control" name="no_rekomendasi" id="no_rekomendasi" onchange="loaddata()"></select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Status Rekomendasi</label>
                                        <div class="col-md-9" id="select-status-rekom">
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
                                    <th class="text-center"><div style="width:420px;">Temuan / Rekomendasi</div></th>
                                    <th class="text-center">Tanggal<br>Penyelesaian</th>
                                    <th class="text-center">PIC 2</th>
                                    <th class="text-center">Tindak<br>Lanjut</th>
                                    <th class="text-center">Rincian</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                    $tem=$tgl=$aksi=$pic2=$tindak_lanjut='';
                                    $user_pic=\App\Models\PICUnit::where('id_user',Auth::user()->id)->first();
                                @endphp
                                @foreach ($temuan as $idtemuan=>$item)
                                    @php
                                        $norekom=$tglselesai=$aksi=$rincian=$rinc='';
                                        $tem='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">';
                                        $tem.='<div class="col-md-2"><small><i>No. Temuan</i></small><br>
                                            <b>'.$item->no_temuan.'</b>
                                            </div>';
                                        if(strlen($item->temuan)>=150)
                                        {
                                            $tem.='<div class="col-md-10"><small><i>Temuan</i></small><br>
                                            <b><a href="#" data-toggle="tooltip" title="'.$item->temuan.'">'.substr($item->temuan,0,150).' ...</a></b>
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
                                        
                                        $tindak_lanjut.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $tindak_lanjut.='<div class="col-md-2">&nbsp;</div>';
                                        $tindak_lanjut.='<div class="col-md-10">&nbsp;</div>';
                                        $tindak_lanjut.='</div>';
                                        
                                        $rinc='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        
                                        $rinc.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $rinc.='<div class="col-md-12">&nbsp;</div>';
                                        $rinc.='</div>';
                                        
                                        $tem.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $tem.='<div class="col-md-2"><small><i>No.Rekom</i></small></div>';
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
                                        
                                        
                                        $aksi.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $aksi.='<div class="col-md-2">&nbsp;</div>';
                                        $aksi.='<div class="col-md-10">&nbsp;</div>';
                                        $aksi.='</div>';
                                        if(isset($rekomendasi[$item->id_temuan]))
                                        {
                                            foreach($rekomendasi[$item->id_temuan] as $k=>$v)
                                            {
                                                $drekom=strlen($v->rekomendasi);
                                                if($drekom>=250)
                                                    $text_rekom='<a href="#" data-toggle="tooltip" data-placement="top" title="'.$v->rekomendasi.'">'.substr($v->rekomendasi,0,250).'...</a>';
                                                else
                                                    $text_rekom=$v->rekomendasi;

                                                $tem.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                $tem.='<div class="col-md-1 text-center">'.$v->nomor_rekomendasi.'</div>';
                                                $tem.='<div class="col-md-11">'.$text_rekom.'</div>';
                                                $tem.='</div>';
                                                
                                                if($item->publish_pic_1==0)
                                                {
                                                    $tindak_lanjut.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                    $tindak_lanjut.='<div class="col-md-12 text-center"><span class="label label-danger">0</span></div>';
                                                    $tindak_lanjut.='</div>';
                                                }
                                                else
                                                {
                                                    if(isset($gettindaklanjut[$v->id]))
                                                    {
                                                        $tindak_lanjut.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $tindak_lanjut.='<div class="col-md-12 text-center"><span class="label label-inverse">'.count($gettindaklanjut[$v->id]).'</span></div>';
                                                        $tindak_lanjut.='</div>';
                                                    }
                                                    else
                                                    {
                                                        $tindak_lanjut.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $tindak_lanjut.='<div class="col-md-12 text-center"><span class="label label-danger">0</span></div>';
                                                        $tindak_lanjut.='</div>';
                                                    }  
                                                }

                                                $jlhrincian=0;
                                                if($v->rincian!='')
                                                {
                                                    if(isset($rincian[$v->rincian][$v->id_rekomendasi]))
                                                    {
                                                        $rinc.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $rinc.='<div class="col-md-12 text-center" style=""><span class="label label-info">'.count($rincian[$v->rincian][$v->id_rekomendasi]).'</i></div>';
                                                        $rinc.='</div>';
                                                        $jlhrincian=count($rincian[$v->rincian][$v->id_rekomendasi]);
                                                    }
                                                    else
                                                    {
                                                        $rinc.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $rinc.='<div class="col-md-12 text-center" style=""><span class="label label-inverse">0</i></div>';
                                                        $rinc.='</div>';
                                                    }
                                                }
                                                else
                                                {
                                                    $rinc.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                    $rinc.='<div class="col-md-12 text-center" style=""><span class="label label-success">Tidak Ada</i></div>';
                                                    $rinc.='</div>';
                                                }

                                                if($v->pic_2_temuan_id!='')
                                                {
                                                    $listpic2=explode(',', $v->pic_2_temuan_id);
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
                                                    $tgl.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">
                                                            <div class="col-md-12"><span class="label label-info"><i class="fa fa-calendar"></i> '.tgl_indo($v->tanggal_penyelesaian).'</span></div>
                                                        </div>';
                                                    // $tglselesai.='<li style="height:32px;">'.tgl_indo($val->tanggal_penyelesaian).'</li>';
                                                }
                                                else
                                                {
                                                   $tgl.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">
                                                            <div class="col-md-12"><i class="label label-danger">Belum Di Set</i></div>
                                                        </div>';
                                                }
                                                $tgl.='</div>';
                                                    
                                                    $styleaksi='display:none';
                                                    
                                                    if($item->publish_pic_1==1)
                                                    {
                                                        $aksi.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                            <div class="btn-group" id="aksi_rekomendasi_'.$item->id_temuan.'_'.$v->id.'">
                                                            <button type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                            <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                                                
                                                                <li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';

                                                        $aksi.='</ul>
                                                        </div></div>';
                                                    }
                                                    else
                                                    {
                                                        $aksi.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                                                
                                                                <li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';

                                                        $aksi.='</ul>
                                                            </div>
                                                        </div>';
                                                    }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{$no}}</td>
                                        <td class="text-left">{!!$tem!!}</td>
                                        <td class="text-center">{!!$tgl!!}</td>
                                        <td class="text-left">{!!$pic2!!}</td>
                                        
                                        <td class="text-center">{!!$tindak_lanjut!!}</td>
                                        <td class="text-center" style="font-size:11px !important;">{!!$rinc!!}</td>
                                        <td class="text-center">{!!$aksi!!}</td>
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
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script src="{{asset('js/tindak-lanjut-junior.js')}}"></script>
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
            $('#select-temuan').load(flagsUrl+'/temuan-by-lhp-select/'+idlhp,function(){
                $('.select2').select2();
                $('#no_temuan').on('change',function(){
                    var idtemuan=$(this).val();
                    $('#select-rekomendasi').load(flagsUrl+'/rekomendasi-by-temuan-select/'+idtemuan,function(){
                        $('.select2').select2();
                    });
                });
            });
        });
        
        function loaddata()
        {
            var tanggal_awal=$('#tanggal_awal').val();
            var tanggal_akhir=$('#tanggal_akhir').val();
            var pemeriksa=$('#pemeriksa').val();
            var no_lhp=$('#no_lhp').val();
            var no_temuan=$('#no_temuan').val();
            var no_rekomendasi=$('#no_rekomendasi').val();
            var status_rekomendasi=$('#status_rekomendasi').val();
            
            $.ajax({
                url : '{{url("/")}}/data-tindaklanjut-list',
                data : { tahun : '{{$tahun}}',pemeriksa:pemeriksa, tgl_awal : tanggal_awal, tgl_akhir : tanggal_akhir, rekomid : no_rekomendasi, temuan_id : no_temuan, statusrekom : status_rekomendasi},
                type : 'POST',
                success : function(res){
                    $('#data').html(res);
                }
            });
            // $('#data').load(flagsUrl+'/data-tindaklanjut-list/{{$tahun}}',function(){
            //     $('#table').DataTable();
            // });
        }

        function getdata(tahun)
        {
            location.href=flagsUrl+'/data-tindaklanjut/'+tahun;
        }
        function rincian(rincian,id)
        {
            //load-table-rincian/{jenis}/{idtemuan?}/{statusrekomendasi?}/{view?}
            var d=id.split('__');
            var id_temuan=d[1];
            var id_rekom=d[0];
            var id_lhp=d[2];
            $('#table-rincian').load(flagsUrl + '/load-table-rincian/'+rincian+'/'+id_temuan+'/'+id_rekom+'/1');
            $('#modalrincian').modal('show');
        }
	</script>
	<style>
	.select2-container{
		width:100% !important;
	}
     .tooltip-inner {
        text-align: left !important;
    }
    div[role=tooltip]
    {
        width:300px !important;
    }
    thead, tbody
    {
        font-size:11px !important;
    }
	</style>
@endsection
@section('modal')
    @include('backend.pages.data-lhp.pic-unit.modal')
    @include('backend.pages.data-lhp.auditor-junior.modal-tindaklanjut')
@endsection