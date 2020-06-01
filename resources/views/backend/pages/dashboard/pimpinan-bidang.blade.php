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
					<canvas id="myChart" height="265px"></canvas>
				</div>
            </div>
            <div class="col-md-5 col-sm-5">
				<div class="widget p-md clearfix">
					<div class="pull-right">
						<small class="text-color">Tingkat Penyelesaian Temuan</small>
                    </div>
                    <br><br>
                    <div class="col-md-12" height="170px">
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <canvas id="totalTemuan" height="170px"></canvas>
                                <div class="text-center">
                                    <small class="text-color">Total Temuan</small>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <canvas id="temuanAuditInternal" height="170px"></canvas>
                                <div class="text-center">
                                    <small class="text-color">Temuan Audit Internal</small>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <canvas id="temuanAuditExternal" height="170px"></canvas>
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
					<canvas id="chartOverdue" height="90px"></canvas>
				</div>
            </div>
            <div class="col-md-5 col-sm-5">
				<div class="widget p-md clearfix" >
					<div class="pull-right">
						<small class="text-color">Monitoring Tindak Lanjut</small>
                    </div>
                    <br><br>
					<canvas id="chartTindakLanjut" height="90px"></canvas>
				</div>
            </div>
        </div>
	</div>


@endsection
@section('footscript')
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato"/>
<script src="{{asset('js/Chart.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
<script>
var ctx = document.getElementById("myChart").getContext("2d");
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
    	yAxes: [{
      	stacked: true,
      	ticks: {
        	beginAtZero: true 
         }
      }]
    },
    plugins: {
        labels: {
            render: function (args) {
                // console.log(args);
                if(args.value!=0)
                return args.value;
            },
            arc: true
            // position: 'outside',
            // render: 'value',
            // fontSize: 14,
            //     fontStyle: 'bold',
            //     fontColor: '#000',
            }
    }
  }
});
</script>

<script>
var ctx = document.getElementById('totalTemuan').getContext('2d');
var totalTemuan = <?php echo json_encode($jsonTotalTemuan);?>;
var myChart = new Chart(ctx, {
    type: 'pie',
    data: totalTemuan,
    options: {
        legend: {
            display: false
         },
        plugins: {
            labels: {
                render: function (args) {
                // console.log('hehe 'args);
                if(args.value!=0)
                    // console.log('hehe '+args);
                },
                arc: true
                render: 'percentage',
                fontSize: 14,
                fontStyle: 'bold',
                fontColor: '#ffff',
            }
        }
    }
});

var ctx = document.getElementById('temuanAuditInternal').getContext('2d');
var totalPemeriksaInternal = <?php echo json_encode($jsonPemeriksaInternal);?>;
var myChart = new Chart(ctx, {
    type: 'pie',
    data: totalPemeriksaInternal,
    options: {
        legend: {
            display: false
         },
         plugins: {
            labels: {
                render: 'percentage',
                fontSize: 14,
                fontStyle: 'bold',
                fontColor: '#ffff',
            }
        }
    }
});

var ctx = document.getElementById('temuanAuditExternal').getContext('2d');
var totalPemeriksaExternal = <?php echo json_encode($jsonPemeriksaExternal);?>;
var myChart = new Chart(ctx, {
    type: 'pie',
    data: totalPemeriksaExternal,
    options: {
        legend: {
            display: false
         },
         plugins: {
            labels: {
                render: 'percentage',
                fontSize: 14,
                fontStyle: 'bold',
                fontColor: '#ffff',
            }
        }
    }
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

var ctx = document.getElementById('chartTindakLanjut').getContext('2d');
var data = <?php echo json_encode($jsonTemuan);?>;
var chart = new Chart(ctx, {
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

		.dropdown-content a:hover {background-color: #ddd;}

		.dropdown:hover .dropdown-content {display: block;}

		.dropdown:hover .dropbtn {background-color: #3e8e41;}
		</style>
@endsection