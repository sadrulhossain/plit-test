@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>@lang('label.USER_LIST')
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('user/create' . Helper::queryPageStr($qpArr)) }}">@lang('label.CREATE_NEW_USER')
                    <i class="fa fa-plus create-new"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(['group' => 'form', 'url' => 'user/filter', 'class' => 'form-horizontal']) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="userGroup">@lang('label.USER_GROUP')</label>
                        <div class="col-md-8">
                            {!! Form::select('user_group', $groupList, Request::get('user_group'), ['class' => 'form-control js-source-states', 'list' => 'userGroup', 'autocomplete' => 'off']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.NAME')</label>
                        <div class="col-md-8">
                            {!! Form::text('search', Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Username', 'placeholder' => 'Username', 'list' => 'userName', 'autocomplete' => 'off']) !!}
                            <datalist id="userName">
                                @if (!empty($nameArr))
                                @foreach ($nameArr as $userName)
                                <option value="{{ $userName->username }}"></option>
                                @endforeach
                                @endif
                            </datalist>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="status">@lang('label.STATUS')</label>
                        <div class="col-md-8">
                            {!! Form::select('status', $status, Request::get('status'), ['class' => 'form-control js-source-states', 'id' => 'status']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">


                <div class="col-md-12 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
            </div>


            {!! Form::close() !!}
            <!-- End Filter -->

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center info">
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="text-center vcenter">@lang('label.PHOTO')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="vcenter">@lang('label.USER_GROUP')</th>
                            <th class="text-center vcenter">@lang('label.EMAIL')</th>
                            <th class="text-center vcenter">@lang('label.PHONE')</th>
                            <th class="vcenter">@lang('label.USERNAME')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="td-actions text-center vcenter">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach ($targetArr as $target)
                        <tr>
                            <td class="text-center vcenter">{{ ++$sl }}</td>
                            <td class="text-center vcenter">
                                @if (!empty($target->photo) && File::exists('public/uploads/user/' . $target->photo))
                                <img width="40" height="40" src="{{ URL::to('/') }}/public/uploads/user/{{ $target->photo }}" alt="{{ $target->full_name }}" />
                                @else
                                <img width="40" height="40" src="{{ URL::to('/') }}/public/img/unknown.png" alt="{{ $target->full_name }}" />
                                @endif
                            </td>
                            <td class="vcenter"> {{ $target->name ?? '' }} </td>
                            <td class="vcenter">{{ $target->group_name }}</td>
                            <td class="text-center vcenter">
                                {{$target->email ?? 'N/A'}}                              
                            </td>
                            <td class="text-center vcenter">
                                {{$target->phone ?? 'N/A'}}
                            </td>

                            <td class="vcenter">{{ $target->username ?? '' }}</td>
                            <td class="text-center vcenter">
                                @if ($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('user/' . $target->id . '/edit' . Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    {{ Form::open(['url' => 'user/' . $target->id . '/' . Helper::queryPageStr($qpArr),'class' => 'delete-form-inline']) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    {{ Form::close() }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_USER_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>
    </div>
</div>
@stop
