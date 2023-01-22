@extends('layouts.default.master')

@section('data_count')
@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}

</div>
@endif


<div class="portlet-body">
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('dashboard')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Dashboard</span>
            </li>
        </ul>
        <div class="page-toolbar">
            <h5 class="dashboard-date font-blue-madison"><span class="icon-calendar"></span> Today is <span class="font-blue-madison">{!! date('l, d F Y') !!}</span> </h5>
        </div>
    </div>


    <div class="row margin-top-20">
        {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat-v2  dashboard-stat yellow-casablanca tooltips" href="{{url('/admin/myProfile')}}" title="@lang('label.MY_PROFILE')">
                <div class="visual">
                    <i class="icon-user"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <i class="icon-user"></i>
                    </div>
                    <div class="desc">@lang('label.MY_PROFILE')</div>
                </div>
            </a>
        </div> --}}


    </div>
</div>


@endsection
