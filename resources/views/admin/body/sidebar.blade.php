<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <span class="app-brand-logo demo">

            </span>
            <span class="app-brand-text demo menu-text fw-bold text-primary">Paket Wisata</span>
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
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Add Agen">Add Agen</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Manage Agen-->

        <!-- Booking -->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Booking">Booking</span>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-calendar-cog"></i>
                <div data-i18n="Booking Manage">Booking Manage</div>
            </a>
            <!-- Booking Manage -->
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="All Booking">All Booking</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Booking">Booking</div>
                    </a>
                </li>
            </ul>
            <!-- Booking Manage End -->
        </li>
        <!-- Booking End -->

        <!-- Packages -->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Packages">Packages</span>
        </li>

        <li class="menu-item">
            <a href="{{ route('generate.all.packages') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-adjustments-plus"></i>
                <div data-i18n="Generate All Tour">Generate All Tour</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-map-bolt"></i>
                <div data-i18n="Tour">Tour</div>
            </a>
            <!-- One Day -->
            <ul class="menu-sub">
                <li class="menu-item">
                    <a class="menu-link menu-toggle">
                        <div data-i18n="Oneday">One Day</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('generate.package') }}" class="menu-link">
                                <div data-i18n="Generate Tour">Generate Tour</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('all.packages') }}" class="menu-link">
                                <div data-i18n="Tour List">Tour List</div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- One Day End -->

            <!-- Two Day -->
            <ul class="menu-sub">
                <li class="menu-item">
                    <a class="menu-link menu-toggle">
                        <div data-i18n="Twoday">Two Day</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('generate.twoday.package') }}" class="menu-link">
                                <div data-i18n="Generate Tour">Generate Tour</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('all.twoday.packages') }}" class="menu-link">
                                <div data-i18n="Tour List">Tour List</div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Two Day End -->

            <!-- Three Day -->
            <ul class="menu-sub">
                <li class="menu-item">
                    <a class="menu-link menu-toggle">
                        <div data-i18n="Threeday">Three Day</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('generate.threeday.package') }}" class="menu-link">
                                <div data-i18n="Generate Tour">Generate Tour</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('all.threeday.packages') }}" class="menu-link">
                                <div data-i18n="Tour List">Tour List</div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Three Day End -->

            <!-- Four Day -->
            <ul class="menu-sub">
                <li class="menu-item">
                    <a class="menu-link menu-toggle">
                        <div data-i18n="Fourday">Four Day</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('generate.fourday.package') }}" class="menu-link">
                                <div data-i18n="Generate Tour">Generate Tour</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('all.fourday.packages') }}" class="menu-link">
                                <div data-i18n="Tour List">Tour List</div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Four Day End -->
        </li>

        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings-cog"></i>
                <div data-i18n="Setting">Setting</div>
            </a>
            <ul class="menu-sub">
                <!-- Destinations -->
                <li class="menu-item">
                    <a class="menu-link menu-toggle">
                        <div data-i18n="Destinations">Destinations</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('all.destinations') }}" class="menu-link">
                                <div data-i18n="Destinations List">Destinations List</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('add.destination') }}" class="menu-link">
                                <div data-i18n="Add Destination">Add Destination</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('import.destinations') }}" class="menu-link">
                                <div data-i18n="Import Destinations">Import Destinations</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- End Destinations-->

                <!-- Vehicle -->
                <li class="menu-item">
                    <a href="" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons ti ti-car-garage"></i>
                        <div data-i18n="Vehicles">Vehicles</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('all.vehicles') }}" class="menu-link">
                                <div data-i18n="Vehicles List">Vehicles List</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('add.vehicle') }}" class="menu-link">
                                <div data-i18n="Add Vehicle">Add Vehicle</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- End Vehicle-->

                <!-- Hotel -->
                <li class="menu-item">
                    <a href="" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons ti ti-building-skyscraper"></i>
                        <div data-i18n="Hotels">Hotels</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('all.hotels') }}" class="menu-link">
                                <div data-i18n="Hotels List">Hotels List</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('add.hotel') }}" class="menu-link">
                                <div data-i18n="Add hotel">Add Hotel</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- End Hotel-->

                <!-- Other -->
                <li class="menu-item">
                    <a href="{{ route('all.service') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-device-ipad-cog"></i>
                        <div data-i18n="Service">Service</div>
                    </a>
                </li>
                <!-- End Other -->
            </ul>
        </li>
        <!-- Packages end -->

        <!-- Roles & Permissions -->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Roles & Permissions">Roles & Permissions</span>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-shield-lock"></i>
                <div data-i18n="Roles & Permissions">Roles & Permissions</div>
            </a>
            <!-- Booking Manage -->
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('all.roles') }}" class="menu-link">
                        <div data-i18n="Roles">Roles</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('all.permissions') }}" class="menu-link">
                        <div data-i18n="Permissions">Permissions</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('add.roles.permission') }}" class="menu-link">
                        <div data-i18n="Set Role Permission">Set Role Permission</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('all.role.permission') }}" class="menu-link">
                        <div data-i18n="All Role Permission">All Role Permission</div>
                    </a>
                </li>
            </ul>
            <!-- Booking Manage End -->
        </li>
        <!-- Roles & Permissions end -->

    </ul>
    <div class="mb-6">
    </div>
</aside>
