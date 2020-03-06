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
                                                    <option value="{{$item->id_lhp}}">{{$item->no_lhp}} - {{$item->judul_lhp}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Nomor Temuan</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="no_temuan" id="no_temuan" onchange="loaddata()"></select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Nomor Rekomendasi</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="no_rekomendasi" id="no_rekomendasi" onchange="loaddata()"></select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Status Rekomendasi</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="status_rekomendasi" id="status_rekomendasi" onchange="loaddata()"></select>
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
                                    <th class="text-center">Tanggal<br>Penyelesaian</th>
                                    <th class="text-center">PIC 2</th>
                                    <th class="text-center">Tindak<br>Lanjut</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                    $tem=$tgl=$aksi=$pic2=$tindak_lanjut='';
                                @endphp
                                @foreach ($temuan as $idlhp=>$item)
                                    @php
                                        $norekom=$tglselesai=$aksi=$rincian='';
                                        $tem='<div class="row" style="height:75px;border-bottom:1px dotted #ddd">';
                                        $tem.='<div class="col-md-2"><small><i>No. Temuan</i></small><br>
                                            <b>'.$item->no_temuan.'</b>
                                            </div>';
                                        $tem.='<div class="col-md-10"><small><i>Temuan</i></small><br>
                                            <b>'.$item->temuan.'</b>
                                            </div>';
                                        $tem.='</div>';
                                        $tgl='<div class="row" style="height:75px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        $pic2='<div class="row" style="height:75px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        $aksi='<div class="row" style="height:75px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        $tindak_lanjut='<div class="row" style="height:75px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        
                                        
                                        $tem.='<br><div class="row" style="height:35px;border-bottom:1px dotted #ddd">';
                                        $tem.='<div class="col-md-2"><small><i>No. Rekomendasi</i></small></div>';
                                        $tem.='<div class="col-md-10"><small><i>Rekomendasi</i></small></div>';
                                        $tem.='</div>';

                                        $tgl.='<br><div class="row" style="height:35px;border-bottom:1px dotted #ddd">';
                                        $tgl.='<div class="col-md-2">&nbsp;</div>';
                                        $tgl.='<div class="col-md-10">&nbsp;</div>';
                                        $tgl.='</div>';

                                        $pic2.='<br><div class="row" style="height:35px;border-bottom:1px dotted #ddd">';
                                        $pic2.='<div class="col-md-2">&nbsp;</div>';
                                        $pic2.='<div class="col-md-10">&nbsp;</div>';
                                        $pic2.='</div>';
                                        
                                        $tindak_lanjut.='<br><div class="row" style="height:35px;border-bottom:1px dotted #ddd">';
                                        $tindak_lanjut.='<div class="col-md-2">&nbsp;</div>';
                                        $tindak_lanjut.='<div class="col-md-10">&nbsp;</div>';
                                        $tindak_lanjut.='</div>';

                                        $aksi.='<br><div class="row" style="height:35px;border-bottom:1px dotted #ddd">';
                                        $aksi.='<div class="col-md-2">&nbsp;</div>';
                                        $aksi.='<div class="col-md-10">&nbsp;</div>';
                                        $aksi.='</div>';
                                        if(isset($rekomendasi[$item->id_temuan]))
                                        {
                                            foreach($rekomendasi[$item->id_temuan] as $k=>$v)
                                            {
                                                $tem.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                $tem.='<div class="col-md-1 text-center">'.$v->nomor_rekomendasi.'</div>';
                                                $tem.='<div class="col-md-11">'.$v->rekomendasi.'</div>';
                                                $tem.='</div>';
                                                
                                                if($item->publish_pic_2==0 && $item->pic_2_temuan_id!='')
                                                {
                                                    $tindak_lanjut.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                    $tindak_lanjut.='<div class="col-md-12 text-center"><span class="label label-danger">0</span></div>';
                                                    $tindak_lanjut.='</div>';
                                                }
                                                else
                                                {
                                                    if(isset($gettindaklanjut[$v->id]))
                                                    {
                                                        $tindak_lanjut.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $tindak_lanjut.='<div class="col-md-12 text-center"><span class="label label-inverse">'.count($gettindaklanjut[$v->id]).'</i></div>';
                                                        $tindak_lanjut.='</div>';
                                                    }
                                                    else
                                                    {
                                                        $tindak_lanjut.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $tindak_lanjut.='<div class="col-md-12 text-center">&nbsp</div>';
                                                        $tindak_lanjut.='</div>';
                                                    }  
                                                }

                                                if($v->pic_2_temuan_id!='')
                                                    $pic2.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0;width:150px;"><div class="col-md-12 text-center">'.(isset($pic[$v->pic_2_temuan_id]) ? $pic[$v->pic_2_temuan_id]->nama_pic : '').'</div></div>';
                                                else
                                                    $pic2.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0;width:150px;"><div class="col-md-12 text-center">'.(isset($pic[$v->pic_2_temuan_id]) ? $pic[$v->pic_2_temuan_id]->nama_pic : '-').'</div></div>';

                                                $tgl.='<div style="height:60px;"  id="tgl_penyelesaian_'.$item->id_temuan.'_'.$v->id.'">';
                                                if($v->tanggal_penyelesaian!='')
                                                {
                                                    $tgl.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0">
                                                            <div class="col-md-12">'.tgl_indo($v->tanggal_penyelesaian).'</div>
                                                        </div>';
                                                    // $tglselesai.='<li style="height:32px;">'.tgl_indo($val->tanggal_penyelesaian).'</li>';
                                                }
                                                else
                                                {
                                                    $tgl.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0">
                                                            <div class="col-md-12">
                                                            <div class="input-group date" id="datetimepicker2" >
                                                                <input type="text" data-plugin="datepicker" data-date-format="dd/mm/yyyy" class="form-control" name="tanggal_penyelesaian" id="tanggal_penyelesaian_'.$item->id_temuan.'_'.$v->id.'" value="'.date('d/m/Y').'" style="height:30px !important;width:120px !important;min-width:120px !important; "/>
                                                                <span class="input-group-addon bg-info text-white" style="cursor:pointer" onclick="settglpenyelesaian('.$item->id_temuan.','.$v->id.')"><i class="glyphicon glyphicon-ok-sign"></i> Set</span>
                                                            </div>    
                                                        </div>    
                                                    </div>';
                                                }
                                                $tgl.='</div>';
                                                    $user_pic=\App\Models\PICUnit::where('id_user',Auth::user()->id)->first();
                                                    $styleaksi='display:none';
                                                    if($v->pic_2_temuan_id==$user_pic->id)
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

                                                if($item->publish_pic_2==0 && $item->pic_2_temuan_id!='')
                                                {
                                                    $aksi.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                            <div class="btn-group" style="'.$styleaksi.'" id="aksi_rekomendasi_'.$item->id_temuan.'_'.$v->id.'">
                                                                <button type="button" disabled class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                                <button type="button" disabled class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                    <span class="caret"></span>
                                                                </button>
                                                            </div>
                                                        </div>';
                                                }
                                                else{
                                                    $aksi.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                        <div class="btn-group" style="'.$styleaksi.'" id="aksi_rekomendasi_'.$item->id_temuan.'_'.$v->id.'">
                                                            <button type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                            <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                                                <li>
                                                                    <a href="#" class="btn-add" data-toggle="modal" data-target="#modaltambahtindaklanjut" data-value="'.$v->id_lhp.'__'.$item->id_temuan.'_0__'.$v->id.'_0'.'" style="font-size:11px;"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tindak Lanjut</a>
                                                                </li>
                                                                <li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>
                                                               ';
                                                        if($v->pic_1_temuan_id==$user_pic->id)
                                                        {
                                                            if($v->publish_pic_1==0)
                                                            {
                                                                $aksi.=' <li><a href="javascript:reviewtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-file"></i> &nbsp;&nbsp;Review Tindak Lanjut</a></li>';
                                                            }
                                                            if($v->review_monev!='')
                                                            {
                                                                $aksi.=' <li><a href="javascript:publishpic1('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke Auditor</a></li>';
                                                            }

                                                            if($v->pic_2_temuan_id!='')
                                                            {
                                                                $aksi.='<li><a href="javascript:rangkumantindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Rangkuman Tindak Lanjut</a></li>';
                                                            }

                                                        }
                                                        if($v->pic_2_temuan_id==$user_pic->id)
                                                        {
                                                            $aksi.=' <li><a href="javascript:publishpics('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke PIC 1</a></li>';
                                                        }
                                                        $aksi.='</ul>
                                                        </div></div>';
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{$no}}</td>
                                        <td class="text-left">{!!$tem!!}</td>
                                        <td class="text-center">{!!$tgl!!}</td>
                                        <td class="text-left">{!!$pic2!!}</td>
                                        <td class="text-left">{!!$tindak_lanjut!!}</td>
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
    <link rel="stylesheet" href="{{asset('css/noty.css')}}"/>
    <script src="{{asset('js/noty.js')}}"></script>
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script src="{{asset('js/tindak-lanjut.js')}}"></script>
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
	</style>
@endsection
@section('modal')
    @include('backend.pages.data-lhp.pic-unit.modal')
    @include('backend.pages.data-lhp.pic-unit.modal-tindaklanjut')
@endsection