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
                <a href="{{url('data-lhp')}}">
                    <div class="widget p-md clearfix">
                        <div class="pull-left">
                            <small class="text-color">Jumlah </small>
                            <h3 class="widget-title" style="padding-top:10px";>LHP</h3>
                        </div>
                        <span class="pull-right fz-lg fw-500 counter">
                            <h3 class="counter " data-plugin="counterUp" style="font-size:30px !important;">{{$lhp->count()}}</h3>
                        </span>
                    </div><!-- .widget -->
                </a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3 col-sm-6">
				<div class="widget stats-widget">
					<div class="widget-body clearfix">
						<div class="pull-left">
							<h3 class="widget-title text-primary"><span class="counter" data-plugin="counterUp">{{isset($datalhp['create-lhp']) ? count($datalhp['create-lhp']) : ''}}</span></h3>
							<small class="text-color"><a href="{{url('data-lhp')}}">Jumlah LHP Status Create</a></small>
						</div>
						<span class="pull-right big-icon watermark"><i class="fa fa-list"></i></span>
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
							<h3 class="widget-title text-danger"><span class="counter" data-plugin="counterUp">{{isset($datalhp['review-lhp']) ? count($datalhp['review-lhp']) : ''}}</span></h3>
							<small class="text-color"><a href="{{url('data-lhp')}}">Jumlah LHP Status Review</a></small>
						</div>
						<span class="pull-right big-icon watermark"><i class="fa fa-list"></i></span>
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
							<h3 class="widget-title text-success"><span class="counter" data-plugin="counterUp">{{isset($datalhp['publish-lhp']) ? count($datalhp['publish-lhp']) : ''}}</span></h3>
							<small class="text-color"><a href="{{url('data-lhp')}}">Jumlah LHP Status Publish</a></small>
						</div>
						<span class="pull-right big-icon watermark"><i class="fa fa-list"></i></span>
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
							<h3 class="widget-title text-warning"><span class="counter" data-plugin="counterUp">{{ $status }}</span></h3>
							<small class="text-color"><a href="{{url('status-rekomendasi')}}">Jumlah Status Rekomendasi</a></small>
						</div>
						<span class="pull-right big-icon watermark"><i class="fa fa-list"></i></span>
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
                <h4 class="widget-title">Presentase Jumlah Rekomendasi Berdasarkan Status</h4>
                </header><!-- .widget-header -->
                <hr class="widget-separator">
                <div class="widget-body">
                    <div data-plugin="chart" data-options="{
                        tooltip : {
                            trigger: 'item',
                            formatter: '{a} <br/>{b} : {c} ({d}%)'
                        },
                        legend: {
                            orient: 'horizontal',
                            x: 'left',
                            data: ['Sudah Selesai/Sesuai (SS)','Belum Selesai/Sesuai (BS)','Belum Ditindaklanjutin (BTL)','Tidak Dapat Ditindaklanjutin (TDL)']
                        },
                        series : [
                            {
                                name: 'Jumlah',
                                type: 'pie',
                                radius : '45%',
                                center: ['50%', '70%'],
                                data:[
                                    {value:1, name:'Sudah Selesai/Sesuai (SS)'},
                                    {value:4, name:'Belum Selesai/Sesuai (BS)'},
                                    {value:3, name:'Belum Ditindaklanjutin (BTL)'},
                                    {value:2, name:'Tidak Dapat Ditindaklanjutin (TDL)'},
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
		<div class="widget">
			<header class="widget-header">
			<h4 class="widget-title">Rekomendasi Yang Belum Di Tindak Lanjutin</h4>
			</header><!-- .widget-header -->
			<hr class="widget-separator">
			<div class="widget-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">No LHP</th>
                            <th class="text-center">Jenis Audit/Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lhp as $key=>$item)
                            @if ($item->flag_tindaklanjut_id==0)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">{{$item->no_lhp}}</td>
                                    <td class="text-left">{{$item->djenisaudit->jenis_audit}}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div style="text-align:center;font-size:12px;margin-top:15px;">
                    <a href="{{ url('data-lhp') }}">Lihat Selengkapnya</a>
                </div>
			</div><!-- .widget-body -->
		</div><!-- .widget -->
	</div><!-- END column -->

	
	
@endsection