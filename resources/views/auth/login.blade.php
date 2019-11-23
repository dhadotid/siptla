<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SIPTLA - SPI Universitas Indonesia</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="Admin, Dashboard, Bootstrap" />
	<link rel="shortcut icon" sizes="196x196" href="{{asset('logo.png')}}">
	
	<link rel="stylesheet" href="theme/backend/libs/bower/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="theme/backend/libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="theme/backend/libs/bower/animate.css/animate.min.css">
	<link rel="stylesheet" href="theme/backend/assets/css/bootstrap.css">
	<link rel="stylesheet" href="theme/backend/assets/css/core.css">
	<link rel="stylesheet" href="theme/backend/assets/css/misc-pages.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300">
</head>
<body class="simple-page myDiv" style="">
    {{-- background:#eec900; --}}
	<div class="simple-page-wrap">
        
		<div class="simple-page-logo swing">
			<a href="{{url('/')}}">
				{{-- <span><i class="fa fa-gg"></i></span> --}}
                <span style="">SIPTLA By SPI</span><br>
                <span  style="font-size:15px;">Universitas Indonesia</span>
			</a>
		</div><!-- logo -->
		<div class="simple-page-form animated flipInY text-center" id="login-form">
            <img src="{{asset('logo.png')}}" style="width:100px;margin:0 auto;">
            <h4 class="form-title m-b-xl text-center"><br>Silahkan Lakukan Login</h4>
                @if (Session::has('success'))
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						<strong>Berhasil! </strong>
						<span>{!!Session::get('success')!!}</span>
					</div>
				@endif
                @if (Session::has('error'))
					<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						<strong>Peringatan! </strong>
						<span>{!!Session::get('error')!!}</span>
					</div>
				@endif
                <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                @csrf
                    <div class="form-group">
                        {{-- <input id="sign-in-email" type="email" class="form-control" placeholder="Email"> --}}
                        <input id="sign-in-email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        {{-- <input id="sign-in-password" type="password" class="form-control" placeholder="Password"> --}}
                        <input id="sign-in-password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    {{-- <div class="form-group m-b-xl">
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" id="keep_me_logged_in"/>
                            <label for="keep_me_logged_in">Keep me signed in</label>
                        </div>
                    </div> --}}
                <button type="submit" class="btn btn-primary" style="color:#fff;">
                    {{ __('Login') }}
                </button>
            </form>
        </div><!-- #login-form -->

        {{-- <div class="simple-page-footer">
            <p><a href="password-forget.html">FORGOT YOUR PASSWORD ?</a></p>
            <p>
                <small>Don't have an account ?</small>
                <a href="signup.html">CREATE AN ACCOUNT</a>
            </p>
        </div><!-- .simple-page-footer --> --}}
        {{-- <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a> --}}

	</div><!-- .simple-page-wrap -->
</body>
</html>
                   
<script>
    setTimeout(function () {
        $('.alert').fadeOut();
    }, 3000);
</script>          
<style>
.myDiv {
    position: relative;
    z-index: 1;
    padding-bottom:11%;
}

.myDiv:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background: url('{{asset("bg.jpg")}}') no-repeat;
    background-position: 50% 100%;
    background-size:100% 100%;
    opacity: .4;
}

</style>