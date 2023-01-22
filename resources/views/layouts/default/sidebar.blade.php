<?php
$controllerName = Request::segment(1);
$controllerName = Request::route()->getName();
$currentControllerFunction = Route::currentRouteAction();
$currentCont = preg_match(
    '/([a-z]*)@/i',
    request()
        ->route()
        ->getActionName(),
    $currentControllerFunction,
);
$controllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
//dd($controllerName);
$routeName = strtolower(
    Route::getFacadeRoot()
        ->current()
        ->uri(),
);

?>
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul id="addsidebarFullMenu" class="page-sidebar-menu page-header-fixed" data-keep-expanded="false"
            data-auto-scroll="true" data-slide-speed="200">
            <!--li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li-->

            <!-- start dashboard menu -->
            <li <?php $current = in_array($controllerName, ['dashboard']) ? 'start active open' : ''; ?>class="nav-item {{ $current }} nav-item ">
                <a href="{{ url('/') }}" class="nav-link ">
                    <i class="icon-home"></i>
                    <span class="title"> @lang('label.DASHBOARD')</span>
                </a>
            </li>
            <li <?php
            $current = in_array($controllerName, ['department', 'designation', 'branch', 'usergroup', 'user']) ? 'start active open' : '';
            ?> class="nav-item {{ $current }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="info">@lang('label.USER_SETUP')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php $current = in_array($controllerName, ['usergroup']) ? 'start active open' : ''; ?> class="nav-item {{ $current }}">
                        <a href="{{ url('/userGroup') }}" class="nav-link ">
                            <span class="title">@lang('label.USER_GROUP')</span>
                        </a>
                    </li>
                    <li <?php $current = in_array($controllerName, ['user']) ? 'start active open' : ''; ?> class="nav-item {{ $current }}">
                        <a href="{{ url('/user') }}" class="nav-link ">
                            <span class="title">@lang('label.USER')</span>
                        </a>
                    </li>

                </ul>
            </li>

        </ul>
    </div>
</div>
