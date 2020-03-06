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
				<span class="widget-title">Daftar Temuan</span>
                
                {{-- @if (!Auth::user()->level==1 || Auth::user()->level==2) --}}
                    <div class="row">
                        <div class="col-md-8">&nbsp;</div>
                        <div class="col-md-3 text-right">
                            <select name="dinas_id" id="dinas_id" class="form-control text-left" data-plugin="select2" style="text-align:left !important" onchange="getdata()">
                                <option value="">-- Pilih Dinas --</option>
                                @foreach ($dinas as $item)
                                    @if (Auth::user()->level==3 || Auth::user()->level==4)
                                        @php
                                            $user=\App\User::where('id',Auth::user()->id)->with('user')->first();
                                            $dinas_id=$user->user->dinas_id;
                                        @endphp
                                        @if ($dinas_id==$item->id)
                                            <option value="{{$item->id}}" selected="selected">{{$item->nama_dinas}}</option>
                                        @endif
                                    @else
                                        @if ($dinas_id==$item->id)
                                            <option value="{{$item->id}}" selected="selected">{{$item->nama_dinas}}</option>
                                        @else    
                                            <option value="{{$item->id}}">{{$item->nama_dinas}}</option>
                                        @endif    
                                    @endif
                                    
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 text-right">
                            <select name="tahun" id="tahun" class="form-control text-left" data-plugin="select2" onchange="getdata()">
                                @for ($i = (date('Y')-20); $i <= (date('Y')); $i++)
                                    @if ($tahun==$i)
                                        <option value="{{$i}}" selected="selected"}}>{{$i}}</option>
                                    @else
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:5px;">
                                <div class="col-md-8">&nbsp;</div>
                                
                                <div class="col-md-4 text-right">
                                    <select name="bidang" id="bidang" class="form-control text-left" data-plugin="select2" onchange="getdata()">
                                    <option value="">-- Pilih Bidang Pengawasan --</option>
                                        @foreach ($bidang as $item)
                                            @if ($item->id==$pengawasan_id)
                                                <option value="{{$item->id}}" selected="selected">{{$item->bidang}}</option>
                                            @else
                                                <option value="{{$item->id}}">{{$item->bidang}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                {{-- @endif --}}
            </header>
            
			<hr class="widget-separator">
			<div class="widget-body">
                
				<div class="">
                    
                    <div class="row" style="">
                        <div class="col-md-12">
                            
                            <div id="data">
                                <div class="text-center"><h4>Silahkan Pilih Data OPD, Tahun Pemeriksaan dan Bidang Pengawasan Terlebih Dahulu</h4></div>
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
    <script src="{{asset('theme/backend/libs/misc/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('theme/backend/libs/misc/flot/jquery.flot.tooltip.min.js')}}"></script>
	<script>
        // loaddata(-1,-1);
        var dinas_id='{{$dinas_id}}';
        var tahun='{{$tahun}}';
        var pengawasan_id='{{$pengawasan_id}}';
        
        // alert(dinas_id)

        if(dinas_id!='' && tahun!='' && pengawasan_id!='')
        {
            loaddata(dinas_id,tahun,pengawasan_id);
        }
        else
            loaddata(dinas_id,tahun,-1);


        function getdata()
        {
            var dinas_id=$('#dinas_id').val();
            var tahun=$('#tahun').val();
            var bidang=$('#bidang').val();
            loaddata(dinas_id,tahun,bidang);
        }
        function loaddata(dinas_id,tahun,bidang)
        {
            if(bidang!='')
            {
                $('#data').load('{{url("list-temuan-data")}}/'+dinas_id+'/'+tahun+'/'+bidang,function(){
                    $('#table').DataTable();
                    $('[data-toggle="tooltip"]').tooltip();
                });
            }
            else
            {
                $('#data').load('{{url("list-temuan-data")}}/-1/'+tahun+'/-1',function(){
                    $('#table').DataTable();
                    $('[data-toggle="tooltip"]').tooltip();
                });
            }
            
        }
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
