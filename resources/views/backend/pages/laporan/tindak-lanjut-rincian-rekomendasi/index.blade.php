@extends('backend.layouts.master')
@section('title')
    <title>Laporan Tindak Lanjut Rincian Rekomendasi</title>
@endsection

@section('content')
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<span class="widget-title">Laporan Tindak Lanjut Rincian Rekomendasi</span>
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
                                        <label for="my-input" class="col-md-3">Pemeriksa</label>
                                        <div class="col-md-6">
                                        <select class="select2 form-control" data-plugin="select2" name="pemeriksa[]" id="pemeriksan" onchange="loaddata();getlhp()" multiple>
                                            <option value="0" selected>Semua</option>
                                                @foreach ($pemeriksa as $item)
                                                    <option value="{{$item->id}}">{{$item->code}} - {{$item->pemeriksa}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">No LHP</label>
                                        <div class="col-md-6" id="div-lhp">
                                            <select class="select2 form-control" name="no_lhp" id="no_lhp"></select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Jenis Rincian</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="jenisrincian" id="jenisrincian" onchange="loaddata()">
                                                @foreach(Config::get('constants.rincian') as $k=>$v)
                                                    <option value="{{$k}}" style="text-align:right">{{$v}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
							</div>
						</div>
					</div>
				</div>
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

        function loaddata()
        {
            var pemeriksa=$('#pemeriksan').val();
            var no_lhp=$('#no_lhp').val();
            var jenisrincian=$('#jenisrincian').val();

            $.ajax({
                url : flagsUrl+'/laporan/tindak-lanjut-rincian-rekomendasi-data',
                data : {pemeriksa:pemeriksa,jenisrincian: jenisrincian, no_lhp:no_lhp},
                type : 'POST',
                success : function(res){
                    $('#data').html(res);
                }
            });
           
        }
        function getlhp(){
            var pemeriksa=$('#pemeriksan').val();
            $('#div-lhp').load(flagsUrl+'/selectlhpbypemeriksa/'+pemeriksa+'/multiple', function(){
                $('.select2').select2({
                    width:'100%'
                });
            })
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
