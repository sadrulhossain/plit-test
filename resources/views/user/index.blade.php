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
                @if (!empty($userAccessArr[1][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('admin/user/create' . Helper::queryPageStr($qpArr)) }}">@lang('label.CREATE_NEW_USER')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(['group' => 'form', 'url' => 'admin/user/filter', 'class' => 'form-horizontal']) !!}
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
                        <label class="control-label col-md-4" for="department">@lang('label.DEPARTMENT')</label>
                        <div class="col-md-8">
                            {!! Form::select('department', $userDepartmentOption, Request::get('department'), ['class' => 'form-control js-source-states', 'id' => 'department']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="designation">@lang('label.DESIGNATION')</label>
                        <div class="col-md-8">
                            {!! Form::select('designation', $designationList, Request::get('designation'), ['class' => 'form-control js-source-states', 'id' => 'designation']) !!}
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

                <div class="col-md-2 text-center">
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
                            <th class="text-center vcenter">@lang('label.EMPLOYEE_ID')</th>
                            <th class="text-center vcenter">@lang('label.PHOTO')</th>
                            <th class="vcenter">@lang('label.USER_GROUP')</th>
                            <th class="vcenter">@lang('label.DEPARTMENT')</th>
                            <th class="vcenter">@lang('label.DESIGNATION')</th>
                            <th class="text-center vcenter">@lang('label.EMAIL')</th>
                            <th class="text-center vcenter">@lang('label.PHONE')</th>
<!--                            <th class="text-center vcenter">@lang('label.ALLOWED_FOR_CRM')</th>
                            <th class="text-center vcenter">@lang('label.FOR_CRM_LEADER')</th>-->
                            <th class="vcenter">@lang('label.NAME')</th>
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
                            <td class="text-center vcenter">{{ $target->employee_id }}</td>
                            <td class="text-center vcenter">
                                @if (!empty($target->photo) && File::exists('public/uploads/user/' . $target->photo))
                                <img width="40" height="40" src="{{ URL::to('/') }}/public/uploads/user/{{ $target->photo }}" alt="{{ $target->full_name }}" />
                                @else
                                <img width="40" height="40" src="{{ URL::to('/') }}/public/img/unknown.png" alt="{{ $target->full_name }}" />
                                @endif
                            </td>
                            <td class="vcenter">{{ $target->group_name }}</td>
                            <td class="vcenter">{{ $target->department_name }}</td>
                            <td class="vcenter">{{ $target->designation_name }}</td>
                            <td class="text-center vcenter">
                                {{$target->email ?? 'N/A'}}                              
                            </td>
                            <td class="text-center vcenter">
                                {{$target->phone ?? 'N/A'}}
                            </td>

                            <td class="vcenter"> {{ $target->first_name . ' ' . $target->last_name }} </td>
                            <td class="vcenter">{{ $target->username }}</td>
                            <td class="text-center vcenter">
                                @if ($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if (!empty($userAccessArr[1][3]))
                                    <button type="button" class="btn yellow btn-xs tooltips addUserInfo" title="" id="" data-target="#userInfo" 
                                            data-toggle="modal" data-id="{{ $target->id }}" data-original-title="Click here to Add More Details">
                                        <i class="fa fa-user text-white"></i>
                                    </button>

                                    
                                    <a class="btn yellow-gold btn-xs tooltips" title="@lang('label.CHANGE_PASSWORD')" href="{{ URL::to('admin/' . $target->id . '/changePassword') }}">
                                        <i class="icon-key text-white"></i>
                                    </a>

                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('admin/user/' . $target->id . '/edit' . Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if (!empty($userAccessArr[1][4]))
                                    {{ Form::open(['url' => 'admin/user/' . $target->id . '/' . Helper::queryPageStr($qpArr),'class' => 'delete-form-inline']) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    {{ Form::close() }}
                                    @endif
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
<!-- Modal start -->
<div class="modal fade" id="userInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="addAdditionalInfo">
        </div>
    </div>
</div>
<div class="modal fade" id="changeUserPassword" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="changePassword">
        </div>
    </div>
</div>
<!-- Modal end-->
<script type="text/javascript">
    $(function () {
        // Adding Additional Information to User Start
        $('.addUserInfo').on('click', function (e) {
            e.preventDefault();
            var user_id = $(this).attr('data-id');
            $.ajax({
                url: "{{ URL::to('admin/user/getUserAdditionalInfo') }}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    user_id: user_id
                },
                success: function (res) {
                    $("#addAdditionalInfo").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            });
        });
        // Adding Additional Information to User End
        // User Change Password Start
        $('.change-password').on('click', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var url = "{{url('/')}}" + "/admin/" + id + "/changePassword ";
//            alert(url);
            $.ajax({
                url: url,
                type: "get",
                dataType: "json",
                success: function (res) {
                    $("#changePassword").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            });
        });
        // User Change Password End
        $(document).on('click', '#submitUserAdditionalInfo', function (e) {
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            e.preventDefault();
            var formData = new FormData($('#userAddtionalIfoForm')[0]);
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Update',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('admin/user/setUserAdditionalInfo') }}",
                        type: "POST",
                        dataType: "json",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                            App.unblockUI();
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {

                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading,
                                        options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '',
                                        options);
                            } else if (jqXhr.status == 422) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', "@lang('label.SOMETHING_WENT_WRONG')",
                                        options);
                            }
                            App.unblockUI();
                        }
                    });
                }
            });
        });
    });
</script>
@stop
