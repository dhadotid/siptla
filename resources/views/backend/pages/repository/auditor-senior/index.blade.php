@extends('backend.layouts.master')

@section('title')
    <title>Repository</title>
@endsection

@section('content')

	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
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
                </div>
            </header>
          
            <hr class="widget-separator">
			<div class="widget-body">
                <div class="">
                    <span class="widget-title">Repository</span>
                    
                    <div class="row" style="margin-top:10px;font-size:20px;">
                        <div class="col-md-12">
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
    <script src="{{asset('js/repository.js')}}"></script>
	<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        loaddata('{{$tahun}}');
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