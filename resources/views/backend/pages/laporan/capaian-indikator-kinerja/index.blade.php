@extends('backend.layouts.master')
@section('title')
    <title>{{$title}}</title>
@endsection

@section('content')
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<span class="widget-title">{{$title}}</span>
			</header><!-- .widget-header -->
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
                                        <label for="my-input" class="col-md-3">Tahun</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="tahun" id="tahun" onchange="loaddata()">
                                                @for($thn=date('Y');$thn>=(date('Y')-20);$thn--)
                                                    @if ($thn==$tahun)
                                                        <option value="{{$thn}}" selected="selected" style="text-align:right">{{$thn}}</option>
                                                    @else
                                                        <option value="{{$thn}}" style="text-align:right">{{$thn}}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Triwulan</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="triwulan" id="triwulan" onchange="loaddata()">
                                                    <option value="Semua" selected>Semua</option>
                                                    @foreach ($triwulan as $idx=>$val)
                                                        <option value="{{$val}}">{{$val}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Sebagai PIC 1/PIC 2</label>
                                        <div class="col-md-6" id="div-kode-lhp">
                                            <select class="select2 form-control" name="pic[]" id="pic" onchange="loaddata()" multiple>
                                                    <option value="0" selected>Semua</option>
                                                    @foreach ($pic as $val)
                                                        <option value="{{$val->id}}">{{$val->nama_pic}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Bidang</label>
                                        <div class="col-md-6" >
                                        <select class="select2 form-control" name="bidang[]" id="bidang" onchange="loaddata()" multiple>
                                                <option value="0" {{$bidang_filter == 'Total' ? 'selected' : ''}}>>Semua</option>
                                                @foreach ($bidang as $k=>$items)
                                                    <option value="{{$items->id}}" {{$bidang_filter == $items->nama_bidang  ? 'selected' : ''}} >{{$items->nama_bidang}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Unit Kerja</label>
                                        <div class="col-md-6" >
                                        <select class="select2 form-control" name="unit_kerja[]" id="unit_kerja" onchange="loaddata()" multiple>
                                                <option value="0" selected>Semua</option>
                                                @foreach ($pic as $k=>$items)
                                                    <option value="{{$items->id}}">{{$items->nama_pic}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Level Resiko</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="level_resiko[]" id="level_resiko" onchange="loaddata()" multiple>
                                                <option value="0" selected>Semua</option>
                                                    @foreach ($levelresiko as $item)
                                                        <option value="{{$item->id}}">{{$item->level_resiko}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
				</div>
                <input id="showreport" name="showreport" type="hidden" value="{{$showreport}}">
				<div id="data">
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
        loaddata();
        getlhp();
        getkodelhp();

        function loaddata()
        {
            var tahun=$('#tahun').val();
            var triwulan=$('#triwulan').val();
            var pic=$('#pic').val();
            var bidang=$('#bidang').val();
            var unit_kerja=$('#unit_kerja').val();
            var level_resiko=$('#level_resiko').val();
            var showreport=$('#showreport').val();

            $.ajax({
                url : flagsUrl+'/laporan/capaian-indikator-kinerja-data',
                data : {tahun:tahun,triwulan: triwulan, pic:pic, bidang: bidang, unit_kerja: unit_kerja, level_resiko:level_resiko, showreport:showreport},
                type : 'POST',
                success : function(res){
                    $('#data').html(res);
                }
            });
           
        }
        function getlhp(){
            // var pemeriksa=$('#pemeriksan').val();
            // $('#div-lhp').load(flagsUrl+'/selectlhpbypemeriksa/'+pemeriksa+'/true', function(){
            //     $('.select2').select2({
            //         width:'100%'
            //     });
            // })
        }
        
	</script>
	<style>
    .tooltip-inner {
        text-align: left !important;
    }
	.select2-container{
		width:100% !important;
	}
    .select2-selection--multiple{
        overflow: hidden !important;
        height: auto !important;
    }
	</style>
@endsection
