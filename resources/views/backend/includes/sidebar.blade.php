<aside id="menubar" class="menubar light">
  <div class="app-user">
    <div class="media">
      <div class="media-left">
        
      </div>
      <div class="media-body">
        <div class="foldable">
          @php
              $level=jenis_level();
              $lv=Auth::user()->level;
          @endphp
          <h5><a href="javascript:void(0)" class="username">Hallo, {{Auth::user()->name}}</a></h5>
          <ul>
            <li class="dropdown">
              <a href="javascript:void(0)" class="dropdown-toggle usertitle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <small>{{isset($level[$lv]) ? $level[$lv] : ''}}</small>
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
         @if (Auth::user()->level!='pic-unit' && Auth::user()->level!='auditor-junior' && Auth::user()->level!='auditor-senior')
          <li class="has-submenu {{$url=='data-temuan' || $url=='data-penyebab' || $url=='data-rekomendasi' || $url=='bidang-pengawasan' ? 'active open' : ''}}">
            <a href="javascript:void(0)" class="submenu-toggle">
              <i class="menu-icon fa fa-bars"></i>
              <span class="menu-text">Master Data</span>
              <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i>
            </a>
            @php
                $g_url=explode('/',$url);
                // echo $url;
                $master=['pejabat-penandatangan','data-temuan','level-pic','pic-unit','pemeriksa','jenis-audit','jangka-waktu','status-rekomendasi','jenis-temuan','rekanan','level-resiko','data-lhp','level-pengguna','pengguna','periode-review'];
            @endphp
            <ul class="submenu" style="{{in_array($g_url[0],$master) ? 'display:block' : ''}}">
              @if (Auth::user()->level=='auditor-junior' || Auth::user()->level=='auditor-senior')
                <li class="{{strpos($url,'pic-unit')!==false  ? 'active open' : ''}}"><a href="{{url('pic-unit')}}"><span class="menu-text">PIC Unit</span></a></li>    
                <li class="{{strpos($url,'pemeriksa')!==false  ? 'active open' : ''}}"><a href="{{url('pemeriksa')}}"><span class="menu-text">Pemeriksa</span></a></li>
                <li class="{{strpos($url,'jenis-audit')!==false  ? 'active open' : ''}}"><a href="{{url('jenis-audit')}}"><span class="menu-text">Jenis Audit</span></a></li>
                <li class="{{strpos($url,'jangka-waktu')!==false  ? 'active open' : ''}}"><a href="{{url('jangka-waktu')}}"><span class="menu-text">Jangka Waktu Penyelesaian</span></a></li>
                <li class="{{strpos($url,'status-rekomendasi')!==false  ? 'active open' : ''}}"><a href="{{url('status-rekomendasi')}}"><span class="menu-text">Status Rekomendasi</span></a></li>
                <li class="{{strpos($url,'jenis-temuan')!==false || $url=='data-temuan'  ? 'active open' : ''}}"><a href="{{url('jenis-temuan')}}"><span class="menu-text">Jenis Temuan</span></a></li>
                <li class="{{strpos($url,'rekanan')!==false ? 'active open' : ''}}"><a href="{{url('rekanan')}}"><span class="menu-text">Rekanan</span></a></li>
                <li class="{{strpos($url,'level-resiko')!==false ? 'active open' : ''}}"><a href="{{url('level-resiko')}}"><span class="menu-text">Level Resiko</span></a></li>
              @endif
              @if (Auth::user()->level=='0')
                  <li class="{{strpos($url,'level-pic')!==false  ? 'active open' : ''}}"><a href="{{url('level-pic')}}"><span class="menu-text">Level PIC Unit</span></a></li>
                  <li class="{{strpos($url,'pic-unit')!==false  ? 'active open' : ''}}"><a href="{{url('pic-unit')}}"><span class="menu-text">PIC Unit</span></a></li>
                  <li class="{{strpos($url,'pemeriksa')!==false  ? 'active open' : ''}}"><a href="{{url('pemeriksa')}}"><span class="menu-text">Pemeriksa</span></a></li>
                  <li class="{{strpos($url,'jenis-audit')!==false  ? 'active open' : ''}}"><a href="{{url('jenis-audit')}}"><span class="menu-text">Jenis Audit</span></a></li>
                  <li class="{{strpos($url,'jangka-waktu')!==false  ? 'active open' : ''}}"><a href="{{url('jangka-waktu')}}"><span class="menu-text">Jangka Waktu Penyelesaian</span></a></li>
                  <li class="{{strpos($url,'status-rekomendasi')!==false  ? 'active open' : ''}}"><a href="{{url('status-rekomendasi')}}"><span class="menu-text">Status Rekomendasi</span></a></li>
                  <li class="{{strpos($url,'jenis-temuan')!==false || $url=='data-temuan'  ? 'active open' : ''}}"><a href="{{url('jenis-temuan')}}"><span class="menu-text">Jenis Temuan</span></a></li>
                  <li class="{{strpos($url,'rekanan')!==false ? 'active open' : ''}}"><a href="{{url('rekanan')}}"><span class="menu-text">Rekanan</span></a></li>
                  <li class="{{strpos($url,'level-resiko')!==false ? 'active open' : ''}}"><a href="{{url('level-resiko')}}"><span class="menu-text">Level Resiko</span></a></li>
                  <li class="{{strpos($url,'pejabat-penandatangan')!==false ? 'active open' : ''}}"><a href="{{url('pejabat-penandatangan')}}"><span class="menu-text">Pejabat Penanda Tangan</span></a></li>
                  
                  <li class="{{strpos($url,'data-lhp')!==false ? 'active open' : ''}}"><a href="{{url('data-lhp')}}"><span class="menu-text">Data LHP</span></a></li> 
                  {{-- <li class="{{$url=='level-pengguna' ? 'active open' : ''}}"><a href="{{url('level-pengguna')}}"><span class="menu-text">Data Level Pengguna</span></a></li>  --}}
                  <li class="{{strpos($url,'pengguna')!==false ? 'active open' : ''}}"><a href="{{url('pengguna')}}"><span class="menu-text">Data Pengguna</span></a></li> 
                  <li class="{{strpos($url,'periode-review')!==false ? 'active open' : ''}}"><a href="{{url('periode-review')}}"><span class="menu-text">Periode Review</span></a></li> 
              @endif
              @if (Auth::user()->level=='super-user')
                  <li class="{{strpos($url,'level-pic')!==false  ? 'active open' : ''}}"><a href="{{url('level-pic')}}"><span class="menu-text">Level PIC Unit</span></a></li>
                  <li class="{{strpos($url,'pic-unit')!==false  ? 'active open' : ''}}"><a href="{{url('pic-unit')}}"><span class="menu-text">PIC Unit</span></a></li>
                  <li class="{{strpos($url,'pemeriksa')!==false  ? 'active open' : ''}}"><a href="{{url('pemeriksa')}}"><span class="menu-text">Pemeriksa</span></a></li>
                  <li class="{{strpos($url,'jenis-audit')!==false  ? 'active open' : ''}}"><a href="{{url('jenis-audit')}}"><span class="menu-text">Jenis Audit</span></a></li>
                  <li class="{{strpos($url,'jangka-waktu')!==false  ? 'active open' : ''}}"><a href="{{url('jangka-waktu')}}"><span class="menu-text">Jangka Waktu Penyelesaian</span></a></li>
                  <li class="{{strpos($url,'status-rekomendasi')!==false  ? 'active open' : ''}}"><a href="{{url('status-rekomendasi')}}"><span class="menu-text">Status Rekomendasi</span></a></li>
                  <li class="{{strpos($url,'jenis-temuan')!==false || $url=='data-temuan'  ? 'active open' : ''}}"><a href="{{url('jenis-temuan')}}"><span class="menu-text">Jenis Temuan</span></a></li>
                  <li class="{{strpos($url,'rekanan')!==false ? 'active open' : ''}}"><a href="{{url('rekanan')}}"><span class="menu-text">Rekanan</span></a></li>
                  <li class="{{strpos($url,'level-resiko')!==false ? 'active open' : ''}}"><a href="{{url('level-resiko')}}"><span class="menu-text">Level Resiko</span></a></li>
                  <li class="{{strpos($url,'pejabat-penandatangan')!==false ? 'active open' : ''}}"><a href="{{url('pejabat-penandatangan')}}"><span class="menu-text">Pejabat Penanda Tangan</span></a></li>
                  <li class="{{strpos($url,'periode-review')!==false ? 'active open' : ''}}"><a href="{{url('periode-review')}}"><span class="menu-text">Periode Review</span></a></li> 
              @endif
            </ul>
          </li>
        @endif
        @if (Auth::user()->level=='auditor-junior' || Auth::user()->level=='pic-unit')
          <li class="{{$url=='data-lhp' ? 'active' : ''}}">
            <a href="{{url('data-lhp')}}">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text">Data LHP</span>
            </a>
          </li>
          <li class="{{$url=='data-tindak-lanjut' ? 'active' : ''}}">
            @if (Auth::user()->level=='pic-unit')
              <a href="{{url('data-tindaklanjut-unitkerja')}}">
                <i class="menu-icon fa fa-list"></i>
                <span class="menu-text">Data Tindak Lanjut</span>
              </a>
            @else
              <a href="{{url('data-tindaklanjut')}}">
                <i class="menu-icon fa fa-list"></i>
                <span class="menu-text">Data Tindak Lanjut</span>
              </a>
            @endif
          </li>
        @endif
        @if (Auth::user()->level=='auditor-senior')
         <li class="{{$url=='data-lhp' ? 'active' : ''}}">
            <a href="{{url('data-lhp')}}">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text">Data LHP</span>
            </a>
          </li>
          <li class="{{$url=='data-tindaklanjut-senior' ? 'active' : ''}}">
            <a href="{{url('data-tindaklanjut-senior')}}">
                <i class="menu-icon fa fa-list"></i>
                <span class="menu-text">Data Tindak Lanjut</span>
              </a>
          </li>
          {{-- <li class="has-submenu {{strpos($url,'data-lhp')!==false ? 'active open' : ''}}">
            <a href="javascript:void(0)" class="submenu-toggle">
              <i class="menu-icon fa fa-archive"></i>
              <span class="menu-text">Master Data LHP</span>
              <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i>
            </a>
            <ul class="submenu" style="{{strpos($url,'data-lhp')!==false || strpos($url,'semua-data-lhp')!==false ? 'display:block' : ''}}">
              <li class="{{strpos($url,'semua-data-lhp')!==false ? 'active open' : ''}}"><a href="{{url('semua-data-lhp')}}/{{ date('Y') }}"><span class="menu-text">Semua Data LHP</span></a></li>
              <li class="{{strpos($url,'data-lhp')!==false ? 'active open' : ''}}"><a href="{{url('data-lhp')}}/{{ date('Y') }}"><span class="menu-text">Data LHP</span></a></li>
            </ul>
          </li> --}}
        @endif
        @if (Auth::user()->level=='super-user')
         <li class="{{$url=='data-lhp' ? 'active' : ''}}">
            <a href="{{url('data-lhp')}}">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text">Data LHP</span>
            </a>
          </li>
          <li class="{{$url=='data-tindaklanjut-su' ? 'active' : ''}}">
            <a href="{{url('data-tindaklanjut-su')}}">
                <i class="menu-icon fa fa-list"></i>
                <span class="menu-text">Data Tindak Lanjut</span>
              </a>
          </li>
          @endif
        @if (Auth::user()->level!='pic-unit')
        <li class="has-submenu {{strpos($url,'laporan')!==false ? 'active open' : ''}}">
          <a href="javascript:void(0)" class="submenu-toggle">
            <i class="menu-icon fa fa-archive"></i>
            <span class="menu-text">Laporan</span>
            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i>
          </a>
          @php
              $menulaporan=[
                'temuan-per-bidang',
                'temuan-per-unitkerja',
                'temuan-per-lhp',
                'tindaklanjut-per-lhp',
                'tindaklanjut-per-bidang',
                'tindaklanjut-per-unitkerja',
                'tindak-lanjut',
                'status-penyelesaian-rekomendasi',
                'status-penyelesaian-rekomendasi-pemeriksa',
                'rekap-lhp',
                'rekap-status-rekomendasi',
                'rekap-status-rekomendasi-bidang',
                'rekap-status-rekomendasi-unitkerja',
                'rekap-jumlah-resiko-periode',
                'rekap-rekomendasi',
                'rekap-jumlah-resiko-bidang',
                'rekap-perhitungan-tekn-pertanggal',
                'rekap-perhitungan-tekn-status'
              ];
          @endphp
          <ul class="submenu" style="{{in_array(str_replace('laporan/','',$url),$menulaporan) ? 'display:block' : ''}}">
            <li class="{{strpos($url,'temuan-per-bidang')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/temuan-per-bidang')}}"><span class="menu-text">Temuan Per Bidang</span></a>
            </li>
            <li class="{{strpos($url,'temuan-per-unitkerja')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/temuan-per-unitkerja')}}"><span class="menu-text">Temuan Per Unit Kerja</span></a>
            </li>
            <li class="{{strpos($url,'temuan-per-lhp')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/temuan-per-lhp')}}"><span class="menu-text">Temuan Per LHP</span></a>
            </li>
            <li class="{{strpos($url,'tindaklanjut-per-lhp')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/tindaklanjut-per-lhp')}}"><span class="menu-text">Tindak Lanjut Per-LHP</span></a>
            </li>
            <li class="{{strpos($url,'tindaklanjut-per-bidang')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/tindaklanjut-per-bidang')}}"><span class="menu-text">Tindak Lanjut Per-Bidang</span></a>
            </li>
            <li class="{{strpos($url,'tindaklanjut-per-unitkerja')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/tindaklanjut-per-unitkerja')}}"><span class="menu-text">Tindak Lanjut Per-Unit Kerja</span></a>
            </li>
            <li class="{{strpos($url,'tindak-lanjut')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/tindak-lanjut')}}"><span class="menu-text">Matriks Pemantauan Tindak Lanjut</span></a>
            </li>
            <li class="{{$url=='laporan/status-penyelesaian-rekomendasi' ? 'active' : ''}}">
              <a href="{{url('laporan/status-penyelesaian-rekomendasi')}}"><span class="menu-text">Status Penyelesaian Rekomendasi</span></a>
            </li>
            <li class="{{$url=='laporan/status-penyelesaian-rekomendasi-pemeriksa' ? 'active' : ''}}">
              <a href="{{url('laporan/status-penyelesaian-rekomendasi-pemeriksa')}}"><span class="menu-text">Status Penyelesaian Rekomendasi Pemeriksa</span></a>
            </li>
            <li class="{{strpos($url,'rekap-lhp')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/rekap-lhp')}}"><span class="menu-text">Rekap LHP</span></a>
            </li>
            <li class="{{strpos($url,'rekap-status-rekomendasi')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/rekap-status-rekomendasi')}}"><span class="menu-text">Rekap Status Rekomendasi Semua Pemeriksa</span></a>
            </li>
            <li class="{{strpos($url,'rekap-status-rekomendasi-bidang')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/rekap-status-rekomendasi-bidang')}}"><span class="menu-text">Rekap Status Rekomendasi Per Bidang</span></a>
            </li>
            <li class="{{strpos($url,'rekap-status-rekomendasi-unitkerja')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/rekap-status-rekomendasi-unitkerja')}}"><span class="menu-text">Rekap Status Rekomendasi Per Unit Kerja</span></a>
            </li>
            <li class="{{strpos($url,'rekap-jumlah-resiko-periode')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/rekap-jumlah-resiko-periode')}}"><span class="menu-text">Rekap Jumlah Resiko Per Periode</span></a>
            </li>
            <li class="{{strpos($url,'rekap-rekomendasi')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/rekap-rekomendasi')}}"><span class="menu-text">Rekap Rekomendasi</span></a>
            </li>
            <li class="{{strpos($url,'rekap-jumlah-resiko-bidang')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/rekap-jumlah-resiko-bidang')}}"><span class="menu-text">Rekap Jumlah Resiko Per Bidang</span></a>
            </li>
            <li class="{{strpos($url,'rekap-perhitungan-tekn-pertanggal')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/rekap-perhitungan-tekn-pertanggal')}}"><span class="menu-text">Rekap Perhitungan TEKN Per Tanggal</span></a>
            </li>
            <li class="{{strpos($url,'rekap-perhitungan-tekn-status')!==false ? 'active' : ''}}">
              <a href="{{url('laporan/rekap-perhitungan-tekn-status')}}"><span class="menu-text">Rekap Perhitungan TEKN Per Status</span></a>
            </li>

          </ul>
        </li>
        @endif
        {{-- <li class="menu-separator"><hr></li>

        <li>
          <a href="{{url('logout')}}">
            <i class="menu-icon fa fa-sign-out"></i>
            <span class="menu-text">Logout</span>
          </a>
        </li> --}}
      </ul><!-- .app-menu -->
    </div><!-- .menubar-scroll-inner -->
  </div><!-- .menubar-scroll -->
</aside>