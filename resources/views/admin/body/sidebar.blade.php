<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img width="38" height="38" src="{{ asset('assets/img/logo/logo-tourpackage.svg') }}" alt="logo">
            </span>
            <span class="app-brand-text demo menu-text fw-bold text-primary">Paket Wisata</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="align-middle ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="align-middle ti ti-x d-block d-xl-none ti-md"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="py-1 menu-inner">
        <!-- Dashboards -->
        <li class="menu-item">
            @if (Auth::user()->role == 'admin')
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-layout-dashboard"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            @else
                <a href="{{ route('agen.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-layout-dashboard"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            @endif
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
                            <a href="{{ route('all.bookings') }}" class="menu-link">
                                <div data-i18n="All Booking">All Booking</div>
                            </a>
                        </li>
                    @endif
                </ul>
                <!-- Booking Manage End -->
            </li>
        @endif
        <!-- Booking End -->

        <!-- Accountings -->
        @if (Auth::user()->can('accounting.menu'))
            <li class="menu-header small">
                <span class="menu-header-text" data-i18n="Accounting">Accounting</span>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-abacus"></i>
                    <div data-i18n="Accounting">Accounting</div>
                </a>
                <!-- Accounting -->
                <ul class="menu-sub">
                    @if (Auth::user()->can('accounting.list'))
                        <li class="menu-item">
                            <a href="{{ route('all.accounts') }}" class="menu-link">
                                <div data-i18n="Account List">Account List</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->can('accounting.list'))
                        <li class="menu-item">
                            <a href="{{ route('all.expenses') }}" class="menu-link">
                                <div data-i18n="Expense Trip">Expense Trip</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->can('accounting.list'))
                        <li class="menu-item">
                            <a href="{{ route('ledger.index') }}" class="menu-link">
                                <div data-i18n="Ledger Book">Ledger Book</div>
                            </a>
                        </li>
                    @endif
                </ul>
                <!-- Accounting End -->
            </li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-brand-deliveroo"></i>
                    <div data-i18n="Supplier">Supplier</div>
                </a>
                <!-- Supplier -->
                <ul class="menu-sub">
                    @if (Auth::user()->can('accounting.list'))
                        <li class="menu-item">
                            <a href="{{ route('all.suppliers') }}" class="menu-link">
                                <div data-i18n="All Supplier">All Supplier</div>
                            </a>
                        </li>
                    @endif
                </ul>
                <!-- Supplier End -->

            </li>
            @if (Auth::user()->can('invoice.menu'))
                <!-- Invoice -->
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons ti ti-file-dollar"></i>
                        <div data-i18n="Invoice Manage">Invoice Manage</div>
                    </a>
                    <!-- Invoice Manage -->
                    <ul class="menu-sub">
                        @if (Auth::user()->can('invoice.list'))
                            <li class="menu-item">
                                <a href="{{ route('all.invoices') }}" class="menu-link">
                                    <div data-i18n="All Invoices">All Invoices</div>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('invoice.add'))
                            <li class="menu-item">
                                <a href="{{ route('add.invoice') }}" class="menu-link">
                                    <div data-i18n="Add Invoice">All Invoice</div>
                                </a>
                            </li>
                        @endif
                    </ul>
                    <!-- Invoice Manage End -->
                </li>
                <!-- Invoice End -->
            @endif
        @endif
        <!-- Accountings End -->

        <!-- Rents -->
        @if (Auth::user()->can('rent.menu'))
            <li class="menu-header small">
                <span class="menu-header-text" data-i18n="Rents">Rents</span>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-car-suv"></i>
                    <div data-i18n="Rent Manage">Rent Manage</div>
                </a>
                <!-- Rents Manage -->
                <ul class="menu-sub">
                    @if (Auth::user()->can('rent.list'))
                        <li class="menu-item">
                            <a href="{{ route('all.rents') }}" class="menu-link">
                                <div data-i18n="All Rents">All Rents</div>
                            </a>
                        </li>
                    @endif
                </ul>
                <!-- Rents Manage End -->
            </li>
        @endif
        <!-- Rents End -->

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
            @if (Auth::user()->can('package.generate'))
                <li class="menu-item">
                    <a href="{{ route('calculate.custom.package') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-adjustments-cog"></i>
                        <div data-i18n="Custom Package">Custom Package</div>
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
                        @if (Auth::user()->can('package.list'))
                            <li class="menu-item">
                                <a href="{{ route('all.packages') }}" class="menu-link">
                                    <div data-i18n="One Day">One Day</div>
                                </a>
                            </li>
                        @endif
                    </ul>
                    <!-- One Day End -->

                    <!-- Two Day -->
                    <ul class="menu-sub">
                        @if (Auth::user()->can('package.list'))
                            <li class="menu-item">
                                <a href="{{ route('all.twoday.packages') }}" class="menu-link">
                                    <div data-i18n="Two Day">Two Day</div>
                                </a>
                            </li>
                        @endif
                    </ul>
                    <!-- Two Day End -->

                    <!-- Three Day -->
                    <ul class="menu-sub">
                        @if (Auth::user()->can('package.list'))
                            <li class="menu-item">
                                <a href="{{ route('all.threeday.packages') }}" class="menu-link">
                                    <div data-i18n="Three Day">Three Day</div>
                                </a>
                            </li>
                        @endif
                    </ul>
                    <!-- Three Day End -->

                    <!-- Four Day -->
                    <ul class="menu-sub">
                        @if (Auth::user()->can('package.list'))
                            <li class="menu-item">
                                <a href="{{ route('all.fourday.packages') }}" class="menu-link">
                                    <div data-i18n="Four Day">Four Day</div>
                                </a>
                            </li>
                        @endif
                    </ul>
                    <!-- Four Day End -->

                    <!-- Custom Tour -->
                    <ul class="menu-sub">
                        @if (Auth::user()->can('package.list'))
                            <li class="menu-item">
                                <a href="{{ route('all.custom.package') }}" class="menu-link">
                                    <div data-i18n="Custom Tour">Custom Tour</div>
                                </a>
                            </li>
                        @endif
                    </ul>
                    <!-- Custom End -->
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
