@extends('backend.layouts.master')
@section('title')
    <title>Rekap Status Sesuai Tahun</title>
@endsection

@section('content')
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<span class="widget-title">Rekap Status Sesuai Tahun</span>
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
                                            <select class="select2 form-control" data-plugin="select2" name="pemeriksa[]" id="pemeriksan" onchange="loaddata();" multiple>
                                                <option value="0" selected>Semua</option>
                                                @foreach ($pemeriksa as $item)
                                                    <option value="{{$item->id}}">{{$item->code}} - {{$item->pemeriksa}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">LHP Dari Tahun</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="lhp_from_year" id="lhp_from_year" onchange="loaddata()">
                                                    <option value="0" selected>Semua</option>
                                                    @foreach ($year as $item)
                                                        <option value="{{$item->tahun}}">{{$item->tahun}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">LHP Sampai Tahun</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="lhp_to_year" id="lhp_to_year" onchange="loaddata()">
                                                    <option value="0" selected>Semua</option>
                                                    @foreach ($year as $item)
                                                        <option value="{{$item->tahun}}">{{$item->tahun}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Tanggal Awal</label>
                                        <div class="col-md-3">
                                            <div class='input-group date' id='datetimepicker' data-plugin="datepicker" data-date-format="dd/mm/yyyy">
                                                <input type='text' class="form-control" name="tanggal_awal" id="tanggal_awal" readonly value="01/{{date('m/Y')}}" onchange="loaddata()"/>
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
                                                <input type='text' class="form-control" name="tanggal_akhir" id="tanggal_akhir" readonly value="{{date('d/m/Y')}}" onchange="loaddata()"/>
                                                <span class="input-group-addon bg-info text-white">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Bidang</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="bidang[]" id="bidang" onchange="loaddata()" multiple>
                                                <option value="0" selected>Semua</option>
                                                @foreach ($bidang as $item)
                                                    <option value="{{$item->id}}">{{$item->nama_bidang}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Unit Kerja</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="unit_kerja1[]" id="unit_kerja1" onchange="loaddata()" multiple>
                                                <option value="0" selected>Semua</option>
                                                @foreach ($unitkerja as $item)
                                                    <option value="{{$item->id}}">{{$item->nama_pic}}</option>
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

        function loaddata()
        {
            var pemeriksa=$('#pemeriksan').val();
            var lhp_from_year = $('#lhp_from_year').val();
            var lhp_to_year = $('#lhp_to_year').val();
            var bidang=$('#bidang').val();
            var tanggal_awal=$('#tanggal_awal').val();
            var tanggal_akhir=$('#tanggal_akhir').val();
            var unit_kerja1=$('#unit_kerja1').val();

            $.ajax({
                url : flagsUrl+'/laporan/rekap-status-sesuai-tahun-data',
                data : {unit_kerja1:unit_kerja1,bidang:bidang,pemeriksa: pemeriksa, lhp_from_year:lhp_from_year, tgl_awal: tanggal_awal, tgl_akhir: tanggal_akhir, lhp_to_year:lhp_to_year},
                type : 'POST',
                success : function(res){
                    $('#data').html(res);
                }
            });
           
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