@extends('backend.layouts.master')

@section('title')
    <title>Daftar Temuan</title>
@endsection
@section('modal')
    <div class="modal fade" id="modalhapus" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Konfirmasi Hapus Detail Temuan</h4>
				</div>
				<div class="modal-body">
					<h5>Apakah anda yakin akan menghapus data ini?</h5>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
					<a class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('form-delete').submit();" style="cursor:pointer;">Ya, Saya Yakin</a>
                    <form id="form-delete" method="POST" style="display: none;" action="{{url('detail-temuan-delete')}}">
                        @csrf
                        <input type="hidden" name="id" id="iddetail">
					</form>
				</div>
			</div>
		</div>
	</div>
    <div class="modal fade" id="modalverifikasi" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Verifikasi Detail Temuan</h4>
				</div>
				<div class="modal-body">
					<h5>Apakah anda yakin akan me-verifikasi data ini?</h5>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
					<a class="btn btn-info" onclick="event.preventDefault(); document.getElementById('form-verifikasi').submit();" style="cursor:pointer;">Ya, Saya Yakin</a>
                    <form id="form-verifikasi" method="POST" style="display: none;" action="{{url('detail-temuan-verifikasi')}}">
                        @csrf
                        <input type="hidden" name="id" id="iddetailverif">
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('content')
@php
    $dinas_id=$pengawasan_id='-1';
    if(Session::has('dinas_id'))
    {
        $dinas_id=Session::get('dinas_id');
    }                                    
    if(Session::has('tahun'))
    {
        $tahun=Session::get('tahun');
        
    }                                    
    if(Session::has('pengawasan_id'))
    {
        $pengawasan_id=Session::get('pengawasan_id');
    }                                    
