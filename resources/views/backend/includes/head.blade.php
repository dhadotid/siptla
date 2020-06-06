<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="SIPTLA SPI Universitas Indonesia" />

<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="shortcut icon" sizes="196x196" href="{{asset('/')}}/logo.png">

<link rel="stylesheet" href="{{asset('theme/backend/libs/bower/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{asset('theme/backend/libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.css')}}">

<!-- build:css theme/backend/assets/css/app.min.css -->
<link rel="stylesheet" href="{{asset('theme/backend/libs/bower/animate.css/animate.min.css')}}">
<link rel="stylesheet" href="{{asset('theme/backend/libs/bower/fullcalendar/dist/fullcalendar.min.css')}}">
<link rel="stylesheet" href="{{asset('theme/backend/libs/bower/perfect-scrollbar/css/perfect-scrollbar.css')}}">
<link rel="stylesheet" href="{{asset('theme/backend/assets/css/bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('theme/backend/assets/css/core.css')}}">
<link rel="stylesheet" href="{{asset('theme/backend/assets/css/app.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('theme/backend/libs/bower/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.css')}}">
<!-- endbuild -->



<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300">
<script src="{{asset('theme/backend/libs/bower/breakpoints.js/dist/breakpoints.min.js')}}"></script>

<script>
    Breakpoints();
</script>
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-analytics.js"></script>

<script>
  // Your web app's Firebase configuration
  var firebaseConfig = {
    apiKey: "AIzaSyBIdoXxWrkmgFSILxa3KF9JSt_hAWk2Vtg",
    authDomain: "ui-siptla.firebaseapp.com",
    databaseURL: "https://ui-siptla.firebaseio.com",
    projectId: "ui-siptla",
    storageBucket: "ui-siptla.appspot.com",
    messagingSenderId: "824351596044",
    appId: "1:824351596044:web:34175e5e72f16eb3855829",
    measurementId: "G-NWETZ3K68D"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
</script>