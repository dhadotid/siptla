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
			<div class="col-md-7 col-sm-7">
				<div class="widget p-md clearfix" >
					<div class="pull-left">
						<small class="text-color">Temuan Per - Bidang</small>
                    </div>
                    <br><br>
					<canvas id="myChart" height="280%"></canvas>
				</div>
            </div>
            <div class="col-md-5 col-sm-5">
				<div class="widget p-md clearfix">
					<div class="pull-right">
						<small class="text-color">Tingkat Penyelesaian Temuan</small>
                    </div>
                    <br><br>
                    <div class="col-md-12" height="92%">
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <!-- <canvas id="totalTemuan" height="170px"></canvas> -->
                                <div id="totalTemuan" class="circle-container" height="93%" ></div>
                                <div class="text-center">
                                    <small class="text-color">Total Temuan</small>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div id="temuanAuditInternal" class="circle-container" height="93%" ></div>
                                <div class="text-center">
                                    <small class="text-color">Temuan Audit Internal</small>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div id="temuanAuditExternal" class="circle-container" height="93%" ></div>
                                <div class="text-center">
                                    <small class="text-color">Temuan Audit External</small>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
            </div>
            <div class="col-md-5 col-sm-5">
				<div class="widget p-md clearfix" >
					<div class="pull-right">
						<small class="text-color">Rekomendasi Yang Overdue</small>
                    </div>
                    <br><br>
					<canvas id="chartOverdue" height="98%"></canvas>
				</div>
            </div>
            <div class="col-md-5 col-sm-5">
				<div class="widget p-md clearfix" >
					<div class="pull-right">
						<small class="text-color">Monitoring Tindak Lanjut</small>
                    </div>
                    <br><br>
					<canvas id="chartTindakLanjut" height="98%"></canvas>
				</div>
            </div>
        </div>
	</div>


@endsection
@section('footscript')
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato"/>
<script src="{{asset('js/Chart.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.0.1/progressbar.min.js"></script>
<script>
var ctx = document.getElementById("myChart");
var data = <?php echo json_encode($temuans);?>;
// var data = {
//   labels: ["PIC1", "PIC2", "PIC3", "PIC4", "PIC5", "Total"],
//   datasets: [{
//       label: "Sedang Diperbaiki",
//       backgroundColor: "#F29220",
//       data: [40,20,30]
//     }, {
//       label: "On Going",
//       backgroundColor: "#4365B0",
//       data: [60,80,70]
//     }, {
//       label: "Akan Datang",
//       backgroundColor: "#D00",
//       data: [10,5,10]
//     }]
// };

var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: data,
  options: {
    scales: {
  		xAxes: [{stacked: true}],
    	yAxes: [{stacked: true}]
    },
    plugins: {
        labels: {
            render: function (args) {
                // console.log(args);
                if(args.value!=0)
                return args.value;
            },
            arc: true
            }
    }
  }
});

ctx.onclick = function(evt) {
   var activePoint = myBarChart.getElementAtEvent(evt)[0];
   var data = activePoint._chart.data;
   var datasetIndex = activePoint._datasetIndex;
   var label = data.datasets[datasetIndex].label;
   var bidang = data.labels[activePoint._index];
   var value = data.datasets[datasetIndex].data[activePoint._index];
   var tahn = $('#tahun').val();

  //  var url = '{{ route("laporan-pimpinan-perbidang", ["key"=>'bidang'] ) }}';
  //  location.href = url;
  location.href = flagsUrl+'/laporan/tindaklanjut-per-bidang-pimpinan?pemeriksa='+
   bidang+'&category='+label+'&tahun='+tahn+'&title=Temuan Per-Bidang';
};
</script>

