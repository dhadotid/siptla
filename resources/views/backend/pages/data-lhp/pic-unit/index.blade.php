@extends('backend.layouts.master')

@section('title')
    <title>Daftar LHP</title>
@endsection

@section('content')

	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				
                
                {{-- @if (!Auth::user()->level==1 || Auth::user()->level==2) --}}
                    <div class="row">
                        <div class="col-md-2 text-left">Tahun
                            <select name="tahun" id="tahun" class="form-control text-left" data-plugin="select2" onchange="getdata(this.value)" style="width:50%">
                                @for ($i = date('Y'); $i >= (date('Y')-20); $i--)
                                    @if ($tahun==$i)
                                        <option value="{{$i}}" selected="selected"}}>{{$i}}</option>
                                    @else
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-8">&nbsp;</div>
                         <div class="col-md-2 text-right">
                            {{-- <a href="" class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modaltambah">+ Tambah Data</a> --}}
                        </div>
                    </div>
                    
                {{-- @endif --}}
            </header>
          
            <hr class="widget-separator">
			<div class="widget-body">
                <div class="">
                    <span class="widget-title">Data LHP</span>
                    
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
                            @if (Session::has('error'))
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    <strong>Peringatan! </strong>
                                    <span style="font-size:11px !important;">{!!Session::get('error')!!}</span>
                                </div>
                            @endif
                            <div id="data">
                                <div class="text-center"><h4>Silahkan Pilih Tahun Terlebih Dahulu</h4></div>
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
    <link rel="stylesheet" href="{{asset('theme/backend/libs/bower/summernote/dist/summernote.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/noty.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/tooltips.css')}}"/>
    <script src="{{asset('theme/backend/libs/misc/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('theme/backend/libs/bower/summernote/dist/summernote.min.js')}}"></script>
    <script src="{{asset('js/noty.js')}}"></script>
    <script src="{{asset('js/lhp.js')}}"></script>
	<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        loaddata('{{$tahun}}','{{$statusrekom}}','{{$key}}','{{$priority}}');
        hidealert();
        $('.select').select2();
        
        
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
    
    </style>
@endsection
@section('modal')
{{-- Start Modal CRUD LHP --}}
    <div class="modal fade" id="modaltambah" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
                <form action="{{ route('data-lhp.store') }}" method="POST" class="form-horizontal" id="formadd">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Tambah Data LHP </h4>
                    </div>
                    <div class="modal-body">
                        @include('backend.pages.data-lhp.auditor-junior.add-lhp',$data)
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasiadd()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
    <div class="modal fade" id="modalubah" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="formubah">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Ubah Data LHP </h4>
                    </div>
                    <div class="modal-body">
                        @include('backend.pages.data-lhp.auditor-junior.edit-lhp',$data)
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasiedit()" class="btn btn-success" value="Simpan">
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
					<h4 class="modal-title">Konfirmasi Hapus Data LHP</h4>
				</div>
				<div class="modal-body">
					<h5>Apakah anda yakin akan menghapus data LHP ini?</h5>
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
{{-- End Modal CRUD LHP --}}

{{-- Start  Modal Detail LHP --}}
    <div class="modal fade" id="modaldetail" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:95% !important;margin-top:10px !important;">
			<div class="modal-content" style="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Detail Data LHP </h4>
                    </div>
                    <div class="modal-body">
                        <div id="detail"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" data-dismiss="modal" class="btn btn-primary">Tutup</button>
                    </div>
			</div>
		</div>
    </div>  
{{-- End  Modal Detail LHP --}}

{{-- Start  Modal Review LHP --}}
    <div class="modal fade" id="modalreview" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="margin-top:10px !important;width:50%;">
			<div class="modal-content" style="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Review Data LHP </h4>
                    </div>
                    <div class="modal-body">
                        <div id="review"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" data-dismiss="modal" class="btn btn-primary">Tutup</button>
                    </div>
			</div>
		</div>
    </div>  
    <div class="modal fade" id="modaladdreview" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="margin-top:10px !important;width:50%;">
			<div class="modal-content" style="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Tambah Review LHP </h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-review"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" data-dismiss="modal" class="btn btn-default">Tutup</button>
                        <input type="button" onclick="validasireview()" class="btn btn-success" value="Simpan">
                    </div>
			</div>
		</div>
    </div>  
    <div class="modal fade" id="modal-detail-rekom" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="margin-top:10px !important;width:50%;">
			<div class="modal-content" style="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Detail Rincian Nilai</h4>
                    </div>
                    <div class="modal-body">
                        <div id="detail-rekom"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" data-dismiss="modal" class="btn btn-primary">Tutup</button>
                    </div>
			</div>
		</div>
    </div>  
{{-- End  Modal Review LHP --}}
@endsection