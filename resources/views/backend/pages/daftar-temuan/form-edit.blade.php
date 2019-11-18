@extends('backend.layouts.master')

@section('title')
    <title>Tambah Detail Temuan</title>
@endsection
@section('content')
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<span class="widget-title">Tambah Detail Temuan</span>
            </header>
			<hr class="widget-separator">
			<div class="widget-body">
                <form class="form-horizontal" action="{{url('detail-temuan-update/'.$id)}}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleTextInput1" class="col-sm-4 control-label">Tahun Pemeriksaan</label>
                                <div class="col-sm-3">
                                    <select name="tahun" id="tahun" class="form-control text-left select2" data-plugin="select2" >
                                        @for ($i = $tahun; $i <= $tahun; $i++)
                                            <option value="{{$i}}" {{$tahun==$i ? 'selected="selected"' : ''}}>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email2" class="col-sm-4 control-label">Obyek Pemeriksaan</label>
                                <div class="col-sm-8">
                                    <select name="dinas_id" id="dinas_id" class="form-control text-left" style="text-align:left !important" data-plugin="select2" >
                                        @foreach ($dinas as $item)
                                            <option value="{{$item->id}}">{{$item->nama_dinas}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="textarea1" class="col-sm-4 control-label">Bidang Pengawasan</label>
                                <div class="col-sm-8">
                                    <select name="pengawasan_id" id="pengawasan_id" class="form-control text-left" style="text-align:left !important" data-plugin="select2" >  
                                        @foreach ($bidang as $item)
                                            <option value="{{$item->id}}">{{$item->bidang}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="textarea1" class="col-sm-4 control-label">Nomor Pengawasan</label>
                                <div class="col-sm-8">
                                <input type="text" name="no_pengawasan" class="form-control" id="no_pengawasan" value="{{$daftar->no_pengawasan}}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="textarea1" class="col-sm-4 control-label">Tanggal Pengawasan</label>
                                <div class="col-sm-4">
                                    <div class="input-group date" id="datetimepicker2" data-plugin="datepicker" data-date-format="dd/mm/yyyy">
										<input type="text" name="tgl_pengawasan" class="form-control" id="tgl_pengawasan" value="{{date('d/m/Y',strtotime($daftar->tgl_pengawasan))}}" readonly>
										<span class="input-group-addon bg-info text-white">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="widget-separator">
                    <br>
                    @for ($i = 0; $i < 1; $i++)
                    
                        <div class="row" style="">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-bottom:5px !important;">
                                            {!!$i==0 ? '<label for="exampleInputEmail1" class="col-md-4 control-label">Kode Temuan</label>' : ''!!}
                                            <div class="col-sm-8">
                                                <select name="temuan" id="temuan" class="form-control text-left" style="text-align:left !important" data-plugin="select2" >  
                                                        <option value="">-- Kode Temuan --</option>
                                                        @foreach ($temuan as $item)
                                                            @if ($item->id==$detail->temuan_id)
                                                                <option value="{{$item->id}}" selected="selected">{{$item->code}} - {{$item->temuan}}</option>
                                                            @else
                                                                <option value="{{$item->id}}">{{$item->code}} - {{$item->temuan}}</option>
                                                            @endif
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class="form-group">
                                            <label for="exampleInputEmail1" class="col-md-2 control-label">Uraian Temuan</label>
                                            <div class="col-md-10">
                                                <textarea class="form-control" name="uraian_temuan" id="uraian_temuan" style="margin-top:0px !important" placeholder="Uraian Singkat">{{$detail->uraian_temuan}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <hr class="widget-separator" style="margin:10px 0;">
                            <input type="hidden" name="sebab">
                            <input type="hidden" name="uraian_sebab">
                            <div class="col-md-12">
                            
                                 {{-- <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-bottom:5px !important;">
                                            {!!$i==0 ? '<label for="exampleInputEmail1" class="col-md-4 control-label">Kode Penyebab</label>' : ''!!}
                                            <div class="col-sm-8">
                                                <select name="sebab" id="sebab" class="form-control text-left" style="text-align:left !important" data-plugin="select2" >  
                                                        <option value="">-- Kode Penyebab--</option>
                                                        @foreach ($sebab as $item)
                                                            @if ($item->id==$detail->sebab_id)
                                                                <option value="{{$item->id}}" selected="selected">{{$item->code}} - {{$item->temuan}}</option>
                                                            @else
                                                                <option value="{{$item->id}}">{{$item->code}} - {{$item->sebab}}</option>
                                                            @endif
                                                        @endforeach
                                                </select>
                                        </div>
                                        </div> 
                                    </div>
                                </div> --}}
                                <div class="row">
                                    {{-- <div class="col-md-12" >
                                        <div class="form-group">
                                            <label for="exampleInputEmail1" class="col-md-2 control-label">Uraian Penyebab</label>
                                            <div class="col-md-10">
                                                
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                
                            </div>
                            <hr class="widget-separator" style="margin:10px 0;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-bottom:5px !important;">
                                            {!!$i==0 ? '<label for="exampleInputEmail1" class="col-md-4 control-label">Kode Rekomendasi</label>' : ''!!}
                                            <div class="col-sm-8">
                                                <select name="rekomendasi" id="rekomendasi" class="form-control text-left" style="text-align:left !important" data-plugin="select2" >  
                                                        <option value="">-- Kode Rekomendasi --</option>
                                                        @foreach ($rekomendasi as $item)
                                                            @if ($item->id==$detail->rekomendasi_id)
                                                                <option value="{{$item->id}}" selected="selected">{{$item->code}} - {{$item->rekomendasi}}</option>
                                                            @else
                                                                <option value="{{$item->id}}">{{$item->code}} - {{$item->rekomendasi}}</option>
                                                            @endif
                                                        @endforeach
                                                </select>
                                            </div> 
                                        </div> 
                                    </div> 
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1" class="col-md-2 control-label">Uraian Rekomendasi</label>
                                            <div class="col-md-10">
                                                <textarea class="form-control" name="uraian_rekomendasi" style="margin-top:0px !important" placeholder="Uraian Singkat" id="uraian_rekomendasi" >{{$detail->uraian_rekomendasi}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" >
                                        <div class="form-group">
                                            <label for="exampleInputEmail1" class="col-md-2 control-label">Kerugian</label>
                                            <div class="col-md-3">
                                                <input type="text" name="kerugian" class="form-control" id="kerugian" value="{{$detail->kerugian}}" >
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        
                    @endfor
                    <div class="row">
                        <div class="col-sm-9 col-sm-offset-3">
                            <button type="submit" class="btn btn-success pull-right">
                                <i class="fa fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('footscript')
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace( 'uraian_temuan' );
    // CKEDITOR.replace( 'uraian_sebab' );
    CKEDITOR.replace( 'uraian_rekomendasi' );
</script>
<script>
    $(document).ready(function(){
        
    });
</script>
<style>
form,.control-label,input,select,textarea,label
{
    font-size:11px !important;
}
</style>
@endsection