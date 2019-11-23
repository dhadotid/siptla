<!DOCTYPE html>
<html lang="en">
<head>
	@include('backend.includes.head')
	@yield('title')
</head>
	
<body class="menubar-left menubar-unfold menubar-light theme-primary">
<!--============= start main area -->

<!-- APP NAVBAR ==========-->
@include('backend.includes.navbar')
<!--========== END app navbar -->

<!-- APP ASIDE ==========-->
@if (Auth::user()->level==0)
	@include('backend.includes.sidebar')
@elseif (Auth::user()->level==2)
	@include('backend.includes.sidebar-operator')
@elseif (Auth::user()->level==3)
	@include('backend.includes.sidebar-opd')
@endif
<!--========== END app aside -->

<!-- navbar search -->
@include('backend.includes.search')
<!-- .navbar-search -->

<!-- APP MAIN ==========-->
<main id="app-main" class="app-main">
  <div class="wrap">
		<section class="app-content">
			<div class="row">
				
					@yield('content')
				
		</section><!-- .app-content -->
	</div><!-- .wrap -->
	@yield('modal')
	<!-- APP FOOTER -->
  @include('backend.includes.footer')
  <!-- /#app-footer -->
</main>


	<script>
		// "global" vars, built using blade
		var flagsUrl = '{{ url("/") }}';
		// alert(flagsUrl);
	</script>
	<script src="{{asset('theme/backend/libs/bower/jquery/dist/jquery.js')}}"></script>
	<script src="{{asset('theme/backend/libs/bower/jquery-ui/jquery-ui.min.js')}}"></script>
	<script src="{{asset('theme/backend/libs/bower/jQuery-Storage-API/jquery.storageapi.min.js')}}"></script>
	<script src="{{asset('theme/backend/libs/bower/bootstrap-sass/assets/javascripts/bootstrap.js')}}"></script>
	<script src="{{asset('theme/backend/libs/bower/jquery-slimscroll/jquery.slimscroll.js')}}"></script>
	<script src="{{asset('theme/backend/libs/bower/perfect-scrollbar/js/perfect-scrollbar.jquery.js')}}"></script>
	<script src="{{asset('theme/backend/libs/bower/PACE/pace.min.js')}}"></script>
	<!-- endbuild -->

	<!-- build:js theme/backend/assets/js/app.min.js -->
	<script src="{{asset('theme/backend/assets/js/library.js')}}"></script>
	<script src="{{asset('theme/backend/assets/js/plugins.js')}}"></script>
	<script src="{{asset('theme/backend/assets/js/app.js')}}"></script>
	<script src="{{asset('js/sweetalert.min.js')}}"></script>
	<!-- endbuild -->
	<script src="{{asset('theme/backend/libs/bower/moment/moment.js')}}"></script>
	<script src="{{asset('theme/backend/libs/bower/fullcalendar/dist/fullcalendar.min.js')}}"></script>
	<script src="{{asset('theme/backend/assets/js/fullcalendar.js')}}"></script>
	<script src="{{asset('theme/backend/libs/bower/select2/dist/js/select2.full.min.js')}}"></script>
	<script src="{{asset('js/js.js')}}"></script>
	
	@yield('footscript')
</body>
</html>
<style>
table th,table td
{
	font-size:12px !important;
}
.datepicker { z-index: 10000 !important; }
.submenu a
{
	font-size:11px !important;
	font-weight: 400 !important;
}
.app-menu li
{
	font-weight: bold !important;
}
</style>