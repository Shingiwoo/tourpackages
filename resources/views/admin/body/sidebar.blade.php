<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="" class="app-brand-link">
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
        @if (Auth::user()->can('agen.menu'))
            <li class="menu-header small">
                <span class="menu-header-text" data-i18n="Manage Agen">Manage Agen</span>
            </li>
            <li class="menu-item">
                <a href="" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-user-pentagon"></i>
                    <div data-i18n="Agen">Agen</div>
                </a>

                <ul class="menu-sub">
                    @if (Auth::user()->can('agen.list'))
                    <li class="menu-item">
                        <a href="{{ route('all.agen') }}" class="menu-link">
                            <div data-i18n="Agen List">Agen List</div>
                        </a>
                    </li>
                    @endif
                    @if (Auth::user()->can('agen.add'))
                    <li class="menu-item">
                        <a href="{{ route('add.agen') }}" class="menu-link">
                            <div data-i18n="Add Agen">Add Agen</div>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- End Manage Agen-->

        <!-- Booking -->
        @if (Auth::user()->can('booking.menu'))
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
                    @if (Auth::user()->can('booking.list'))
                    <li class="menu-item">
                        <a href="" class="menu-link">
                            <div data-i18n="All Booking">All Booking</div>
                        </a>
                    </li>
                    @endif
                    @if (Auth::user()->can('booking.add'))
                    <li class="menu-item">
                        <a href="" class="menu-link">
                            <div data-i18n="Booking">Booking</div>
                        </a>
                    </li>
                    @endif
                </ul>
                <!-- Booking Manage End -->
            </li>
        @endif
        <!-- Booking End -->

        <!-- Packages -->
        @if (Auth::user()->can('package.tour.menu'))
            <li class="menu-header small">
                <span class="menu-header-text" data-i18n="Packages">Packages</span>
            </li>
            @if (Auth::user()->can('package.generate'))
            <li class="menu-item">
                <a href="{{ route('generate.all.packages') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-adjustments-plus"></i>
                    <div data-i18n="Generate All Tour">Generate All Tour</div>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('package.tour.menu'))
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
                            @if (Auth::user()->can('package.generate'))
                            <li class="menu-item">
                                <a href="{{ route('generate.package') }}" class="menu-link">
                                    <div data-i18n="Generate Tour">Generate Tour</div>
                                </a>
                            </li>
                            @endif
                            @if (Auth::user()->can('package.list'))
                            <li class="menu-item">
                                <a href="{{ route('all.packages') }}" class="menu-link">
                                    <div data-i18n="Tour List">Tour List</div>
                                </a>
                            </li>
                            @endif
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
                            @if (Auth::user()->can('package.generate'))
                            <li class="menu-item">
                                <a href="{{ route('generate.twoday.package') }}" class="menu-link">
                                    <div data-i18n="Generate Tour">Generate Tour</div>
                                </a>
                            </li>
                            @endif
                            @if (Auth::user()->can('package.list'))
                            <li class="menu-item">
                                <a href="{{ route('all.twoday.packages') }}" class="menu-link">
                                    <div data-i18n="Tour List">Tour List</div>
                                </a>
                            </li>
                            @endif
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
                            @if (Auth::user()->can('package.generate'))
                            <li class="menu-item">
                                <a href="{{ route('generate.threeday.package') }}" class="menu-link">
                                    <div data-i18n="Generate Tour">Generate Tour</div>
                                </a>
                            </li>
                            @endif
                            @if (Auth::user()->can('package.list'))
                            <li class="menu-item">
                                <a href="{{ route('all.threeday.packages') }}" class="menu-link">
                                    <div data-i18n="Tour List">Tour List</div>
                                </a>
                            </li>
                            @endif
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
                            @if (Auth::user()->can('package.generate'))
                            <li class="menu-item">
                                <a href="{{ route('generate.fourday.package') }}" class="menu-link">
                                    <div data-i18n="Generate Tour">Generate Tour</div>
                                </a>
                            </li>
                            @endif
                            @if (Auth::user()->can('package.list'))
                            <li class="menu-item">
                                <a href="{{ route('all.fourday.packages') }}" class="menu-link">
                                    <div data-i18n="Tour List">Tour List</div>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                </ul>
                <!-- Four Day End -->
            </li>
            @endif

            @if (Auth::user()->can('package.setting.menu'))
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-settings-cog"></i>
                    <div data-i18n="Setting">Setting</div>
                </a>
                <ul class="menu-sub">
                    <!-- Destinations -->
                    @if (Auth::user()->can('destinations.list'))
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
                            @if (Auth::user()->can('destinations.add'))
                            <li class="menu-item">
                                <a href="{{ route('add.destination') }}" class="menu-link">
                                    <div data-i18n="Add Destination">Add Destination</div>
                                </a>
                            </li>
                            @endif
                            @if (Auth::user()->can('destinations.import'))
                            <li class="menu-item">
                                <a href="{{ route('import.destinations') }}" class="menu-link">
                                    <div data-i18n="Import Destinations">Import Destinations</div>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- End Destinations-->

                    <!-- Vehicle -->
                    @if (Auth::user()->can('vehicles.list'))
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
                            @if (Auth::user()->can('vehicles.add'))
                            <li class="menu-item">
                                <a href="{{ route('add.vehicle') }}" class="menu-link">
                                    <div data-i18n="Add Vehicle">Add Vehicle</div>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- End Vehicle-->

                    <!-- Hotel -->
                    @if (Auth::user()->can('hotels.list'))
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
                            @if (Auth::user()->can('hotels.add'))
                            <li class="menu-item">
                                <a href="{{ route('add.hotel') }}" class="menu-link">
                                    <div data-i18n="Add hotel">Add Hotel</div>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- End Hotel-->

                    <!-- Other -->
                    @if (Auth::user()->can('service.list'))
                    <li class="menu-item">
                        <a href="{{ route('all.service') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-device-ipad-cog"></i>
                            <div data-i18n="Service">Service</div>
                        </a>
                    </li>
                    @endif
                    <!-- End Other -->
                </ul>
            </li>
            @endif
        @endif
        <!-- Packages end -->


        <!-- Manage Admin -->
        @if (Auth::user()->can('admin.menu'))
            <li class="menu-header small">
                <span class="menu-header-text" data-i18n="Manage Admin">Manage Admin</span>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-password-user"></i>
                    <div data-i18n="Manage Admin">Manage Admin</div>
                </a>
                <!-- Manage Admin -->
                @if (Auth::user()->can('admin.list'))
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('all.admin') }}" class="menu-link">
                            <div data-i18n="All Admin">All Admin</div>
                        </a>
                    </li>
                </ul>
                @endif
                <!-- /Manage Admin End -->
            </li>
        @endif
        <!-- Manage Admin -->

        <!-- Roles & Permissions -->
        @if (Auth::user()->can('role.permission.menu'))
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
                    @if (Auth::user()->can('role.list'))
                    <li class="menu-item">
                        <a href="{{ route('all.roles') }}" class="menu-link">
                            <div data-i18n="Roles">Roles</div>
                        </a>
                    </li>
                    @endif
                    @if (Auth::user()->can('permission.list'))
                    <li class="menu-item">
                        <a href="{{ route('all.permissions') }}" class="menu-link">
                            <div data-i18n="Permissions">Permissions</div>
                        </a>
                    </li>
                    @endif
                    @if (Auth::user()->can('role.permission.set'))
                    <li class="menu-item">
                        <a href="{{ route('add.roles.permission') }}" class="menu-link">
                            <div data-i18n="Set Role Permission">Set Role Permission</div>
                        </a>
                    </li>
                    @endif
                    @if (Auth::user()->can('role.permission.all'))
                    <li class="menu-item">
                        <a href="{{ route('all.role.permission') }}" class="menu-link">
                            <div data-i18n="All Role Permission">All Role Permission</div>
                        </a>
                    </li>
                    @endif
                </ul>
                <!-- Booking Manage End -->
            </li>
        @endif
        <!-- Roles & Permissions end -->

    </ul>
    <div class="mb-6">
    </div>
</aside>
