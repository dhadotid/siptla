@extends('backend.layouts.master')

@section('title')
	<title>Dashboard</title>
@endsection
@section('content')

	<div class="col-md-12">
        <div class="row">
			
			<div class="col-md-9 col-sm-6">
				<div class="widget p-md clearfix">
					<div class="pull-left">
						<small class="text-color">Login Sebagai</small>
						<h3 class="widget-title" style="padding-top:10px";>{{Auth::user()->name}}</h3>
					</div>
					<span class="pull-right fz-lg fw-500 counter">
                        <img class="img-responsive" src="{{asset('logo.png')}}" alt="{{Auth::user()->name}}" style="height:60px;">
                    </span>
				</div><!-- .widget -->
			</div>
			
			{{-- <div class="col-md-3 col-sm-3">
				<div class="widget p-md clearfix">
								@php
									$periode=\App\Models\PeriodeReview::first();
									if($periode)
									{
										$tglmulai=$periode->tanggal_mulai;
										$tglselesai=$periode->tanggal_selesai;
										$id=$periode->id;
									}
									else
									{
										$tanggal=periodereview();
										$tglmulai=$tanggal['tanggal_mulai'];
										$tglselesai=$tanggal['tanggal_selesai'];
										$id=0;
									}

									// if(isInDate($tglmulai,$tglselesai))
									if(date('d')<=$tglmulai && date('d')>=$tglselesai)
									{
										$st_period=1;
										$class='text-success';
									}
									else
									{
										$st_period=0;
										$class='text-danger';
									}
								@endphp
					<span class="pull-left" style="height:68px;width:100%">
						<small class="text-color">Periode Review</small> : <span id="status_periode" class="{{$class}}" style="font-size:13px;font-weight:bold;">{{$st_period==1 ? 'Aktif' : 'Tidak Aktif'}}</span>
						<div style="margin-top:5px;" class="row">
							<div class="col-md-5">
								<label class="switch">
									<input type="checkbox" {{$st_period==1 ? 'checked' : ''}} onchange="periode({{$id}})" id="input-period">
									<span class="slider"></span>
								</label>
							</div>
							<div class="col-md-7">
								
								<small class="text-color" style="font-size:10px">Tanggal Mulai :</small> <b>{{$tglmulai}}</b><br>
								<small class="text-color" style="font-size:10px">Tanggal Selesai :</small> <b>{{$tglselesai}}</b>
							</div>
							
						</div>
					</span>
				</div>
			</div> --}}
			<div class="col-md-3 col-sm-3">
				<div class="widget p-md clearfix">
					<span class="pull-right fz-lg fw-500 counter" style="height:68px;padding-top:15px;">
						Tahun&nbsp;&nbsp;
						<select class="form-control pull-right" name="tahun" id="tahun" style="width:200px;" onchange="location.href='{{url('dashboard')}}/'+this.value">
							@for($thn=date('Y');$thn>=(date('Y')-20);$thn--)
								@if ($thn==$tahun)
									<option value="{{$thn}}" selected="selected" style="text-align:right">{{$thn}}</option>
								@else
									<option value="{{$thn}}" style="text-align:right">{{$thn}}</option>
								@endif
							@endfor
						</select>
                    </span>
				</div>
			</div>
		</div>
		
	</div>
	<div class="col-md-12">
        <div class="row">
			<div class="col-md-4 col-sm-4">
				<div class="widget p-md clearfix " style="height:650px !important;">
					<div class="pull-left">
						<small class="text-color">Statistik Rekomendasi ({{isset($rekom['datasets'][0]['data']) ? count($rekom['datasets'][0]['data']) : 0}})</small>
					</div>
					<canvas id="chart1" style="width:100%;margin-top:80px;" height="300"></canvas>
					<div class='cell'>
						<ul>
							@foreach ($rekom['labels'] as $idx=>$item)
								@php
									if(isset($color['colorrekom'][str_slug($item)]))
										$warna=$color['colorrekom'][str_slug($item)];
									else
										$warna='#ffffff';
								@endphp
								<li><div class="box" style="background: {{$warna}}"></div> 
									@if ($dstatus[str_slug($item)])
										<a href="{{url('data-lhp/'.$tahun.'/'.$dstatus[str_slug($item)]->id)}}">{{$item}} ({{isset($rekom['datasets'][0]['data'][$idx]) ? $rekom['datasets'][0]['data'][$idx] : 0}})</a>
									@else
										<a href="#">{{$item}} ({{isset($rekom['datasets'][0]['data'][$idx]) ? $rekom['datasets'][0]['data'][$idx] : 0}})</a>
									@endif
								</li>
							@endforeach
							
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4">
				<div class="widget p-md clearfix " style="height:650px !important;">
					<div class="pull-left">
						<small class="text-color">Monitoring Review SPI ({{isset($dtl['datasets'][0]['data']) ? count($dtl['datasets'][0]['data']) : 0}})</small>
					</div>
					<canvas id="chart2" style="width:100%;margin-top:80px;" height="300"></canvas>
					<div class='cell'>
						<ul>
							@if (isset($dtl['labels']))
								@php
									$dstatus=$dst=status_lhp_key();
								@endphp
								@foreach ($dtl['labels'] as $idx=>$item)
									@php
										if(isset($color['colorlhp'][str_slug($item)]))
											$warna=$color['colorlhp'][str_slug($item)];
										else
											$warna='#ffffff';

										$dst[str_slug($item)]=str_slug($item);
									@endphp
									<li><div class="box" style="background: {{$warna}}"></div> 
										<a href="#">{{$item}} ({{isset($dtl['datasets'][0]['data'][$idx]) ? $dtl['datasets'][0]['data'][$idx] : 0}})</a>
									</li>
								@endforeach

								@foreach ($dstatus as $item)
									@if(!in_array(str_slug($item),$dst))
										<li>
											<div class="box" style="background: {{generate_color_one()}}"></div> 
											<a href="#">{{$item}} (0)</a>
										</li>
									@endif
								@endforeach
							@else	
								@foreach (status_lhp() as $item)
									<li>
										<div class="box" style="background: {{generate_color_one()}}"></div> 
										<a href="#">{{$item}} (0)</a>
									</li>
								@endforeach
							@endif
							
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4">
				<div class="widget p-md clearfix " style="height:650px !important;">
					<div class="pull-left">
						<small class="text-color">Overdue Review ({{count(bataswaktu())}})</small>
					</div>
					<canvas id="chart3" style="width:100%;margin-top:80px;" height="300"></canvas>
					<div class='cell'>
						<ul>
							@if (isset($doverdue['labels']))
								@php
									$dstatus=$dst=bataswaktu();
								@endphp
								@foreach ($doverdue['labels'] as $idx=>$item)
									@php
										if(isset($color['colorbataswaktu'][str_slug($item)]))
											$warna=$color['colorbataswaktu'][str_slug($item)];
										else
											$warna='#ffffff';

									@endphp
									<li><div class="box" style="background: {{$warna}}"></div> 
										<a href="{{url('data-tindaklanjut/'.$tahun)}}?key={{str_slug($item)}}">{{$item}} ({{isset($doverdue['datasets'][0]['data'][$idx]) ? $doverdue['datasets'][0]['data'][$idx] : 0}})</a>
									</li>
								@endforeach

							
							@else	
								@foreach (bataswaktu() as $item)
									<li>
										<div class="box" style="background: {{generate_color_one()}}"></div> 
										<a href="#">{{$item}} (0)</a>
									</li>
								@endforeach
							@endif
							
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	
@endsection
@section('footscript')
	@php
		$color=["#FF6384","#63FF84","#84FF63","#8463FF","#6384FF"];
	@endphp
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato"/>
	<script src="{{asset('js/Chart.min.js')}}"></script>
	<script>
		var oilCanvas = document.getElementById("chart1");
		Chart.defaults.global.defaultFontFamily = "Lato";
		Chart.defaults.global.defaultFontSize = 18;
		var oilData = <?php echo json_encode($rekom);?>;
		var pieChart = new Chart(oilCanvas, {
			type: 'doughnut',
			data: oilData,
			options: {
				legend: {
					display: false,
					labels: {
						fontColor: 'rgb(255, 99, 132)'
					}
				}
			}
		});
		//--------------
		var oilCanvas = document.getElementById("chart2");
		var oilData = <?php echo json_encode($dtl);?>;
		var pieChart = new Chart(oilCanvas, {
			type: 'doughnut',
			data: oilData,
			options: {
				legend: {
					display: false,
					labels: {
						fontColor: 'rgb(255, 99, 132)'
					}
				}
			}
		});
		//---------------
		var oilCanvas = document.getElementById("chart3");
		var oilData = <?php echo json_encode($doverdue);?>;
		var pieChart = new Chart(oilCanvas, {
			type: 'doughnut',
			data: oilData,
			options: {
				legend: {
					display: false,
					labels: {
						fontColor: 'rgb(255, 99, 132)'
					}
				}
			}
		});
		function periode(id)
		{
			var checkBox = document.getElementById("input-period");

			if (checkBox.checked == true){
				$.ajax({
					url : flagsUrl+'/status-periode/'+id+'/1',
					success : function(){
						$('#status_periode').removeClass('text-danger');
						$('#status_periode').addClass('text-success');
						$('#status_periode').text('Aktif');
						swal("Berhasil", "Status Periode Review Berhasil Di Edit", "success");
					}
				});
			} else {
				$.ajax({
					url : flagsUrl+'/status-periode/'+id+'/0',
					success : function(){
						$('#status_periode').removeClass('text-success');
						$('#status_periode').addClass('text-danger');
						$('#status_periode').text('Tidak Aktif');
						swal("Berhasil", "Status Periode Review Berhasil Di Edit", "success");
					}
				});
			}
		}
	</script>
	<style>
	.box{
		width:15px;
		height:15px;
		float:left;
		margin-right:10px;
		border:1px solid #000;
	}
	.cell
	{
		width:100%;
		margin-top:50px;
		/* float:left; */
	}
	.cell li
	{
		height:30px;
		width:100%;
		float:left;
	}

		.switch {
		position: relative;
		display: inline-block;
		width: 120px;
		height: 40px;
		}

		.switch input { 
		opacity: 0;
		width: 0;
		height: 0;
		}

		.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
		}

		.slider:before {
		position: absolute;
		content: "";
		height: 32px;
		width: 40px;
		left: 30px;
		bottom: 4px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
		}

		input:checked + .slider {
		background-color: #2196F3;
		}

		input:focus + .slider {
		box-shadow: 0 0 1px #2196F3;
		}

		input:checked + .slider:before {
		-webkit-transform: translateX(26px);
		-ms-transform: translateX(26px);
		transform: translateX(26px);
		}

		/* Rounded sliders */
		.slider.round {
		border-radius: 34px;
		}

		.slider.round:before {
		border-radius: 50%;
		}
		</style>
@endsection