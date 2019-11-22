@extends('backend.layouts.master')

@section('title')
    <title>Data Temuan</title>
@endsection

@section('content')

	<div class="col-md-12">
        <div class="widget">
			<header class="widget-header">
				<h4 class="widget-title">Data LHP</h4>
			</header><!-- .widget-header -->
			<hr class="widget-separator">
			<div class="widget-body row">
				<div class="col-xs-4">
                    <div class="text-center p-h-md" style="border-right: 2px solid #eee">
                        <h6>Nomor LHP</h4>
						<h4 class=" fw-400 m-0"><strong>{{$data->no_lhp}}</strong></h4>
					</div>
				</div><!-- END column -->
				<div class="col-xs-4">
                    <div class="text-center p-h-md" style="border-right: 2px solid #eee">
                        <h6>Kode LHP</h4>
						<h4 class=" fw-400 m-0"><strong>{{$data->kode_lhp}}</strong></h4>
					</div>
				</div><!-- END column -->
				<div class="col-xs-4">
					<div class="text-center p-h-md">
                        <h6>Judul LHP</h4>
						<h4 class=" fw-400 m-0"><strong>{{$data->judul_lhp}}</strong></h4>
					</div>
				</div><!-- END column -->
			</div><!-- .widget-body -->
		</div>
		<div class="widget">
			<header class="widget-header" style="padding-bottom:10px;margin-bottom:0px;">
				
                
                {{-- @if (!Auth::user()->level==1 || Auth::user()->level==2) --}}
                    <div class="row" style="margin-bottom:0px;padding-bottom:0px;">
                        
                       
                        <div class="col-md-2 text-left">
                             <h3 class="widget-title">Data Temuan</h3>
                        </div>
                        <div class="col-md-8">&nbsp;</div>
                         <div class="col-md-2 text-right">
                            <a href="" class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modaltambah">+ Tambah Data</a>
                        </div>
                    </div>
                    
                {{-- @endif --}}
            </header>
            
            <hr class="widget-separator">
			<div class="widget-body">
                <div class="">
                   
                    
                    <div class="row" style="margin-top:10px;font-size:20px;">
                        <div class="col-md-12">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    <strong>Alert ! </strong>
                                    <span>
                                        <ul>
                                        @foreach ($errors->all() as $item)
                                            <li style="font-size:11px !important;">- {!!$item!!}</li>
                                        @endforeach
                                        </ul>
                                        
                                    </span>
                                </div>	
                            @endif
                            @if (Session::has('success'))
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    <strong>Berhasil! </strong>
                                    <span style="font-size:11px !important;">{!!Session::get('success')!!}</span>
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-bordered" id="datatable-temuan" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center primary" style="width:15px;">#</th>
                                            <th class="text-center primary">Nomor Temuan</th>
                                            <th class="text-center primary">Temuan</th>
                                            <th class="text-center primary">Jenis Temuan</th>
                                            <th class="text-center primary">PIC Temuan</th>
                                            <th class="text-center primary">Nilai Temuan </th>
                                            <th class="text-center primary">Level Resiko</th>
                                            <th class="text-center primary">Rekomendasi</th>
                                            <th class="text-center primary">Aksi</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($temuan as $ky=>$item)
                                            <tr class="info text-dark">
                                                <td class="text-center">{{$ky+1}}</td>
                                                <td class="text-center">{{$item->no_temuan}}</td>
                                                <td class="text-left">{!!$item->temuan!!}</td>
                                                <td class="text-center">{{isset($item->jenistemuan->temuan) ? $item->jenistemuan->temuan: '-'}}</td>
                                                <td class="text-center">{{isset($item->picunit->nama_pic) ? $item->picunit->nama_pic: '-'}}</td>
                                                <td class="text-right">{{number_format($item->nominal,2,',','.')}}</td>
                                                <td class="text-center">{{isset($item->levelresiko->level_resiko) ? $item->levelresiko->level_resiko: '-'}}</td>
                                                <td class="text-center rekomendasi-detail" data-value="{{ $item->id }}">
                                                    <div style="width:150px;">
                                                        @php
                                                            $jlhrekom=isset($rekomendasi[$item->temuan_id]) ? count($rekomendasi[$item->temuan_id]) : 0;
                                                        @endphp
                                                        <span style="cursor:pointer" class="label label-{{$jlhrekom==0 ? 'dark' : 'primary'}} fz-sm">{{$jlhrekom}}</span>
                                                        <span style="cursor:pointer" class="label label-success fz-sm">Rekomendasi</span>
                                                        <span style="cursor:pointer" class="label label-info fz-sm" data-toggle="modal" data-target="#modaltambahrekomendasi">
                                                            <a data-toggle="tooltip" title="Tambah Rekomendasi" style="color:#fff" data-value="{{ $item->temuan_id }}" onclick="rekomadd('{{$item->temuan_id}}')"><i class="fa fa-plus-circle"></i></a>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-align:center">
                                                    <div style="width:70px;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-bars"></i></button>
                                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="#"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Temuan & Rekomendasi</a></li>
                                                                <li style="cursor:pointer"><a class="btn-edit" data-toggle="modal" data-target="#modalubah" data-value="{{ $item->temuan_id }}"><i class="glyphicon glyphicon-edit"></i> &nbsp;&nbsp;Edit LHP</a></li>
                                                                <li><a class="btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="{{ $item->id }}"><i class="glyphicon glyphicon-trash"></i> &nbsp;&nbsp;Hapus Temuan</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footscript')
    <link rel="stylesheet" href="{{asset('theme/backend/libs/misc/datatables/datatables.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/noty.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/tooltips.css')}}"/>
    <script src="{{asset('theme/backend/libs/misc/datatables/datatables.min.js')}}"></script>
    {{-- <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script> --}}
    
    <script src="{{asset('js/noty.js')}}"></script>
    <script src="{{asset('js')}}/numeral.min.js"></script>
    <script src="{{asset('js')}}/js.js"></script>
    <script src="{{asset('js')}}/temuan.js"></script>
    <script src="{{asset('js')}}/jquery-ui.js"></script>
	<script>
        hidealert();
        
    </script>
    <style>
    /* .form-inline .btn
    {
        height:24px !important;
    } */
    .select2-container{
		width:100% !important;
	}
    .dataTables_info{
        font-size:11px !important;
    }
    .paginate_button a{
        font-size:11px !important;
    }
    .btn-group .dropdown-menu
    {
        right:0 !important;
        left:unset !important;
        font-size:11px !important;
        /* z-index: 1000000000000 !important;
        position: relative !important; */
    }
    .table-responsive
    {
        overflow-x: unset !important;
    }
    .form-inline .btn
    {
        height:unset !important;
    }
    </style>
