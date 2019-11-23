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
					<div class="pull-left">
						<small class="text-color">Jumlah </small>
						<h3 class="widget-title" style="padding-top:10px";>Jenis Audit</h3>
					</div>
					<span class="pull-right fz-lg fw-500 counter">
                        <h3 class="counter " data-plugin="counterUp" style="font-size:30px !important;">{{$jenisaudit}}</h3>
                    </span>
				</div><!-- .widget -->
			</div>
		</div>
		<div class="row">
			<div class="col-md-3 col-sm-6">
				<div class="widget stats-widget">
					<div class="widget-body clearfix">
						<div class="pull-left">
							<h3 class="widget-title text-primary"><span class="counter" data-plugin="counterUp">{{$picunit->count()}}</span></h3>
							<small class="text-color"><a href="{{url('pic-unit')}}">Jumlah PIC Unit</a></small>
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
							<h3 class="widget-title text-danger"><span class="counter" data-plugin="counterUp">{{ $pemeriksa }}</span></h3>
							<small class="text-color"><a href="{{url('pemeriksa')}}">Jumlah Pemeriksa</a></small>
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
							<h3 class="widget-title text-success"><span class="counter" data-plugin="counterUp">{{ $status }}</span></h3>
							<small class="text-color"><a href="{{url('status-rekomendasi')}}">Jumlah Status Rekomendasi</a></small>
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
							<h3 class="widget-title text-warning"><span class="counter" data-plugin="counterUp">{{ $jenistemuan }}</span></h3>
							<small class="text-color"><a href="{{url('jenis-temuan')}}">Jumlah Jenis Temuan</a></small>
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
			<h4 class="widget-title">Daftar PIC Unit</h4>
			</header><!-- .widget-header -->
			<hr class="widget-separator">
			<div class="widget-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Level PIC Unit</th>
                            <th class="text-center">Bidang/Fakultas</th>
                            <th class="text-center">Nama PIC Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($picunit as $key=>$item)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-left">{{$item->levelpic->nama_level}}</td>
                                <td class="text-center">
                                    @if (isset($item->bid->nama_bidang))
                                        {{$item->bid->nama_bidang}}
                                    @elseif (isset($item->fak->nama_fakultas))
                                        {{$item->fak->nama_fakultas}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-left">{{$item->nama_pic}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="text-align:center;font-size:12px;margin-top:15px;">
                    <a href="{{ url('pic-unit') }}">Lihat Selengkapnya</a>
                </div>
			</div><!-- .widget-body -->
		</div><!-- .widget -->
	</div><!-- END column -->

	<div class="col-md-6">
		<div class="widget p-lg">
			&nbsp;
		</div><!-- .widget -->
	</div><!-- END column -->
	
@endsection