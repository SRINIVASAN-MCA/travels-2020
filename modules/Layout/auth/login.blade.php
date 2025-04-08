@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center bravo-login-form-page bravo-login-page">
        <div class="col-md-5">
            <div class="text-center">
                <h4 class="form-title">{{ __('Login') }}</h4>
            </div>
            @include('Layout::auth.login-form',['captcha_action'=>'login_normal'])
            <!-- @include('Layout::auth.mobile-login') -->
        </div>
    </div>
</div>
@endsection
