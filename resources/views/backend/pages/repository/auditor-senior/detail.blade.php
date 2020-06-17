@extends('backend.layouts.master')

@section('title')
    <title>Repository</title>
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
                        <h6>Judul LHP</h4>
						<h4 class=" fw-400 m-0"><strong>{{$data->judul_lhp}}</strong></h4>
					</div>
				</div><!-- END column -->
                <div class="col-xs-4">
                    <div class="text-center p-h-md">
                        <h6>Pemeriksa</h4>
						<h4 class=" fw-400 m-0"><strong>{{$data->dpemeriksa->pemeriksa}}</strong></h4>
					</div>
				</div><!-- END column -->
			</div><!-- .widget-body -->
		</div>
		<div class="widget">
			<header class="widget-header" style="padding-bottom:10px;margin-bottom:0px;">
                <div class="row" style="margin-bottom:0px;padding-bottom:0px;">
                    <div class="col-md-2 text-left">
                            <h3 class="widget-title">Repository</h3>
                    </div>
                </div>
            </header>
            
            <hr class="widget-separator">
			<div class="widget-body">
                <div class="">
                    <div class="row" style="margin-top:10px;font-size:20px;">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="table-detail-repository" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr class="primary">
                                            <th class="text-center" style="width:15px;">#</th>
                                            <th class="text-center">No. Rekomendasi</th>
                                            <th class="text-center">Rekomendasi</th>
                                            <th class="text-center">Dokumen Pendukung</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rekom as $key=>$item)
                                        <tr>
                                            <td class="text-center">{{ ($key + 1) }}</td>
                                            <td class="text-left">
                                                {{$item->nomor_rekomendasi}}
                                            </td>
                                            
                                            <td>{{$item->rekomendasi}}</td>
                                            
                                            <td class="text-center" style="width:180px;">
                                                <div class="btn-group"> 
                                                    <button type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li>
                                                            <a href="javascript:tindak_lanjut({{$item->id}},{{$item->id_temuan}})"><i class="fa fa-bars"></i> &nbsp;&nbsp;Dokumen Tindak Lanjut</a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:tindak_lanjut_rincian({{$item->id}},{{$item->id_temuan}})"><i class="fa fa-bars"></i> &nbsp;&nbsp;Dokumen Tindak Lanjut Rincian Rekomendasi</a>
                                                        </li>
                                                    </ul>
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
    <link rel="stylesheet" href="{{asset('theme/backend/libs/bower/summernote/dist/summernote.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/noty.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/tooltips.css')}}"/>
    <script src="{{asset('theme/backend/libs/bower/summernote/dist/summernote.min.js')}}"></script>
    <script src="{{asset('theme/backend/libs/misc/datatables/datatables.min.js')}}"></script>
    
    <script src="{{asset('js/noty.js')}}"></script>
    <script src="{{asset('js')}}/numeral.min.js"></script>
    <script src="{{asset('js')}}/js.js"></script>
    <script src="{{asset('js')}}/repository.js"></script>
    <script src="{{asset('js')}}/jquery-ui.js"></script>
	<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        hidealert();
        $('#table-detail-repository').DataTable();
    </script>
    <style>
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
    .select2-container
    {
        z-index:20000 !important;
    }
    </style>
@endsection
@section('modal')
    @include('backend.pages.repository.modal')
@endsection