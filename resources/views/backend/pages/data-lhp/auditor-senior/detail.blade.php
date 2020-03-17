@extends('backend.layouts.master')

@section('title')
    <title>Detail LHP</title>
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
                        
                       
                        <div class="col-md-2 text-left">&nbsp;</div>
                        <div class="col-md-8">&nbsp;</div>
                         <div class="col-md-2 text-right">
                            <a href="{{url('data-lhp')}}" class="btn btn-sm btn-success pull-right"><i class="fa fa-chevron-left"></i> Kembali</a>
                        </div>
                    </div>
                    
                {{-- @endif --}}
            </header>
          
            <hr class="widget-separator">
			<div class="widget-body">
                <div class="">
                    <span class="widget-title">Data Detail LHP</span>
                    
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

@endsection