@endphp
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
                <span class="widget-title"><a href="{{url('temuan')}}">Daftar Temuan</a></span>
                >>
                <span class="widget-title">Nomor LHP : {{$temuan->no_pengawasan}}</span>
                
                <a href="{{url('temuan')}}" class="btn btn-sm btn-info pull-right"><i class="fa fa-chevron-left"></i>&nbsp;Kembali</a>
			</header>
            
            <hr class="widget-separator">
			<div class="widget-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row" style="padding-bottom:10px;">
                            <div class="col-lg-3">Nomor LHP</div>
                            <div class="col-lg-9">: <b>{{$temuan->no_pengawasan}}</b></div>
                        </div>
                        <div class="row" style="padding-bottom:10px;">
                            <div class="col-lg-3">Tanggal</div>
                            <div class="col-lg-9">: <b>{{date('d/m/Y',strtotime($temuan->tgl_pengawasan))}}</b></div>
                        </div>
                        <div class="row" style="padding-bottom:10px;">
                            <div class="col-lg-3">Dinas</div>
                            <div class="col-lg-9">: <b>{{$temuan->dinas->nama_dinas}}</b></div>
                        </div>
                        <div class="row" style="padding-bottom:10px;">
                            <div class="col-lg-3">Tahun</div>
                            <div class="col-lg-9">: <b>{{$temuan->tahun}}</b></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        @if (Auth::user()->level!=1 && Auth::user()->level!=3)
                            <a href="{{url('detail-form/'.(isset($temuan->id) ? $temuan->id : '-1').'/'.$temuan->dinas_id.'/'.$temuan->tahun.'/'.$temuan->pengawasan_id)}}" class="btn btn-success btn-xs pull-right">+ Tambah Detail</a>
                        @endif
                    </div>
                </div>
                <hr class="widget-separator">
				<div class="">
                    
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-12">       
                            <table id="table" class="table table-striped table-bordered" width="100%" style="padding:10px 0px;x-overflow:hidden">
                                <thead>
                                    <tr>
                                        <th class="text-center"  style="width:15px;">#</th>
                                        <th class="text-center" >Temuan / Penyebab<br>(Uraian Ringkas)</th>
                                        <th class="text-center" >Kode Temuan</th>
                                        <th class="text-center" >Rekomendasi<br>(Uraian Ringkas)</th>
                                        <th class="text-center" >Kode Rekomendasi</th>
                                        <th class="text-center">Detail</th>
                                        {{-- <th class="text-center" >Harus Selesai<br>Tanggal</th> --}}
                                        @if (Auth::user()->level!=3)
                                            <th class="text-center" >Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $keydaftar=0;
                                    @endphp
                                    @if (isset($det[$temuan->id]))
                                    
                                        @foreach ($det[$temuan->id] as $key=>$us)
                                            @if (Auth::user()->level==3)
                                                @if ($us->flag!=0)
                                                    <tr>
                                                        <td>{{ $keydaftar = $keydaftar + 1 }}</td>
                                                        <td>
                                                            {!! $us->uraian_temuan !!}
                                                        </td>
                                                        <td class="text-center">{{$us->temuan->code}}</td>
                                                        <td>{!! $us->uraian_rekomendasi !!}</td>
                                                        <td class="text-center">{{$us->rekomendasi->code}}</td>
                                                        <td class="text-left">
                                                                
                                                            {{-- * Status Temuan:
                                                            * 0 : Menunggu Verifikasi
                                                            * 2 : Belum Tindak Lanjut
                                                            * 3 : Telah Tindak Lanjut
                                                            * 4 : Selesai
                                                                --}}

                                                            @if (Auth::user()->level==1)
                                                                @if ($us->flag==0)
                                                                    <span class="text-danger">Menunggu Verifikasi</span>
                                                                @elseif ($us->flag==2)
                                                                    <span>Telah Diverifikasi</span><br>
                                                                    <span class="text-danger"><i>(Belum Ditindaklanjuti)</i></span>
                                                                @elseif ($us->flag==3)
                                                                    <a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}" style="color:green;">Telah Ditindaklanjuti</a>
                                                                @else
                                                                    <span class="text-success">Proses Selesai</span> <br>
                                                                    <a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}"><i>(Lihat Detail)</i></a>
                                                                @endif
                                                            @elseif(Auth::user()->level==2)
                                                                @if ($us->flag==0)
                                                                    <span class="text-danger">Menunggu Verifikasi</span>
                                                                @elseif ($us->flag==2)
                                                                    <span>Telah Diverifikasi</span><br>
                                                                    <span class="text-danger"><i>(Belum Ditindaklanjuti)</i></span>
                                                                @elseif ($us->flag==3)
                                                                    <a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}" style="color:green;">Telah Ditindaklanjuti</a>
                                                                @else
                                                                    <span class="text-success">Proses Selesai</span> <br>
                                                                    <a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}"><i>(Lihat Detail)</i></a>
                                                                @endif
                                                            @elseif(Auth::user()->level==3)
                                                                @if ($us->flag==0)
                                                                    <span>Menunggu Verifikasi</span>
                                                                @elseif ($us->flag==2)
                                                                    <a href="{{ route('tindak-lanjut.index', $us->id) }}" style="color:red;">Belum ada tindak lanjut</a>
                                                                @elseif ($us->flag==3)
                                                                    <span class="text-green">Telah Ditindaklanjuti</span> <br>
                                                                    <a href="{{ route('tindak-lanjut.edit', $us->id) }}"><i>(Ubah Data)</i></a>
                                                                @else
                                                                    <span class="text-success">Proses Selesai</span> <br>
                                                                    <a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}"><i>(Lihat Detail)</i></a>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        
                                                        @if (Auth::user()->level!=3)
                                                        <td class="text-center">
                                                            <div style="width:80px">
                                                                @if (Auth::user()->level==2)
                                                                    <a class="btn btn-xs btn-info btn-edit" href="{{url('list-temuan/'.$us->id.'/edit')}}" style="height:24px !important;">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                @endif

                                                                @if (Auth::user()->level==1)
                                                                    @if ($us->flag==0)
                                                                        <a href="javascript:verifikasi({{$us->id}})" class="btn btn-xs btn-info"><i class="fa fa-check"></i></a>
                                                                    @else
                                                                        <a href="#" class="btn btn-xs btn-default" disabled><i class="fa fa-check"></i></a>
                                                                    @endif
                                                                @endif
                                                                
                                                                <a href="javascript:hapusdetail({{$us->id}})" class="btn btn-xs btn-danger btn-delete" style="height:24px !important;">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                                
                                                            </div>
                                                        </td>
                                                        @endif
                                                    </tr>
                                                @endif
                                            @else
                                                <tr>
                                                    <td>{{ $keydaftar = $keydaftar + 1 }}</td>
                                                    
                                                    <td>
                                                        {!! $us->uraian_temuan !!}
                                                    </td>
                                                    <td class="text-center">{{$us->temuan->code}}</td>
                                                    <td>{!! $us->uraian_rekomendasi !!}</td>
                                                    <td class="text-center">{{$us->rekomendasi->code}}</td>
                                                    <td class="text-left">
                                                            
                                                        {{-- * Status Temuan:
                                                        * 0 : Menunggu Verifikasi
                                                        * 2 : Belum Tindak Lanjut
                                                        * 3 : Telah Tindak Lanjut
                                                        * 4 : Selesai
                                                            --}}

                                                        @if (Auth::user()->level==1)
                                                            @if ($us->flag==0)
                                                                <span class="text-danger">Menunggu Verifikasi</span>
                                                            @elseif ($us->flag==2)
                                                                <span>Telah Diverifikasi</span><br>
                                                                <span class="text-danger"><i>(Belum Ditindaklanjuti)</i></span>
                                                            @elseif ($us->flag==3)
                                                                <a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}" style="color:green;">Telah Ditindaklanjuti</a>
                                                            @else
                                                                <span class="text-success">Proses Selesai</span> <br>
                                                                <a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}"><i>(Lihat Detail)</i></a>
                                                            @endif
                                                        @elseif(Auth::user()->level==2)
                                                            @if ($us->flag==0)
                                                                <span class="text-danger">Menunggu Verifikasi</span>
                                                            @elseif ($us->flag==2)
                                                                <span>Telah Diverifikasi</span><br>
                                                                <span class="text-danger"><i>(Belum Ditindaklanjuti)</i></span>
                                                            @elseif ($us->flag==3)
                                                                <a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}" style="color:green;">Telah Ditindaklanjuti</a>
                                                            @else
                                                                <span class="text-success">Proses Selesai</span> <br>
                                                                <a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}"><i>(Lihat Detail)</i></a>
                                                            @endif
                                                        @elseif(Auth::user()->level==3)
                                                            @if ($us->flag==0)
                                                                <span>Menunggu Verifikasi</span>
                                                            @elseif ($us->flag==2)
                                                                <a href="{{ route('tindak-lanjut.index', $us->id) }}" style="color:red;">Belum ada tindak lanjut</a>
                                                            @elseif ($us->flag==3)
                                                                <span class="text-green">Telah Ditindaklanjuti</span> <br>
                                                                <a href="{{ route('tindak-lanjut.edit', $us->id) }}"><i>(Ubah Data)</i></a>
                                                            @else
                                                                <span class="text-success">Proses Selesai</span> <br>
                                                                <a href="{{ route('tindak-lanjut.show', $us->tindak_lanjut_temuan->id) }}"><i>(Lihat Detail)</i></a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    
                                                    @if (Auth::user()->level!=3)
                                                    <td class="text-center">
                                                        <div style="width:80px">
                                                            @if (Auth::user()->level==2)
                                                                @if ($us->flag==0)
                                                                    <a class="btn btn-xs btn-info btn-edit" href="{{url('list-temuan/'.$us->id.'/edit')}}" style="height:24px !important;">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>

                                                                    <a href="javascript:hapusdetail({{$us->id}})" class="btn btn-xs btn-danger btn-delete" style="height:24px !important;">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                @else
                                                                    <a class="btn btn-xs btn-default" disabled style="height:24px !important;">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>

                                                                    <a class="btn btn-xs btn-default" disabled style="height:24px !important;">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                @endif
                                                            @endif

                                                            @if (Auth::user()->level==1)
                                                                @if ($us->flag==0)
                                                                    <a href="javascript:verifikasi({{$us->id}})" class="btn btn-xs btn-info"><i class="fa fa-check"></i></a>
                                                                @else
                                                                    <a href="#" class="btn btn-xs btn-default" disabled><i class="fa fa-check"></i></a>
                                                                @endif

                                                                <a href="javascript:hapusdetail({{$us->id}})" class="btn btn-xs btn-danger btn-delete" style="height:24px !important;">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            @endif	
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <th class="text-center" colspan="7">Data Tidak Tersedia</th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footscript')
    <link rel="stylesheet" href="{{asset('theme/backend/libs/misc/datatables/datatables.min.css')}}"/>
    <script src="{{asset('theme/backend/libs/misc/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('theme/backend/libs/misc/flot/jquery.flot.tooltip.min.js')}}"></script>
	<script>
       
        function hapusdetail(id)
        {
            $('#iddetail').val(id);
            $('#modalhapus').modal('show');
        }
        function verifikasi(id)
        {
            $('#iddetailverif').val(id);
            $('#modalverifikasi').modal('show');
        }
    </script>
    <style>
    .form-inline .btn
    {
        height:24px !important;
    }
    </style>
@endsection
