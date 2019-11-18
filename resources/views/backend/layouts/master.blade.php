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
@if (Auth::user()->level==1)
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
<!--========== END app main -->

	<!-- APP CUSTOMIZER -->
	{{-- <div id="app-customizer" class="app-customizer">
		<a href="javascript:void(0)" 
			class="app-customizer-toggle theme-color" 
			data-toggle="class" 
			data-class="open"
			data-active="false"
			data-target="#app-customizer">
			<i class="fa fa-gear"></i>
		</a>
		<div class="customizer-tabs">
			<!-- tabs list -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#menubar-customizer" aria-controls="menubar-customizer" role="tab" data-toggle="tab">Menubar</a></li>
				<li role="presentation"><a href="#navbar-customizer" aria-controls="navbar-customizer" role="tab" data-toggle="tab">Navbar</a></li>
			</ul><!-- .nav-tabs -->

			<div class="tab-content">
				<div role="tabpanel" class="tab-pane in active fade" id="menubar-customizer">
					<div class="hidden-menubar-top hidden-float">
						<div class="m-b-0">
							<label for="menubar-fold-switch">Fold Menubar</label>
							<div class="pull-right">
								<input id="menubar-fold-switch" type="checkbox" data-switchery data-size="small" />
							</div>
						</div>
						<hr class="m-h-md">
					</div>
					<div class="radio radio-default m-b-md">
						<input type="radio" id="menubar-light-theme" name="menubar-theme" data-toggle="menubar-theme" data-theme="light">
						<label for="menubar-light-theme">Light</label>
					</div>

					<div class="radio radio-inverse m-b-md">
						<input type="radio" id="menubar-dark-theme" name="menubar-theme" data-toggle="menubar-theme" data-theme="dark">
						<label for="menubar-dark-theme">Dark</label>
					</div>
				</div><!-- .tab-pane -->
				<div role="tabpanel" class="tab-pane fade" id="navbar-customizer">
					<!-- This Section is populated Automatically By javascript -->
				</div><!-- .tab-pane -->
			</div>
		</div><!-- .customizer-taps -->
		<hr class="m-0">
		<div class="customizer-reset">
			<button id="customizer-reset-btn" class="btn btn-block btn-outline btn-primary">Reset</button>
			<a href="https://themeforest.net/item/infinity-responsive-web-app-kit/16230780" class="m-t-sm btn btn-block btn-danger">Buy Now</a>
		</div>
	</div><!-- #app-customizer --> --}}
	
	<!-- build:js theme/backend/assets/js/core.min.js -->
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
	<!-- endbuild -->
	<script src="{{asset('theme/backend/libs/bower/moment/moment.js')}}"></script>
	<script src="{{asset('theme/backend/libs/bower/fullcalendar/dist/fullcalendar.min.js')}}"></script>
	<script src="{{asset('theme/backend/assets/js/fullcalendar.js')}}"></script>
	@yield('footscript')
</body>
</html>
<style>
table th,table td
{
	font-size:11px !important;
}
.datepicker { z-index: 10000 !important; }
</style>