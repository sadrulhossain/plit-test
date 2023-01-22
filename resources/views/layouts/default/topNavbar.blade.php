<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="{{ URL::to('/') }}">
                <img src="{{ URL::to('/') }}/public/img/logo.png" alt="logo" />
            </a>
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
            data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!--<li class="show-hide-side-menu">
    <a title="" data-container="body" class="btn-show-hide-link">
        <i class="btn red-sunglo" >
            <span id="fullMenu" data-fullMenu="1">{!! __('label.FULL_SCREEN') !!}</span>
        </i>
    </a>
</li>-->



                {{-- <li class="dropdown dropdown-user">

                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                        data-close-others="true">
                        <?php
                        $user = Auth::user(); //get current user all information
                        if (!empty($user->photo) && file_exists('public/uploads/user/' . $user->photo)) {
                            ?>
                        <img alt="{{ $user['username'] }}" class="img-circle"
                            src="{{ URL::to('/') }}/public/uploads/user/{{ $user->photo }}" />
                        <?php } else { ?>
                        <img alt="{{ $user['username'] }}" class="img-circle"
                            src="{{ URL::to('/') }}/public/img/unknown.png" />
                        <?php } ?>
                        <span class="username username-hide-on-mobile">@lang('label.WELCOME') {{ $user->username }}
                            ({{ $user->userGroup->name }})</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="{{ url('admin/myProfile') }}" class="tooltips" title="My Profile">
                                <i class="icon-user"></i>@lang('label.MY_PROFILE')</a>
                        </li>
                        <li>
                            <a href="{{ url('admin/' . Auth::user()->id . '/changePassword') }}" class="tooltips"
                                title="Change Password">
                                <i class="icon-key"></i>@lang('label.CHANGE_PASSWORD')</a>
                        </li>

                        <li>
                            <a class="tooltips" title="Logout" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                <i class="icon-logout"></i> @lang('label.LOGOUT')
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li> --}}
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li>
                    <a class="tooltips" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"
                        title="Logout">
                        <i class="icon-logout"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>

<!-- Modal start -->

<!-- Modal end -->
<script type="text/javascript">
    $(document).ready(function() {

        $('.show-tooltip').tooltip();
        $('.tooltips').tooltip();

    });
</script>
