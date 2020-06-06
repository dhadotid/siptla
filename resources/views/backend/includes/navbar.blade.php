<nav id="app-navbar" class="navbar navbar-inverse navbar-fixed-top primary">

  <!-- navbar header -->
  <div class="navbar-header">
    <button type="button" id="menubar-toggle-btn" class="navbar-toggle visible-xs-inline-block navbar-toggle-left hamburger hamburger--collapse js-hamburger">
      <span class="sr-only">Toggle navigation</span>
      <span class="hamburger-box"><span class="hamburger-inner"></span></span>
    </button>

    <button type="button" class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
      <span class="sr-only">Toggle navigation</span>
      <span class="zmdi zmdi-hc-lg zmdi-more"></span>
    </button>

    <a href="theme/backend/index.html" class="navbar-brand">
      <span class="brand-icon"><img src="{{asset('logo.png')}}" style="height:30px;"></span>
      <span class="brand-name">SIPTLA</span>
    </a>
  </div><!-- .navbar-header -->
  
  <div class="navbar-container container-fluid">
    <div class="collapse navbar-collapse" id="app-navbar-collapse">
      <ul class="nav navbar-toolbar navbar-toolbar-left navbar-left">
        <li class="hidden-float hidden-menubar-top">
          <a href="javascript:void(0)" role="button" id="menubar-fold-btn" class="hamburger hamburger--arrowalt is-active js-hamburger">
            <span class="hamburger-box"><span class="hamburger-inner"></span></span>
          </a>
        </li>
        <li>
          <h5 class="page-title hidden-menubar-top hidden-float">Dashboard</h5>
        </li>
      </ul>

      <ul class="nav navbar-toolbar navbar-toolbar-right navbar-right">

      <li class="dropdown" width="150px">
          <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="zmdi zmdi-hc-lg zmdi-notifications">
          @if(count($notificationData) > 0)<span class="badge bg-danger">{{count($notificationData)}}</span>@endif
          </i></a>
          @if(Auth::check())
          @if(count($notificationData) > 0)
          <ul class="dropdown-menu animated flipInY" style="width: 400px !important;">
          @foreach ($notificationData as $data)
           <li>
              <a href="{{url('read-notification/'.$data->id_lhp.'/'.$data->id_rekomendasi.'/'.$data->id)}}">
              {{--<a href="{{url('data-temuan-lhp/'.$lhp_id)}}">--}}
              <span style="display: inline-block; width: 380px; white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;">{{ $data->status }}</span>
              </a>
            </li>
            @endforeach
            @else
            <ul class="dropdown-menu animated flipInY" style="width: 180px !important;">
            <li>
              <a>
              <span style="display: inline-block; width: 180px; white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;">Tidak ada notifikasi baru</span>
            </a>
            </li>
            @endif
          </ul>
          {{--<span class="media-annotation pull-right">{{ \Carbon\Carbon::parse($data->updated_at)->diffForHumans() }}</span>--}}
          @endif
        </li>

        <li class="dropdown">
          <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="zmdi zmdi-hc-lg zmdi-settings"></i></a>
          <ul class="dropdown-menu animated flipInY">
           <li><a href="{{url('profil')}}"><i class="zmdi m-r-md zmdi-hc-lg zmdi-sign-out"></i>Profil Pengguna</a></li>
           <li><a href="{{url('logout')}}"><i class="zmdi m-r-md zmdi-hc-lg zmdi-sign-out"></i>Logout</a></li>
          </ul>
        </li>

      </ul>
    </div>
  </div><!-- navbar-container -->
</nav>