@extends('backend.layouts.master')

@section('title')
	<title>Dashboard</title>
@endsection
@section('content')

	<div class="col-md-12">
        <div class="row">
			
			<div class="col-md-6 col-sm-6">
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
			<div class="col-md-6 col-sm-6">
				<div class="widget p-md clearfix">
					<span class="pull-right fz-lg fw-500 counter" style="height:68px;padding-top:15px;">
						Tahun&nbsp;&nbsp;
						<select class="form-control pull-right" name="tahun" id="tahun" style="width:200px;">
							@for($thn=date('Y');$thn>=(date('Y')-20);$thn--)
								<option value="{{$thn}}" style="text-align:right">{{$thn}}</option>
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
				<div class="widget p-md clearfix" style="height:650px !important;">
					<div class="pull-left">
						<small class="text-color">Rekapitulasi Jumlah PIC Unit ({{isset($datalevelpic['labels']) ? count($datalevelpic['labels']) : 0}})</small>
					</div>
					<canvas id="chart1" style="width:100%;margin-top:80px;" height="300"></canvas>
					<div class='cell'>
						<ul>
							
							@foreach ($datalevelpic['labels'] as $idx=>$item)
								<li>
									<div class="box" style="background: {{isset($color['colorlevel'][$idx]) ? $color['colorlevel'][$idx] : '#ffffff'}}"></div> 
									<a href="{{url('pic-unit')}}">{{$item}} ({{isset($datalevelpic['datasets'][0]['data'][$idx]) ? $datalevelpic['datasets'][0]['data'][$idx]: 0}})</a>
								</li>
							@endforeach
							
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4">
				<div class="widget p-md clearfix" style="height:650px !important;">
					<div class="pull-left">
						<small class="text-color">Rekapitulasi Pengguna ({{isset($dpengguna['labels']) ? count($dpengguna['labels']) : 0}})</small>
					</div>
					<canvas id="chart2" style="width:100%;margin-top:80px;" height="300"></canvas>
					<div class='cell'>
						<ul>
							@foreach ($dpengguna['labels'] as $idx=>$item)
								<li>
									<div class="box" style="background: {{isset($color['coloruser'][$idx]) ? $color['coloruser'][$idx] : '#ffffff'}}"></div> 
									<a href="{{url('pengguna/'.str_slug($item))}}">
										{{$item}} ({{isset($dpengguna['datasets'][0]['data'][$idx]) ? $dpengguna['datasets'][0]['data'][$idx]: 0}})
									</a>
								</li>
							@endforeach
							
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4">
				<div class="widget p-md clearfix" style="height:650px !important;">
					<div class="pull-left">
						<small class="text-color">Rekapitulasi Jumlah Pemeriksa ({{$pemeriksa->count()}})</small>
					</div>
					<canvas id="chart3" style="width:100%;margin-top:80px;" height="300"></canvas>
					<div class='cell'>
						<ul>
							@foreach ($dpemeriksa['labels'] as $idx=>$item)
								<li>
									<div class="box" style="background: {{isset($color['colorpemeriksa'][$idx]) ? $color['colorpemeriksa'][$idx] : '#ffffff'}}"></div> 
									<a href="{{url('pemeriksa')}}">
										{{$item}} ({{isset($dpemeriksa['datasets'][0]['data'][$idx]) ? $dpemeriksa['datasets'][0]['data'][$idx]: 0}})
									</a>
								</li>
							@endforeach
							
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
		var oilData = <?php echo json_encode($datalevelpic);?>;
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
		var oilData = <?php echo json_encode($dpengguna);?>;
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
		var jlhpemeriksa = document.getElementById("chart3");
		var datapemeriksa = <?php echo json_encode($dpemeriksa);?>;
		var pieChart = new Chart(jlhpemeriksa, {
			type: 'doughnut',
			data: datapemeriksa,
			options: {
				legend: {
					display: false,
					labels: {
						fontColor: 'rgb(255, 99, 132)'
					}
				}
			}
		});

		
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
	</style>
@endsection