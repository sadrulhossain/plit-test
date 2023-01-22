@include('layouts.default.header')
<body id="addFullMenuClass" class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-fixed">
    <div class="page-wrapper">
        @include('layouts.default.topNavbar')
        <div class="clearfix"> </div>
        <div class="page-container">
            @include('layouts.default.sidebar')
            <div class="page-content-wrapper">
                <?php
                $dashboardContent = '';
                if ($controllerName == 'dashboard') {
                    $dashboardContent = 'dashboard-content';
                }
                ?>
                <div class="page-content {{$dashboardContent}}">
                    @yield('data_count')
                    <div class="clearfix"></div>
                </div>
            </div>
            <a href="javascript:;" class="page-quick-sidebar-toggler">
                <i class="icon-login"></i>
            </a>
        </div>
        @include('layouts.default.footer')
    </div>

    <div class="quick-nav-overlay"></div>
    @include('layouts.default.footerScript')
</body>
</html>