@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>@lang('label.EDIT_USER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('user.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="groupId">@lang('label.USER_GROUP') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('group_id', $groupList, null, ['class' => 'form-control js-source-states', 'id' => 'groupId']) !!}
                                <span class="text-danger">{{ $errors->first('group_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="userEmail">@lang('label.EMAIL') :</label>
                            <div class="col-md-8">
                                {!! Form::email('email', null, ['id'=> 'userEmail', 'class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="phone">@lang('label.PHONE') :</label>
                            <div class="col-md-8">
                                {!! Form::text('phone', null, ['id'=> 'phone', 'class' => 'form-control integer-only']) !!}
                                <span class="text-danger">{{ $errors->first('user_phone') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="userName">@lang('label.USERNAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('username', null, ['id'=> 'userName', 'class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('username') }}</span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="password">@lang('label.PASSWORD') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <div class="input-group bootstrap-touchspin">
                                    {!! Form::password('password', ['id'=> 'password', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                    <span class="input-group-btn">
                                        <button class="btn default show-pass" type="button" id="showPass">
                                            <i class="fa fa-eye" id="passIcon"></i>
                                        </button>
                                    </span>
                                </div>
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger">@lang('label.NOTE')</span>
                                    @lang('label.COMPLEX_PASSWORD_INSTRUCTION')
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="confPassword">@lang('label.CONF_PASSWORD') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <div class="input-group bootstrap-touchspin">
                                    {!! Form::password('conf_password', ['id'=> 'confPassword', 'class' => 'form-control']) !!}
                                    <span class="input-group-btn">
                                        <button class="btn default show-pass" type="button" id="showConfPass">
                                            <i class="fa fa-eye" id="confPassIcon"></i>
                                        </button>
                                    </span>
                                </div>
                                <span class="text-danger">{{ $errors->first('conf_password') }}</span>
                            </div>
                        </div>



                        @if(Auth::user()->id != $target->id)
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], null, ['class' => 'form-control js-source-states-2', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                        @else

                        {!! Form::hidden('status', $target->status) !!}
                        @endif
                    </div>
                    <div class="col-md-3">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                @if(!empty($target->photo))
                                <img src="{{URL::to('/')}}/public/uploads/user/{{$target->photo}}" alt="{{ $target->full_name}}" />
                                @endif
                            </div>
                            <div>
                                <span class="btn green-seagreen btn-outline btn-file">
                                    <span class="fileinput-new"> Select image </span>
                                    <span class="fileinput-exists"> Change </span>
                                    {!! Form::file('photo', null, ['id'=> 'photo']) !!}
                                </span>
                                @if(!empty($target->photo))
                                <a href="javascript:;" class="btn green-seagreen" data-dismiss="fileinput"> Remove </a>
                                @else
                                <a href="javascript:;" class="btn green-seagreen fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                @endif
                            </div>
                        </div>
                        <div class="clearfix margin-top-10">
                            <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.USER_IMAGE_FOR_IMAGE_DESCRIPTION')
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/admin/user'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        //        START::show pass
        $(document).on('click', '#show-pass', function() {
            $(this).children('i').toggleClass("fa-eye fa-eye-slash");
            var input = $(this).parent().siblings('input');
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
        //        END::show pass



    });
</script>
@stop