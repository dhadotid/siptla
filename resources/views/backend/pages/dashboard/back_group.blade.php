<canvas id="myChart"></canvas>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato"/>
<script src="{{asset('js/Chart.min.js')}}"></script>
<script>
var ctx = document.getElementById("myChart").getContext("2d");
var data = {
  labels: ["Data1", "Data2", "Data3"],
  datasets: [{
      label: "Apples",
      backgroundColor: "#F29220",
      borderColor: "#F29220",
      data: [40,20,30]
    }, {
      label: "Bananas",
      backgroundColor: "#4365B0",
      borderColor: "#4365B0",
      data: [60,80,70]
    }, {
      label: "Cookies",
      backgroundColor: "#D00",
      borderColor: "#D00",
      data: [10,5,10]
    }]
};

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
    }
  }
});


</script>