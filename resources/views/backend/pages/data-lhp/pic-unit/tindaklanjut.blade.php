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
                            <select name="tahun" id="tahun" class="form-control text-left" data-plugin="select2" onchange="getdata(this.value)" style="width:50%">
                                @for ($i = date('Y'); $i >= (date('Y')-5); $i--)
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
                                                @foreach ($datalhp as $item)
                                                    <option value="{{$item->id}}">{{$item->no_lhp}} - {{$item->judul_lhp}}</option>
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
                                    {{-- <th class="text-center">No Temuan</th> --}}
                                    <th class="text-center">Temuan</th>
                                    <th class="text-center">No. <br>Rekomendasi</th>
                                    <th class="text-center">Rekomendasi</th>
                                    <th class="text-center">Tanggal<br>Penyelesaian</th>
                                    <th class="text-center">PIC 2</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                @endphp
                                @foreach ($temuan as $item)
                                    @if($item->totemuan->tahun_pemeriksa==$tahun)
                                        @php
                                        
                                            $rekom=$norekom=$tglselesai=$aksi=$rincian=$pic2='';
                                            if(isset($rekomendasi[$item->id]))
                                            {
                                                foreach($rekomendasi[$item->id] as $key=>$val)
                                                {
                                                    $norekom.='<li style="height:32px;">'.$val->nomor_rekomendasi.'</li>';
                                                    $rekom.='<li style="height:32px;">- '.(strlen($val->rekomendasi) > 30 ? '<a href="#" data-toggle="tooltip" title="'.$val->rekomendasi.'">'.substr($val->rekomendasi,0,30).' ...</a>' : $val->rekomendasi ).'</li>';

                                                    if(isset($val->picunit2->nama_pic))
                                                        $pic2.='<li style="height:32px;">'.$val->picunit2->nama_pic.'</li>';
                                                    else
                                                        $pic2.='<li style="height:32px;">-</li>';

                                                    $tglselesai.='<div id="tgl_penyelesaian_'.$item->id.'_'.$val->id.'">';
                                                    if($val->tanggal_penyelesaian!='')
                                                    {
                                                        $tglselesai.='<li style="height:32px;">'.tgl_indo($val->tanggal_penyelesaian).'</li>';
                                                    }
                                                    else
                                                    {
                                                        $tglselesai.='<li style="height:32px;">
                                                            <div class="input-group date" id="datetimepicker2" >
                                                                <input type="text" data-plugin="datepicker" data-date-format="dd/mm/yyyy" class="form-control" name="tanggal_penyelesaian" id="tanggal_penyelesaian_'.$item->id.'_'.$val->id.'" value="'.date('d/m/Y').'" style="height:30px !important;width:120px !important;min-width:120px !important; "/>
                                                                <span class="input-group-addon bg-info text-white" style="cursor:pointer" onclick="settglpenyelesaian('.$item->id.','.$val->id.')"><i class="glyphicon glyphicon-ok-sign"></i> Set</span>
                                                            </div>    
                                                        </li>';
                                                    }
                                                    $tglselesai.='</div>';

                                                    $user_pic=\App\Models\PICUnit::where('id_user',Auth::user()->id)->first();
                                                    $styleaksi='display:none';
                                                    if($val->pic_2_temuan_id==$user_pic->id)
                                                    {
                                                        if($val->tanggal_penyelesaian!='')
                                                        {
                                                            $styleaksi='display:block';
                                                        }
                                                        else
                                                        {
                                                            $styleaksi='display:none';
                                                        }
                                                    }
                                                    
                                                    if($val->pic_1_temuan_id==$user_pic->id)
                                                    {

                                                        if($val->tanggal_penyelesaian!='')
                                                        {
                                                            $styleaksi='display:block';
                                                        }
                                                        else
                                                        {
                                                            $styleaksi='display:none';
                                                        }
                                                    }
                                                    $aksi.='<li style="margin-bottom:1px;height:32px;" id="aksi_rekomendasi_'.$item->id.'_'.$val->id.'">
                                                        <div class="btn-group" style="'.$styleaksi.'">
                                                            <button type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                            <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                                                <li>
                                                                    <a href="#" class="btn-add" data-toggle="modal" data-target="#modaltambahtindaklanjut" data-value="'.$item->id_lhp.'__'.$item->id.'_0__'.$val->id.'_0'.'" style="font-size:11px;"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tindak Lanjut</a>
                                                                </li>
                                                                <li><a href="javascript:detailtindaklanjut('.$val->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';
                                                        if($val->pic_1_temuan_id==$user_pic->id)
                                                                $aksi.='<li><a href="javascript:rangkumantindaklanjut('.$val->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Rangkuman Tindak Lanjut</a></li>';
                                                        $aksi.='</ul>
                                                        </div></li>';

                                                    // <li><a target="_blank" href="'.url('data-tindak-lanjut-unitkerja/'.$val->id.'/'.$item->id).'" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>
                                                    //<li><a href="#" data-toggle="modal" data-target="#lihattindaklanjut" data-value="'.$val->id.'" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{$no}}</td>
                                            {{-- <td class="text-center">{{$item->no_temuan}}</td> --}}
                                            {{-- <td class="text-left">{{(strlen($item->temuan) > 30 ? substr($item->temuan,0,30).' ...' : $item->temuan )}}</td> --}}
                                            <td class="text-left">
                                            No. {{$item->no_temuan}} <br>{!!(strlen($item->temuan) > 30 ? '<a href="#" data-toggle="tooltip" data-title="'.$item->temuan.'">'.substr($item->temuan,0,30).' ...</a>' : $item->temuan )!!}</td>
                                            <td class="text-center"><ul>{!!$norekom!!}</ul></td>
                                            <td class="text-left"><ul>{!!$rekom!!}</ul></td>
                                            <td class="text-center"><ul>{!!$tglselesai!!}</ul></td>
                                            <td class="text-left"><ul>{!!$pic2!!}</ul></td>
                                            <td class="text-center"><ul>{!!$aksi!!}</ul></td>
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
			</div><!-- .widget-body -->
		</div><!-- .widget -->
	</div>
@endsection

@section('footscript')
    <link rel="stylesheet" href="{{asset('theme/backend/libs/misc/datatables/datatables.min.css')}}"/>
    <script src="{{asset('theme/backend/libs/misc/datatables/datatables.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/noty.css')}}"/>
    <script src="{{asset('js/noty.js')}}"></script>
    <script src="{{asset('js/tindak-lanjut.js')}}"></script>
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
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
@endsection