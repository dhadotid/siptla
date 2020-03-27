@extends('backend.layouts.master')

@section('title')
    <title>Data User</title>
@endsection


@section('content')
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<span class="widget-title">Profil Pengguna</span>
				
				
			</header><!-- .widget-header -->
			<hr class="widget-separator">
			<div class="widget-body">
				
				@if ($errors->any())
					<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						<strong>Alert ! </strong>
						<span>
							<ul>
							@foreach ($errors->all() as $item)
								<li>- {{$item}}</li>
							@endforeach
							</ul>
							
						</span>
					</div>	
				@endif
				@if (Session::has('success'))
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						<strong>Berhasil! </strong>
						<span>{!!Session::get('success')!!}</span>
					</div>
				@endif
					<form action="{{ route('pengguna.simpan-profil',$user->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
									<label>Jenis Level</label>
                                    <span class="form-control">{{strtoupper($user->level)}}</span>
                                </div>
                                <div class="form-group" id="add-name-txt" style="">
									<label>Nama Pengguna</label>
                                    <input name="name" type="text" class="form-control" placeholder="Nama Pengguna" id="addname" value="{{$user->name}}">
                                </div>
                                <div class="form-group" id="add-name-pic" style="display:none">
									<label>PIC Unit</label>
                                    <span class="form-control">{{isset($picunit->nama_pic) ? $picunit->nama_pic : ''}}</span>
                                </div>
                                <div class="form-group">
									<label>Email</label>
                                    <input name="email" type="text" class="form-control" placeholder="Email" value="{{$user->email}}">
                                </div>
                                <div class="form-group">
									<label>Telepon</label>
                                    <input name="telepon" type="text" class="form-control" placeholder="Telepon" value="{{$user->telepon}}">
                                </div>
                                <div class="form-group">
									<label>Password</label>
                                    <input name="password" type="password" class="form-control" placeholder="Password"><br>
                                    <small><i>*Biarkan Kosong Jika Tidak Ingin Mengganti Password</i></small>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success" value="Simpan">
                                </div>
                            </div>
                        </div>
					</form>
				
			</div><!-- .widget-body -->
		</div><!-- .widget -->
	</div>
@endsection

@section('footscript')
	<script>
		setTimeout(function(){
			$('.alert').fadeOut();
		},3000);
		$('.select2').select2();
		// binding data to modal edit
	</script>
	<style>
	.select2-container{
		width:100% !important;
	}
	</style>
@endsection