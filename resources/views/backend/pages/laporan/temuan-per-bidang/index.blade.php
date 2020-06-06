@extends('backend.layouts.master')
@section('title')
    <title>Laporan Temuan Per Bidang </title>
@endsection

@section('content')
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<span class="widget-title">Laporan Temuan Per Bidang </span>
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
                                        <label for="my-input" class="col-md-3">Tampilkan Nilai</label>
                                        <div class="col-md-3">
                                            <select class="select2 form-control" name="tampilkannilai" id="tampilkannilai" onchange="loaddata()">
                                                <option value="1">Ya</option>
                                                <option value="0">Tidak</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Tampilkan Waktu Penyelesaian</label>
                                        <div class="col-md-3">
                                            <select class="select2 form-control" name="tampilkanwaktupenyelesaian" id="tampilkanwaktupenyelesaian" onchange="loaddata()">
                                                <option value="1">Ya</option>
                                                <option value="0">Tidak</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- 
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <label for="my-input" class="col-md-3">Pejabat Penanda Tangan</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control" name="pejabat" id="pejabat">
                                                @foreach ($pejabat as $item)
                                                    <option value="{{$item->id}}">{{$item->jabatan}} : {{$item->nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    --}}
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
        // function checkZeroValue(idselected, value){
        //     var old_values = [];
        //     var test = $(idselected);//$("#pemeriksan");
        //     test.on("select2:select", function(event) {
        //     var values = [];
        //     $(event.currentTarget).find("option:selected").each(function(i, selected){ 
        //         values[i] = $(selected).val();
        //     });
        //     var last = $(values).not(old_values).get();
        //     old_values = values;
        //     if(old_values.length > 0){
        //         console.log("selected values: ", values);
        //         console.log("selected last: ", last);
        //         if(last.indexOf(0) ){
        //             // removePemeriksaValue(test, 0);
        //             $(idselected+" option[value='"+0+"']").prop("selected", false);
        //         }else{
        //             // $(idselected+" option[value='"+0+"']").prop("selected", false);
        //             console.log('remove all')
        //         }
        //     }
        //     }); checkZeroValue('#pemeriksan',this.value)
        // }

        function loaddata()
        {
            // var latest_value = $('#pemeriksan').closest('select').find('option').filter(':selected:last').val();
            
            var pemeriksa=$('#pemeriksan').val();
            var no_lhp=$('#no_lhp').val();
            var level_resiko=$('#level-resiko').val();
            var bidang=$('#bidang').val();
            var tanggal_awal=$('#tanggal_awal').val();
            var tanggal_akhir=$('#tanggal_akhir').val();
            var tampilkannilai=$('#tampilkannilai').val();
            var tampilkanwaktupenyelesaian=$('#tampilkanwaktupenyelesaian').val();
            var pejabat=$('#pejabat').val();

            $.ajax({
                url : flagsUrl+'/laporan/temuan-per-bidang-data',
                data : {bidang:bidang,pemeriksa: pemeriksa, no_lhp:no_lhp, tgl_awal: tanggal_awal, tgl_akhir: tanggal_akhir, level_resiko: level_resiko, tampilkannilai: tampilkannilai, tampilkanwaktupenyelesaian: tampilkanwaktupenyelesaian, pejabat:pejabat},
                type : 'POST',
                success : function(res){
                    $('#data').html(res);
                }
            });
           
        }
        function getlhp(){
            var pemeriksa=$('#pemeriksan').val();
            $('#div-lhp').load(flagsUrl+'/selectlhpbypemeriksa/'+pemeriksa+'/true', function(){
                $('.select2').select2({
                    width:'100%'
                });
            })
        }
        
	</script>
	<style>
	.select2-container{
		width:100% !important;
    }
    .tooltip-inner {
        text-align: left !important;
    }
    .select2-selection--multiple{
        overflow: hidden !important;
        height: auto !important;
    }
	</style>
@endsection