<script>
var circleBar = new ProgressBar.Circle("#totalTemuan", {
  color: "#a8d1f5",
  strokeWidth: 1,
  trailWidth: 25,
  trailColor: "#5895f1",
  easing: "easeInOut",
  from: { color: "#5895f1", width: 1 },
  to: { color: "#a8d1f5", width: 25 },
  text: {
    value: '30',
    className: 'progress-text',
    style: {
      color: 'black',
      position: 'absolute',
      top: '35%',
      left: '30%',
      padding: 0,
      margin: 0,
      transform: null
    }
  },
  step: (state, shape) => {
    shape.path.setAttribute("stroke-width", 25);
    shape.setText(Math.round(shape.value() * 100) + ' %');
  }
});
var circleInternalBar = new ProgressBar.Circle("#temuanAuditInternal", {
  color: "#a8d1f5",
  strokeWidth: 1,
  trailWidth: 25,
  trailColor: "#5895f1",
  easing: "easeInOut",
  from: { color: "#5895f1", width: 1 },
  to: { color: "#a8d1f5", width: 25 },
  text: {
    value: '30',
    className: 'progress-text',
    style: {
      color: 'black',
      position: 'absolute',
      top: '35%',
      left: '30%',
      padding: 0,
      margin: 0,
      transform: null
    }
  },
  step: (state, shape) => {
    shape.path.setAttribute("stroke-width", 25);
    shape.setText(Math.round(shape.value() * 100) + ' %');
  }
});
var circleExternalBar = new ProgressBar.Circle("#temuanAuditExternal", {
  color: "#a8d1f5",
  strokeWidth: 1,
  trailWidth: 25,
  trailColor: "#5895f1",
  easing: "easeInOut",
  from: { color: "#5895f1", width: 1 },
  to: { color: "#a8d1f5", width: 25 },
  text: {
    value: '30',
    className: 'progress-text',
    style: {
      color: 'black',
      position: 'absolute',
      top: '35%',
      left: '30%',
      padding: 0,
      margin: 0,
      transform: null
    }
  },
  step: (state, shape) => {
    shape.path.setAttribute("stroke-width", 25);
    shape.setText(Math.round(shape.value() * 100) + ' %');
  }
});
var internalSPI = <?php echo json_encode($finalInternalSPI);?>;
var externalSPI = <?php echo json_encode($finalExternal);?>;
var totalTemuan = '0.'+ (Number(internalSPI) + Number(externalSPI));
circleExternalBar.animate('0.'+Number(externalSPI), {
    duration: 1700
});
circleInternalBar.animate('0.'+Number(internalSPI), {
    duration: 1600
});
circleBar.animate(totalTemuan, {
  duration: 1500
});

var ctx = document.getElementById('chartOverdue').getContext('2d');
var data = <?php echo json_encode($rekomJson);?>;
var chart = new Chart(ctx, {
    type: 'doughnut',
    data: data,
    options: {
        showDatasetLabels : true,
        legend: {
        position: 'right'
        },
        plugins: {
            labels: {
                render: 'value',
                fontSize: 14,
                fontStyle: 'bold',
                fontColor: '#ffff',
            }
        }
    }
});

var chartTindakLanjut = document.getElementById('chartTindakLanjut');
var data = <?php echo json_encode($jsonTemuan);?>;
var tindaklanjutChart = new Chart(chartTindakLanjut, {
    type: 'doughnut',
    data: data,
    options: {
        showDatasetLabels : true,
        legend: {
            display: true, 
            position: 'right'
        },
        plugins: {
            labels: {
                render: 'value',
                fontSize: 14,
                fontStyle: 'bold',
                fontColor: '#ffff',
            }
        }
    }
});
chartTindakLanjut.onclick = function(evt) {
   var activePoint = tindaklanjutChart.getElementAtEvent(evt)[0];
   var data = activePoint._chart.data;
   var datasetIndex = activePoint._datasetIndex;
   var label = data.datasets[datasetIndex].label;
   var bidang = data.labels[activePoint._index];
   var value = data.datasets[datasetIndex].data[activePoint._index];
   var tahn = $('#tahun').val();

  //  var url = '{{ route("laporan-pimpinan-perbidang", ["key"=>'bidang'] ) }}';
  //  location.href = url;
  location.href = flagsUrl+'/laporan/tindaklanjut-per-bidang-pimpinan?rekomstatus='+
   bidang+'&tahun='+tahn+'&title=Monitoring Tindak Lanjut';
};
</script>
<style>
    .scroll-box {
        width: auto;
        height: 200px;
        white-space:nowrap;
    }
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
		<style>
		.dropdown {
		position: relative;
		display: inline-block;
		}

		.dropdown-content {
		display: none;
		position: absolute;
		background-color: #f1f1f1;
		min-width: 160px;
		box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
		z-index: 1;
		}

		.dropdown-content a {
		color: black;
		padding: 12px 16px;
		text-decoration: none;
		display: block;
		}

        .circle-container {
            width: 80px;
            height: 80px;
            margin: auto;
        }
        /* .progress-text {
            font-size: 1.25em;
            color: white;
            margin-bottom: 1em;
            font-weight: 60;
        } */

		.dropdown-content a:hover {background-color: #ddd;}

		.dropdown:hover .dropdown-content {display: block;}

		.dropdown:hover .dropbtn {background-color: #3e8e41;}
		</style>
@endsection