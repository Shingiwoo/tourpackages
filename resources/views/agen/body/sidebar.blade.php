<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img width="38" height="38" src="{{ asset('assets/img/logo/logo-tourpackage.svg') }}" alt="Tour Pack" class="logo-icon me-2" />
            </span>
            <span class="app-brand-text demo menu-text fw-bold">Paket Wisata</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item">
            <a href="{{ route('agen.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-layout-dashboard"></i>
                <div data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>

        <!-- All Package -->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Manage Package">Manage Package</span>
        </li>
        <li class="menu-item">
            <a href="" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-box"></i>
                <div data-i18n="Package">Package</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route("agen.all.package") }}" class="menu-link">
                        <div data-i18n="Package List">Package List</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End All Package -->

        <!-- Booking -->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Manage Booking">Manage Booking</span>
        </li>
        <li class="menu-item">
            <a href="" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-shopping-cart-plus"></i>
                <div data-i18n="Booking">Booking</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route("agen.booking") }}" class="menu-link">
                        <div data-i18n="Booking List">Booking List</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Booking -->







    </ul>
</aside>
