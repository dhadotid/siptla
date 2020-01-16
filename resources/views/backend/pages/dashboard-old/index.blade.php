@extends('backend.layouts.master')

@section('title')
	<title>Dashboard</title>
@endsection
@section('content')
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-3 col-sm-6">
				<div class="widget stats-widget">
					<div class="widget-body clearfix">
						<div class="pull-left">
							<h3 class="widget-title text-primary"><span class="counter" data-plugin="counterUp">0</span></h3>
							<small class="text-color">Total Temuan</small>
						</div>
						<span class="pull-right big-icon watermark"><i class="fa fa-paperclip"></i></span>
					</div>
					<footer class="widget-footer bg-primary">
						<small>Jumlah</small>
						<span class="small-chart pull-right" data-plugin="sparkline" data-options="[4,3,5,2,1], { type: 'bar', barColor: '#ffffff', barWidth: 5, barSpacing: 2 }"><canvas width="33" height="16" style="display: inline-block; width: 33px; height: 16px; vertical-align: top;"></canvas></span>
					</footer>
				</div><!-- .widget -->
			</div>

			<div class="col-md-3 col-sm-6">
				<div class="widget stats-widget">
					<div class="widget-body clearfix">
						<div class="pull-left">
							<h3 class="widget-title text-danger"><span class="counter" data-plugin="counterUp">{{ $mv }}</span></h3>
							<small class="text-color">Menunggu Verifikasi</small>
						</div>
						<span class="pull-right big-icon watermark"><i class="fa fa-ban"></i></span>
					</div>
					<footer class="widget-footer bg-danger">
						<small>Jumlah</small>						
						<span class="small-chart pull-right" data-plugin="sparkline" data-options="[1,2,3,5,4], { type: 'bar', barColor: '#ffffff', barWidth: 5, barSpacing: 2 }"><canvas width="33" height="16" style="display: inline-block; width: 33px; height: 16px; vertical-align: top;"></canvas></span>
					</footer>
				</div><!-- .widget -->
			</div>

			<div class="col-md-3 col-sm-6">
				<div class="widget stats-widget">
					<div class="widget-body clearfix">
						<div class="pull-left">
							<h3 class="widget-title text-success"><span class="counter" data-plugin="counterUp">{{ $btl }}</span></h3>
							<small class="text-color">Belum Tindak Lanjut</small>
						</div>
						<span class="pull-right big-icon watermark"><i class="fa fa-unlock-alt"></i></span>
					</div>
					<footer class="widget-footer bg-success">
						<small>Jumlah</small>
						<span class="small-chart pull-right" data-plugin="sparkline" data-options="[2,4,3,4,3], { type: 'bar', barColor: '#ffffff', barWidth: 5, barSpacing: 2 }"><canvas width="33" height="16" style="display: inline-block; width: 33px; height: 16px; vertical-align: top;"></canvas></span>
					</footer>
				</div><!-- .widget -->
			</div>

			<div class="col-md-3 col-sm-6">
				<div class="widget stats-widget">
					<div class="widget-body clearfix">
						<div class="pull-left">
							<h3 class="widget-title text-warning"><span class="counter" data-plugin="counterUp">{{ $sel }}</span></h3>
							<small class="text-color">Selesai</small>
						</div>
						<span class="pull-right big-icon watermark"><i class="fa fa-file-text-o"></i></span>
					</div>
					<footer class="widget-footer bg-warning">
						<small>Jumlah</small>
						<span class="small-chart pull-right" data-plugin="sparkline" data-options="[5,4,3,5,2],{ type: 'bar', barColor: '#ffffff', barWidth: 5, barSpacing: 2 }"><canvas width="33" height="16" style="display: inline-block; width: 33px; height: 16px; vertical-align: top;"></canvas></span>
					</footer>
				</div><!-- .widget -->
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="widget">
			<header class="widget-header">
			<h4 class="widget-title">Chart Status Temuan</h4>
			</header><!-- .widget-header -->
			<hr class="widget-separator">
			<div class="widget-body">
			<div data-plugin="chart" data-options="{
				tooltip : {
					trigger: 'item',
					formatter: '{a} <br/>{b} : {c} ({d}%)'
				},
				legend: {
					orient: 'vertical',
					x: 'left',
					data: ['Menunggu Verifikasi','Belum Tindak Lanjut','Telah Tindak Lanjut','Selesai']
				},
				series : [
					{
						name: 'Jumlah',
						type: 'pie',
						radius : '55%',
						center: ['50%', '60%'],
						data:[
							{value:{{ $mv }}, name:'Menunggu Verifikasi'},
							{value:{{ $btl }}, name:'Belum Tindak Lanjut'},
							{value:{{ $ttl }}, name:'Telah Tindak Lanjut'},
							{value:{{ $sel }}, name:'Selesai'},
						],
						itemStyle: {
							emphasis: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}
				]
			}" style="height: 300px;">
			</div>
			</div><!-- .widget-body -->
		</div><!-- .widget -->
	</div><!-- END column -->

	<div class="col-md-6">
		<div class="widget p-lg">
			<h4 class="m-b-lg">Daftar Temuan Terbaru</h4>
			<table class="table">
				<tr>
					<th>#</th>
					<th>Nomor</th>
					<th>Tanggal LHP</th>
					<th>Status</th>
				</tr>
				@foreach ($temuan as $key => $item)
				
					<tr>
						<td>{{ $key = $key + 1 }}</td>
						<td>{{ $item->daftar->no_pengawasan }}</td>
						<td>{{ $item->daftar->tgl_pengawasan }}</td>
						<td>
							@switch($item->flag)
								@case(0)
									{{ 'Menunggu Verifikasi' }}
									@break
								@case(2)
									{{ 'Belum Tindak Lanjut' }}
									@break
								@case(3)
									{{ 'Telah Tindak Lanjut' }}
									@break
								@case(4)
									{{ 'Selesai' }}
									@break
								@default
									{{ 'Tidak Diketahui' }}
							@endswitch
						</td>
					</tr>
					@if ($key == 5)
						@break;
					@endif
				@endforeach
			</table>
			<div style="text-align:center;font-size:12px;margin-top:15px;">
				<a href="{{ url('list-temuan') }}">Lihat Selengkapnya</a>
			</div>
		</div><!-- .widget -->
	</div><!-- END column -->
	
@endsection