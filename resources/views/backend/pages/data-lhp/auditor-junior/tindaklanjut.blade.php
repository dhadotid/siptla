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
                                    {{-- <th class="text-center">No.<br>Temuan</th> --}}
                                    <th class="text-center">Temuan</th>
                                    {{-- <th class="text-center">No.<br>Rekomendasi</th>--}}
                                    <th class="text-center">Rekomendasi</th>
                                    <th class="text-center">Tanggal<br>Penyelesaian</th>
                                    <th class="text-center">Rincian<br>Tindak Lanjut</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                @endphp
                                @foreach ($temuan as $item)
                                    @php
                                        $rekom=$norekom=$tglselesai=$aksi=$rincian='';
                                        if(isset($rekomendasi[$item->id]))
                                        {
                                            foreach($rekomendasi[$item->id] as $key=>$val)
                                            {
                                                // $norekom.='<li style="height:32px;">'.$val->nomor_rekomendasi.'</li>';
                                                
                                                $rekom.='<li style="height:32px;">
                                                    <div class="row">
                                                        <div style="width:30px;float:left">'.$val->nomor_rekomendasi.'</div>
                                                        <div style="width:90%;float:left">'.(strlen($val->rekomendasi) > 30 ? '<a href="#" data-toggle="tooltip" data-title="'.$val->rekomendasi.'" title="'.$val->rekomendasi.'">'.substr($val->rekomendasi,0,30).' ...</a>' : $val->rekomendasi ).'</div>
                                                    </div>';
                                                    
                                                $rekom.='</li>';

                                                    
                                                    $tglselesai.='<div id="tgl_penyelesaian_'.$item->id.'_'.$val->id.'">';
                                                        if($val->tanggal_penyelesaian!='')
                                                        {
                                                            $tglselesai.='<li style="height:32px;">'.tgl_indo($val->tanggal_penyelesaian).'</li>';
                                                        }
                                                        else
                                                        {
                                                            $tglselesai.='<li style="height:32px;">-</li>';
                                                            // $tglselesai.='<li style="height:32px;">
                                                            //     <div class="input-group date" id="datetimepicker2" >
                                                            //         <input type="text" data-plugin="datepicker" data-date-format="dd/mm/yyyy" class="form-control" name="tanggal_penyelesaian" id="tanggal_penyelesaian_'.$item->id.'_'.$val->id.'" value="'.date('d/m/Y').'" style="height:30px !important;width:120px !important;min-width:120px !important; "/>
                                                            //         <span class="input-group-addon bg-info text-white" style="cursor:pointer" onclick="settglpenyelesaian('.$item->id.','.$val->id.')"><i class="glyphicon glyphicon-ok-sign"></i> Set</span>
                                                            //     </div>    
                                                            // </li>';
                                                        }
                                                    $tglselesai.='</div>';
                                                    $aksi.='<li style="margin-bottom:1px;height:32px;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                                            <li>
                                                                <a href="#" class="btn-add" data-toggle="modal" data-target="#modaltambahtindaklanjut" data-value="'.$item->id_lhp.'__'.$item->id.'_0__'.$val->id.'_0'.'" style="font-size:11px;"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tindak Lanjut</a>
                                                            </li>
                                                            <li><a href="#" target="_blank" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>
                                                        </ul>
                                                    </div></li>';

                                                if($val->rincian!='')
                                                    {
                                                       $rincian.='<li style="margin-bottom:1px;height:32px;">
                                                            <a href="javascript:rincian(\''.$val->rincian.'\',\''.$val->id.'__'.$item->id.'__'.$item->id_lhp.'\')" class="btn btn-xs btn-danger" style="height:28px;"><i class="fa fa-flag"></i></a>
                                                        </li>'; 
                                                    }
                                                    else
                                                        $rincian.='<li style="margin-bottom:1px;height:32px;">-</li>';
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{$no}}</td>
                                        {{-- <td class="text-center"></td> --}}
                                        <td class="text-left">
                                            No. {{$item->no_temuan}} <br>{!!(strlen($item->temuan) > 30 ? '<a href="#" data-toggle="tooltip" data-title="'.$item->temuan.'" title="'.$item->temuan.'">'.substr($item->temuan,0,30).' ...</a>' : $item->temuan )!!}</td>
                                        {{-- <td class="text-center"><ul>{!!$norekom!!}</ul></td> --}}
                                        <td class="text-left"><ul>{!!$rekom!!}</ul></td>
                                        <td class="text-center"><ul>{!!$tglselesai!!}</ul></td>
                                        <td class="text-center"><ul>{!!$rincian!!}</ul></td>
                                        <td class="text-center"><ul>{!!$aksi!!}</ul></td>
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
                data : { tahun : '{{$tahun}}', tgl_awal : tanggal_awal, tgl_akhir : tanggal_akhir, rekomid : no_rekomendasi, temuan_id : no_temuan, statusrekom : status_rekomendasi},
                type : 'POST',
                dataType : 'JSON',
                success : function(res){
                    $('#data').html(res,function(){
                        $('#table-data').DataTable();
                    });
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
	</style>
@endsection
@section('modal')
    @include('backend.pages.data-lhp.pic-unit.modal')
@endsection