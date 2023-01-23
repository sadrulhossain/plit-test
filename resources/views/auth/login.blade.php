@extends('layouts.login')
@section('login_content')
<!-- BEGIN LOGIN FORM -->
<form class="login-form" method="POST" action="{{ url('login') }}">
    @csrf
    <div class="row login-form-logo">
        <div class="col-md-12 text-center">
            <!-- BEGIN LOGO -->
            <div class="logo  margin-top-20">
                <a href="#" class="text-center">
                    <img src="{{URL::to('/')}}/public/img/login_logo.png" class="img-responsive" alt="logo" height="120px" width="auto"/>
                </a>
            </div>
            <!-- END LOGO -->
        </div>
    </div>

    <div class="form-group login-form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        @if ($errors->has('username'))
        <span class="invalid-feedback">
            <strong class="text-danger">{{ $errors->first('username') }}</strong>
        </span>
        @endif
        <label class="control-label visible-ie8 visible-ie9">@lang('label.USERNAME')</label>
        <div class="input-group bootstrap-touchspin width-inherit">
            <span class="input-group-addon bootstrap-touchspin-prefix bold maroon">
                <img src="{{URL::to('/')}}/public/img/username_icon.png" alt="username"/>
            </span>
            <input id="userName" type="text" class="form-control form-control-solid placeholder-no-fix {{ $errors->has('username') ? ' is-invalid' : '' }}" placeholder="@lang('label.USERNAME')" name="username" value="{{ old('username') }}" required/>
        </div>
    </div>
    <div class="form-group login-form-group">
        <label class="control-label visible-ie8 visible-ie9">@lang('label.PASSWORD')</label>
        <div class="input-group bootstrap-touchspin width-inherit">
            <span class="input-group-addon bootstrap-touchspin-prefix bold maroon">
                <img src="{{URL::to('/')}}/public/img/password_icon.png" alt="password"/>
            </span>
            <input id="password" type="password" class="form-control form-control-solid placeholder-no-fix{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="@lang('label.PASSWORD')" name="password" required/>
            <span class="input-group-btn">
                <button class="btn default show-pass" type="button" id="showPass">
                    <i class="fa fa-eye" id="passIcon"></i>
                </button>
            </span>
        </div>

        @if ($errors->has('password'))
        <span class="invalid-feedback">
            <strong class="text-danger">{{ $errors->first('password') }}</strong>
        </span>
        @endif
    </div>

    <div class="form-actions login-form-group">
        <button type="submit" class="btn maroon">@lang('label.LOGIN')</button>
        <!--label class="rememberme check mt-checkbox mt-checkbox-outline">
            <input type="checkbox" name="remember" value="1" />Remember
            <span></span>
        </label> -->
    </div>
    <div class="login-options">
        <div class="copyright">@lang('label.COPYRIGHT') &copy; {!! date('Y') !!}
        </div>
    </div>
</form>

<script src="{{asset('public/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
<script>
$(document).ready(function () {
    //START::show pass
    $(document).on('click', '#showPass', function () {
        $('#passIcon').toggleClass("fa-eye fa-eye-slash");
        var input = $('#password');
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    //END::show pass
});

</script>
<!-- END LOGIN FORM -->
@endsection
