@extends('layouts.admin.master2')

@section('title', trans('site.reset_password'))

@section('css')
    <style>
        .bg-primary-transparent {
            background: url({{ asset('admin_assets/img/login-bg.jpg') }});
            background-repeat: no-repeat;
            background-size: cover;
        }
        .login .bg {
            position: absolute;
            right: 0;
            left: 0;
            bottom: 0;
            top: 0;
            z-index: -1;
            opacity: 0.8;
        }
        .col-sm-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        @media (max-width: 767px) {
            .main-signup-header, .main-card-signin {
                padding: unset !important;
                border: unset !important;
                border-radius: unset !important;
            }
        }
        
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="login d-flex align-items-center py-2">
            <div class="col-lg-4 col-md-6 col-sm-10 mx-auto">
                <div class="bg bg-white p-2"></div>

                <div class="card-sigin p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-3 text-center"> <a href="#">
                        <img src="{{ getSettingOf('logo') != null ? asset('images/settings/logo/'.getSettingOf('logo')) : asset('admin_assets/img/logo.png') }}" class="sign-favicon ht-40 avatar-xl brround" alt="{{ getSettingOf('site_title') ?? config('app.name') }}"></a><h1 class="main-logo1 mt-2 tx-28">{{ getSettingOf('site_title') ?? config('app.name') }}</h1>
                    </div>

                    <div class="card-sigin">
                        <div class="main-signup-header">
                            <form method="POST" action="{{ route('client.password.email') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="email" class="col-form-label text-md-end">{{ trans('site.email') }}</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> 

                            
                                <button type="submit" class="btn btn-main-primary btn-block">
                                    {{ trans('site.send_password_reset_link') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection
