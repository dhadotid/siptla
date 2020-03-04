
@extends('backend.layouts.master')

@section('title')
    <title>Data Tindak Lanjut</title>
@endsection

@section('content')

	<div class="col-md-12">
        <div class="widget">
			<header class="widget-header">
				<h4 class="widget-title">Detail Data Temuan</h4>
			</header><!-- .widget-header -->
			<hr class="widget-separator">
			<div class="widget-body row">
				<div class="col-xs-4">
                    <div class="text-center p-h-md" style="border-right: 2px solid #eee">
                        <h6>LHP</h4>
						<h5 class="text-center fw-400 m-0">Nomor : <strong>{{$data->no_lhp}}</strong></h5>
					</div>
				</div><!-- END column -->
				<div class="col-xs-4">
                    <div class="text-center p-h-md" style="border-right: 2px solid #eee">
                        <h6>Kode LHP</h4>
						<h5 class=" fw-400 m-0"><strong>{{$data->kode_lhp}}</strong></h5>
					</div>
				</div><!-- END column -->
				<div class="col-xs-4">
					<div class="text-center p-h-md">
                        <h6>Judul LHP</h4>
						<h5 class=" fw-400 m-0"><strong>{{$data->judul_lhp}}</strong></h5>
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
                        <div class="col-md-7">&nbsp;</div>
                         <div class="col-md-3 text-right">
                             <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary">< Kembali </a>&nbsp;
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
                                            {{-- <th class="text-center primary">Jenis Temuan</th>
                                            <th class="text-center primary">PIC Temuan</th>
                                            <th class="text-center primary">Nilai Temuan </th>
                                            <th class="text-center primary">Level Resiko</th> --}}
                                            <th class="text-center primary">Rekomendasi</th>
                                            <th class="text-center primary">Aksi</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no=1;
                                        @endphp
                                        @foreach ($tindaklanjut as $item)
                                            <tr>
                                                <td class="text-center">{{$no}}</td>
                                                <td class="text-center"></td>
                                            </tr>
                                        @php
                                            $no++;
                                        @endphp
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
    <link rel="stylesheet" href="{{asset('theme/backend/libs/bower/summernote/dist/summernote.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/noty.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/tooltips.css')}}"/>
    <script src="{{asset('theme/backend/libs/misc/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('theme/backend/libs/bower/summernote/dist/summernote.min.js')}}"></script>
    {{-- <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script> --}}
    
    <script src="{{asset('js/noty.js')}}"></script>
    <script src="{{asset('js')}}/numeral.min.js"></script>
    <script src="{{asset('js')}}/js.js"></script>
    <script src="{{asset('js')}}/temuan.js"></script>
    <script src="{{asset('js')}}/tindaklanjut.js"></script>
    <script src="{{asset('js')}}/jquery-ui.js"></script>
	<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        hidealert();

         </script>
    <style>
    /* .form-inline .btn
    {
        height:24px !important;
    } */
    .modal-dialog{
        margin-top:10px !important;
    }
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
    .kolom-hide
    {
        display:none;
    }
    </style>
@endsection

@section('modal')
    @include('backend.pages.data-lhp.tindak-lanjut.modal')
@endsection