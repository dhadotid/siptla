<aside id="menubar" class="menubar light">
  <div class="app-user">
    <div class="media">
      <div class="media-left">
        
      </div>
      <div class="media-body">
        <div class="foldable">
          <h5><a href="javascript:void(0)" class="username">Admin Utama</a></h5>
          <ul>
            <li class="dropdown">
              <a href="javascript:void(0)" class="dropdown-toggle usertitle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <small>Administrator Utama</small>
              </a>
              
            </li>
          </ul>
        </div>
      </div><!-- .media-body -->
    </div><!-- .media -->
  </div><!-- .app-user -->
@php
    $url=Request::path();
@endphp
  <div class="menubar-scroll">
    <div class="menubar-scroll-inner">
      <ul class="app-menu">
        <li class="has-submenu {{$url=='dashboard' ? 'active open' : ''}}">
          <a href="{{url('dashboard')}}">
            <i class="menu-icon zmdi zmdi-view-dashboard zmdi-hc-lg"></i>
            <span class="menu-text">Dashboard</span>
          </a>
        </li>
        <li class="has-submenu {{$url=='data-temuan' || $url=='data-penyebab' || $url=='data-rekomendasi' || $url=='bidang-pengawasan' ? 'active open' : ''}}">
          <a href="javascript:void(0)" class="submenu-toggle">
            <i class="menu-icon fa fa-bars"></i>
            <span class="menu-text">Master Data</span>
            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i>
          </a>
          @php
              $master=['pejabat-penandatangan','data-temuan','level-pic','pic-unit','pemeriksa','jenis-audit','jangka-waktu','status-rekomendasi','jenis-temuan','rekanan','level-resiko','data-lhp','level-pengguna','pengguna'];
          @endphp
          <ul class="submenu" style="{{in_array($url,$master) ? 'display:block' : ''}}">
            @if (Auth::user()->level=='auditor-junior' || Auth::user()->level=='auditor-senior')
              <li class="{{$url=='pic-unit'  ? 'active open' : ''}}"><a href="{{url('pic-unit')}}"><span class="menu-text">PIC Unit</span></a></li>    
              <li class="{{$url=='pemeriksa'  ? 'active open' : ''}}"><a href="{{url('pemeriksa')}}"><span class="menu-text">Pemeriksa</span></a></li>
              <li class="{{$url=='jenis-audit'  ? 'active open' : ''}}"><a href="{{url('jenis-audit')}}"><span class="menu-text">Jenis Audit</span></a></li>
              <li class="{{$url=='jangka-waktu'  ? 'active open' : ''}}"><a href="{{url('jangka-waktu')}}"><span class="menu-text">Jangka Waktu Penyelesaian</span></a></li>
              <li class="{{$url=='status-rekomendasi'  ? 'active open' : ''}}"><a href="{{url('status-rekomendasi')}}"><span class="menu-text">Status Rekomendasi</span></a></li>
              <li class="{{$url=='jenis-temuan' || $url=='data-temuan'  ? 'active open' : ''}}"><a href="{{url('jenis-temuan')}}"><span class="menu-text">Jenis Temuan</span></a></li>
              <li class="{{$url=='rekanan' ? 'active open' : ''}}"><a href="{{url('rekanan')}}"><span class="menu-text">Rekanan</span></a></li>
              <li class="{{$url=='level-resiko' ? 'active open' : ''}}"><a href="{{url('level-resiko')}}"><span class="menu-text">Level Resiko</span></a></li>
            @endif
            @if (Auth::user()->level=='0')
                <li class="{{$url=='level-pic'  ? 'active open' : ''}}"><a href="{{url('level-pic')}}"><span class="menu-text">Level PIC Unit</span></a></li>
                <li class="{{$url=='pic-unit'  ? 'active open' : ''}}"><a href="{{url('pic-unit')}}"><span class="menu-text">PIC Unit</span></a></li>
                <li class="{{$url=='pemeriksa'  ? 'active open' : ''}}"><a href="{{url('pemeriksa')}}"><span class="menu-text">Pemeriksa</span></a></li>
                <li class="{{$url=='jenis-audit'  ? 'active open' : ''}}"><a href="{{url('jenis-audit')}}"><span class="menu-text">Jenis Audit</span></a></li>
                <li class="{{$url=='jangka-waktu'  ? 'active open' : ''}}"><a href="{{url('jangka-waktu')}}"><span class="menu-text">Jangka Waktu Penyelesaian</span></a></li>
                <li class="{{$url=='status-rekomendasi'  ? 'active open' : ''}}"><a href="{{url('status-rekomendasi')}}"><span class="menu-text">Status Rekomendasi</span></a></li>
                <li class="{{$url=='jenis-temuan' || $url=='data-temuan'  ? 'active open' : ''}}"><a href="{{url('jenis-temuan')}}"><span class="menu-text">Jenis Temuan</span></a></li>
                <li class="{{$url=='rekanan' ? 'active open' : ''}}"><a href="{{url('rekanan')}}"><span class="menu-text">Rekanan</span></a></li>
                <li class="{{$url=='level-resiko' ? 'active open' : ''}}"><a href="{{url('level-resiko')}}"><span class="menu-text">Level Resiko</span></a></li>
                <li class="{{$url=='pejabat-penandatangan' ? 'active open' : ''}}"><a href="{{url('pejabat-penandatangan')}}"><span class="menu-text">Pejabat Penanda Tangan</span></a></li>
                
                <li class="{{$url=='data-lhp' ? 'active open' : ''}}"><a href="{{url('data-lhp')}}"><span class="menu-text">Data LHP</span></a></li> 
                <li class="{{$url=='level-pengguna' ? 'active open' : ''}}"><a href="{{url('level-pengguna')}}"><span class="menu-text">Data Level Pengguna</span></a></li> 
                <li class="{{$url=='pengguna' ? 'active open' : ''}}"><a href="{{url('pengguna')}}"><span class="menu-text">Data Pengguna</span></a></li> 
            @endif
          </ul>
        </li>
        @if (Auth::user()->level=='auditor-junior')
          <li class="{{$url=='data-lhp' ? 'active' : ''}}">
            <a href="{{url('data-lhp')}}">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text">Data LHP</span>
            </a>
          </li>
        @endif
        @if (Auth::user()->level=='auditor-senior')
          <li class="has-submenu {{strpos($url,'data-lhp')!==false ? 'active open' : ''}}">
            <a href="javascript:void(0)" class="submenu-toggle">
              <i class="menu-icon fa fa-archive"></i>
              <span class="menu-text">Master Data LHP</span>
              <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i>
            </a>
            <ul class="submenu" style="{{strpos($url,'data-lhp')!==false || strpos($url,'semua-data-lhp')!==false ? 'display:block' : ''}}">
              <li class="{{strpos($url,'semua-data-lhp')!==false ? 'active open' : ''}}"><a href="{{url('semua-data-lhp')}}/{{ date('Y') }}"><span class="menu-text">Semua Data LHP</span></a></li>
              <li class="{{strpos($url,'data-lhp')!==false ? 'active open' : ''}}"><a href="{{url('data-lhp')}}/{{ date('Y') }}"><span class="menu-text">Data LHP</span></a></li>
            </ul>
          </li>
        @endif
        <li class="has-submenu {{strpos($url,'laporan')!==false ? 'active open' : ''}}">
          <a href="javascript:void(0)" class="submenu-toggle">
            <i class="menu-icon fa fa-archive"></i>
            <span class="menu-text">Laporan</span>
            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i>
          </a>
          <ul class="submenu" style="{{$url=='rekap-temuan' || $url=='rekomendasi-temuan' || $url=='laporan-kelompok-temuan' ? 'display:block' : ''}}">
            <li class="{{$url=='rekap-temuan' ? 'active' : ''}}"><a href="{{url('rekap-temuan')}}"><span class="menu-text">Rekap Temuan</span></a></li>
            <li class="{{$url=='rekomendasi-temuan' ? 'active' : ''}}"><a href="{{url('rekomendasi-temuan')}}/{{ date('Y') }}"><span class="menu-text">Per Rekomendasi Temuan</span></a></li>
            <li class="{{$url=='laporan-kelompok-temuan' ? 'active' : ''}}"><a href="{{url('laporan-kelompok-temuan')}}/{{ date('Y') }}"><span class="menu-text">Per Kelompok Temuan</span></a></li>
          </ul>
        </li>
        
        <li class="menu-separator"><hr></li>

        <li>
          <a href="{{url('logout')}}">
            <i class="menu-icon fa fa-sign-out"></i>
            <span class="menu-text">Logout</span>
          </a>
        </li>
      </ul><!-- .app-menu -->
    </div><!-- .menubar-scroll-inner -->
  </div><!-- .menubar-scroll -->
</aside>