@endsection

@section('modal')
    <div class="modal fade" id="modaltambahrekomendasi" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:60% !important">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="form_rekom_add">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Tambah Data Rekomendasi </h4>
                    </div>
                    <div class="modal-body">
                        @php
                            $dt['act']='add';
                        @endphp
                        <input type="hidden" name="id_lhp" value="{{$idlhp}}">
                        @include('backend.pages.data-lhp.auditor-junior.rekomendasi-form',$dt)
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasirekom('add')" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
    </div>  
    <div class="modal fade" id="modalubahrekomendasi" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:60% !important">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="form_rekom_edit">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Ubah Data Rekomendasi </h4>
                    </div>
                    <div class="modal-body">
                        @php
                            $dt['act']='edit';
                        @endphp
                        <input type="hidden" name="id_lhp" value="{{$idlhp}}">
                        @include('backend.pages.data-lhp.auditor-junior.rekomendasi-form',$dt)
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasirekom('edit')" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
    </div>  
    <div class="modal fade" id="modaltambah" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form action="{{ url('data-temuan-lhp-simpan/'.$idlhp) }}" method="POST" class="form-horizontal" id="formadd">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Tambah Data Temuan </h4>
                    </div>
                    <div class="modal-body">
                        @php
                            $dt['act']='add';
                        @endphp
                        @include('backend.pages.data-lhp.auditor-junior.temuan-form',$dt)
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasiadd('add')" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
    <div class="modal fade" id="modalubah" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form action="{{ url('data-temuan-lhp-update/'.$idlhp) }}" method="POST" class="form-horizontal" id="formedit">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Ubah Data Temuan </h4>
                    </div>
                    <div class="modal-body">
                        @php
                            $dt['act']='edit';
                        @endphp
                        <input type="hidden" id="temuan_id" name="temuan_id">
                        @include('backend.pages.data-lhp.auditor-junior.temuan-form',$dt)
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasiadd('edit')" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
    <div class="modal fade" id="modalhapus" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Konfirmasi Hapus Data Temuan</h4>
				</div>
				<div class="modal-body">
					<h5>Apakah anda yakin akan menghapus data temuan ini?</h5>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
					<a class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('form-delete').submit();" style="cursor:pointer;">Ya, Saya Yakin</a>
					<form id="form-delete" method="POST" style="display: none;">
						@csrf
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection