<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                    data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>

    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Dashboards</li>
                <li>
                    <a href="{{ route('backend.dashboard') }}"
                        class="{{ Route::is('backend.dashboard') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                        Dashboard
                    </a>
                </li>
                <li class="app-sidebar__heading">User Management</li>
                <li class="{{ Route::is('backend.super-admin.index*') || Route::is('backend.roles.index*') ? 'mm-active' : '' }}">
                    <a href="#"
                        class="">
                        <i class="metismenu-icon pe-7s-users"></i>
                        Property
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                    @canany('backend.super-admin.index')
                        <li>
                            <a href="{{route('backend.super-admin.index')}}"
                                class="{{ Route::is('backend.super-admin.index*') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Users
                            </a>
                        </li>                       
                        @endcanany
                        @canany('backend.roles.index')
                            <li>
                                <a href="{{ route('backend.roles.index') }}"
                                    class="{{ Route::is('backend.roles.index*') ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon"></i>
                                    Roles
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </li>
        </div>
    </div>
</div>
