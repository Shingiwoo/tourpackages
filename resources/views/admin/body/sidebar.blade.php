<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">

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
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-layout-dashboard"></i>
                <div data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>

        <!-- Manage Agen-->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Manage Agen">Manage Agen</span>
        </li>
        <li class="menu-item">
            <a href="" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-user-pentagon"></i>
                <div data-i18n="Agen">Agen</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Agen List">Agen List</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Manage Agen-->



        <!-- Tour Packages-->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Manage Packages">Manage Packages</span>
        </li>
        <li class="menu-item">
            <a href="" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-packages"></i>
                <div data-i18n="Packages">Packages</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Packages List">Packages List</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Add Packages">Add Packages</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Tour Packages-->

        <!-- Misc -->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Misc">Misc</span>
        </li>
        <li class="menu-item">
            <a href="" target="_blank" class="menu-link">
                <i class="menu-icon tf-icons ti ti-lifebuoy"></i>
                <div data-i18n="Support">Support</div>
            </a>
        </li>
    </ul>
</aside>
