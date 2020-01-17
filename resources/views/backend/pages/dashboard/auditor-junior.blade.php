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
						<select class="form-control pull-right" name="tahun" id="tahun" style="width:200px;" onchange="location.href='{{url('dashboard')}}/'+this.value">
							@for($thn=date('Y');$thn>=(date('Y')-6);$thn--)
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
				<div class="widget p-md clearfix">
					<div class="pull-left">
						<small class="text-color">Statistik Rekomendasi ({{$status->count()}})</small>
					</div>
					<canvas id="chart1" style="width:100%" height="400"></canvas>
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
									<a href="#">{{$item}} ({{isset($rekom['datasets'][0]['data'][$idx]) ? $rekom['datasets'][0]['data'][$idx] : 0}})</a>
								</li>
							@endforeach
							
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4">
				<div class="widget p-md clearfix">
					<div class="pull-left">
						<small class="text-color">Monitoring Review SPI (7)</small>
					</div>
					<canvas id="chart2" style="width:100%" height="400"></canvas>
					<div class='cell'>
						<ul>
							@if (isset($dtl['labels']))
								@foreach ($dtl['labels'] as $idx=>$item)
									@php
										if(isset($color['colorlhp'][str_slug($item)]))
											$warna=$color['colorlhp'][str_slug($item)];
										else
											$warna='#ffffff';
									@endphp
									<li><div class="box" style="background: {{$warna}}"></div> 
										<a href="#">{{$item}} ({{isset($dtl['datasets'][0]['data'][$idx]) ? $dtl['datasets'][0]['data'][$idx] : 0}})</a>
									</li>
								@endforeach
							@else	
								@foreach (status_lhp() as $item)
									<li><div class="box" style="background: {{generate_color_one()}}"></div> 
										<a href="#">{{$item}} (0)</a>
									</li>
								@endforeach
							@endif
							
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4">
				<div class="widget p-md clearfix">
					<div class="pull-left">
						<small class="text-color">Overdue Review (7)</small>
					</div>
					<canvas id="chart3" style="width:100%" height="400"></canvas>
					<div class='cell'>
						<ul>
							
							
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
			type: 'pie',
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
			type: 'pie',
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
		var oilData = {
			labels: [
				"Saudi Arabia",
				"Russia",
				"Iraq",
				"United Arab Emirates",
				"Canada"
			],
			datasets: [
				{
					data: [133.3, 86.2, 52.2, 51.2, 50.2],
					backgroundColor: <?php echo json_encode($color);?>
				}]
			
		};
		var pieChart = new Chart(oilCanvas, {
			type: 'pie',
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
		
	</script>
	<style>
	.box{
		width:20px;
		height:20px;
		float:left;
		margin-right:10px;
		border:1px solid #000;
	}
	.cell
	{
		width:100%;
		/* float:left; */
	}
	.cell li
	{
		height:35px;
		width:100%;
		float:left;
	}
	</style>
@endsection