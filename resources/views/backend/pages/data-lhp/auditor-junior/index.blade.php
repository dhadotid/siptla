@extends('backend.layouts.master')

@section('title')
    <title>Daftar LHP</title>
@endsection
@section('modal')
    <div class="modal fade" id="modaltambah" tabindex="-1" role="dialog">
		<div class="modal-dialog">
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
    
@endsection
@section('content')

	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				
                
                {{-- @if (!Auth::user()->level==1 || Auth::user()->level==2) --}}
                    <div class="row">
                        
                       
                        <div class="col-md-2 text-left">Tahun
                            <select name="tahun" id="tahun" class="form-control text-left" data-plugin="select2" onchange="getdata()" style="width:50%">
                                @for ($i = (date('Y')-5); $i <= (date('Y')); $i++)
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
                            <a href="" class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modaltambah">+ Tambah Data</a>
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
    <link rel="stylesheet" href="{{asset('css/noty.css')}}"/>
    <script src="{{asset('theme/backend/libs/misc/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('theme/backend/libs/misc/flot/jquery.flot.tooltip.min.js')}}"></script>
    <script src="{{asset('js/noty.js')}}"></script>
	<script>
        loaddata('{{$tahun}}');
        
        $('.select').select2();
        function loaddata(tahun)
		{
			$('#data').load(flagsUrl+'/data-lhp-data/'+tahun,function(){
                $('#table').dataTable();
            });
        }
        function generatekodelhp(val)
        {
            $.ajax({
                url : flagsUrl+'/data-lhp-cek-kode/'+val,
                success : function(res){
                    $('#kode_lhp').val(res);
                }
            })
        }
        function validasiadd()
        {
            var nomor_lhp=$('#nomor_lhp');
            var pemeriksa=$('#pemeriksa');
            var judul_lhp=$('#judul_lhp');
            var jenis_audit=$('#jenis_audit');

            if(nomor_lhp.val()=='')
            {
                notif('error','Nomor LHP Belum Diisi');
                nomor_lhp.focus();
            }
            else if(pemeriksa.val()=='')
            {
                notif('error','Pemeriksa Belum Dipilih');
                pemeriksa.focus();
            }
            else if(judul_lhp.val()=='')
            {
                notif('error','Judul LHP Belum Diisi');
                judul_lhp.focus();
            }
            else if(jenis_audit.val()=='')
            {
                notif('error','Jenis Audit Belum Dipilih');
                jenis_audit.focus();
            }
            else
            {
                $('#formadd').submit()
            }
        }

        function notif(tp,txt)
        {
            new Noty({
                layout:'topRight',
                type : tp,
                theme : 'mint',
                text: txt,
                progressBar:true,
                timeout:3000,
            }).show();
        }
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
