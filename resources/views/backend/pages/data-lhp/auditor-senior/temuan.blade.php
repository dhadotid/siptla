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
                                        @foreach ($temuan as $ky=>$item)
                                            <tr class="info text-dark">
                                                <td class="text-center">{{$no}}</td>
                                                <td class="text-left">{{$item->no_temuan}}</td>
                                                <td class="text-left">{!!$item->temuan!!}</td>
                                                {{-- <td class="text-center">{{isset($item->jenistemuan->temuan) ? $item->jenistemuan->temuan: '-'}}</td>
                                                <td class="text-center">{{isset($item->picunit->nama_pic) ? $item->picunit->nama_pic: '-'}}</td>
                                                <td class="text-right">{{number_format($item->nominal,2,',','.')}}</td>
                                                <td class="text-center">{{isset($item->levelresiko->level_resiko) ? $item->levelresiko->level_resiko: '-'}}</td> --}}
                                                <td class="text-center">
                                                    <div style="text-align:center">
                                                        @php
                                                            $jlhrekom=isset($rekomendasi[$item->temuan_id]) ? count($rekomendasi[$item->temuan_id]) : 0;
                                                        @endphp
                                                        <span class="rekomendasi-detail" data-value="{{ $item->id }}">
                                                            <span id="div-jlh-rekom-{{ $item->id }}">
                                                                <span style="cursor:pointer" class="label label-{{$jlhrekom==0 ? 'dark' : 'primary'}} fz-sm">{{$jlhrekom}}</span>
                                                            </span>
                                                            <span style="cursor:pointer" class="label label-success fz-sm">Rekomendasi</span>
                                                        </span>
                                                        <span style="cursor:pointer" class="label label-info fz-sm" data-toggle="modal" data-target="#modaltambahrekomendasi">
                                                            <a data-toggle="tooltip" title="Tambah Rekomendasi" style="color:#fff" data-value="{{ $item->temuan_id }}" onclick="rekomadd('{{$item->temuan_id}}')"><i class="fa fa-plus-circle"></i></a>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-align:center">
                                                    <div style="text-align:center">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-bars"></i></button>
                                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li>
                                                                    <a href="#" class="btn-detail-temuan" data-toggle="modal" data-target="#modaldetail" data-value="{{$item->temuan_id}}"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Temuan</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" class="btn-edit-temuan" data-toggle="modal" data-target="#modalubah" data-value="{{ $item->temuan_id }}"><i class="glyphicon glyphicon-edit"></i> &nbsp;&nbsp;Edit Temuan</a>
                                                                </li>
                                                                <li>
                                                                    <a class="btn-delete-temuan" data-toggle="modal" data-target="#modalhapus" data-value="{{ $item->id }}"><i class="glyphicon glyphicon-trash"></i> &nbsp;&nbsp;Hapus Temuan</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
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
        $('#datatable-temuan').on('click', '.btn-edit-temuan', function () {
            var id = $(this).data('value')
            // alert(id);
            $.ajax({
                url: "{{ url('data-temuan-lhp-edit') }}/" + id,
                success: function (res) {
                    $('#temuan_id').val(id);
                    $('#edit_nomor_temuan').val(res.no_temuan);
                    $('#edit_temuan').val(res.temuan);
                    $('#edit_jenis_temuan').val(res.jenis_temuan_id);
                    $('#edit_jenis_temuan').select2().trigger('change');
                    $('#edit_pic_temuan').val(res.pic_temuan_id);
                    $('#edit_pic_temuan').select2().trigger('change');
                    $('#edit_nominal').val(format(res.nominal));
                    $('#edit_level_resiko').val(res.level_resiko_id);
                    $('#edit_level_resiko').select2().trigger('change');
                }
            })
        })

        // delete action
        $('#datatable-temuan').on('click', '.btn-delete-temuan', function () {
            var id = $(this).data('value')
            $('#form-delete').attr('action', "{{ url('data-temuan-lhp-delete') }}/{{$idlhp}}/" + id)
        })
        
        $('#datatable-temuan').on('click', '.btn-detail-temuan', function () {
            var id = $(this).data('value')
            $.ajax({
                url: "{{ url('data-temuan-lhp-edit') }}/" + id,
                success: function (res) {
                    $('#detail_nomor_temuan').val(res.no_temuan);
                    $('#detail_temuan').val(res.temuan);
                    $('#detail_jenistemuan').val(res.jenistemuan.temuan);
                    $('#detail_pic_temuan').val(res.picunit.nama_pic);
                    $('#detail_nominal').val(format(res.nominal));
                    $('#detail_levelresiko').val(res.levelresiko.level_resiko);
                }
            })
        })


        $('#table').on('click', '.btn-edit-rekom', function () {
            var id = $(this).data('value')
            $('#idtemuan').val(id);
            // alert(id);
            $.ajax({
                url: "{{ url('rekomendasi-edit') }}/" + id,
                success: function (res) {
                    // $('#edit_level_resiko').select2().trigger('change');
                }
            })
        })

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
    @include('backend.pages.data-lhp.auditor-junior.modal')
    @include('backend.pages.data-lhp.tindak-lanjut.modal')
@endsection