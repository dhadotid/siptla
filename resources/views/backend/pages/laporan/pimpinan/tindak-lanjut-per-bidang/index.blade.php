@extends('backend.layouts.master')
@section('title')
    <title>{{$title_form}}</title>
@endsection

@section('content')
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<span class="widget-title">{{$title_form}}</span>
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
                                            <option value="0" {{$pemeriksa_filter == 'Total' ? 'selected' : ''}}>Semua</option>
                                                @foreach ($pemeriksa as $item)
                                                    <option value="{{$item->id}}" {{$pemeriksa_filter == $item->code  ? 'selected' : ''}}>{{$item->code}} - {{$item->pemeriksa}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Bidang</label>
                                        <div class="col-md-6" >
                                        <select class="select2 form-control" name="bidang[]" id="bidang" onchange="loaddata()" multiple>
                                                <option value="0" {{$bidang_filter == 'Total' ? 'selected' : ''}}>Semua</option>
                                                @foreach ($bidang as $k=>$items)
                                                    <option value="{{$items->id}}" {{$bidang_filter == $items->nama_bidang  ? 'selected' : ''}}>{{$items->nama_bidang}}</option>
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
                                        <label for="my-input" class="col-md-3">Level Resiko</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="level-resiko[]" id="level-resiko" onchange="loaddata()" multiple>
                                                <option value="0" selected>Semua</option>
                                                    @foreach ($levelresiko as $item)
                                                        <option value="{{$item->id}}">{{$item->level_resiko}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Status Rekomendasi</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="statusrekomendasi[]" id="statusrekomendasi" onchange="loaddata()" multiple>
                                                <option value="0" {{$category_filter == 'Semua' ? 'selected' : ''}}>Semua</option>
                                                    @foreach ($statusrekomendasi as $item)
                                                        <option value="{{$item->id}}" {{$category_filter == $item->rekomendasi  ? 'selected' : ''}} >{{$item->rekomendasi}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Tanggal Awal</label>
                                        <div class="col-md-3">
                                            <div class='input-group date' id='datetimepicker' data-plugin="datepicker" data-date-format="dd/mm/yyyy">
                                                <input type='text' class="form-control" name="tanggal_awal" id="tanggal_awal" readonly value="01/01/{{$tahun}}" onchange="loaddata()"/>
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
                                        <label for="my-input" class="col-md-3">Overdue</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="overdue" id="overdue" onchange="loaddata()">
                                                <option value="2">Semua</option>
                                                <option value="1">Ya</option>
                                                <option value="0">Tidak</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input id="rekom_status" name="rekom_status" type="hidden" value="{{$rekomstatus}}">
                                    <input id="overduestatus" name="overduestatus" type="hidden" value="{{$overduestatus}}">
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
            var statusrekomendasi=$('#statusrekomendasi').val();
            var tanggal_awal=$('#tanggal_awal').val();
            var tanggal_akhir=$('#tanggal_akhir').val();
            var bidang=$('#bidang').val();
            var level_resiko=$('#level-resiko').val();
            var overdue=$('#overdue').val();
            var rekomstatus=$('#rekom_status').val();
            var overduestatus=$('#overduestatus').val();

            $.ajax({
                url : flagsUrl+'/laporan/tindaklanjut-per-bidang-pimpinan-data',
                data : {bidang:bidang,pemeriksa: pemeriksa, no_lhp:no_lhp, tgl_awal: tanggal_awal, tgl_akhir: tanggal_akhir, statusrekomendasi: statusrekomendasi, level_resiko:level_resiko, overdue:overdue,
                    rekomstatus:rekomstatus, overduestatus:overduestatus},
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