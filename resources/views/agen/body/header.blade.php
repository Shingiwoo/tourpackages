<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Style Switcher -->
            <li class="nav-item dropdown-style-switcher dropdown">
                <a class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
                    href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="ti ti-md"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <span class="align-middle"><i class="ti ti-sun ti-md me-3"></i>Light</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                            <span class="align-middle"><i class="ti ti-moon-stars ti-md me-3"></i>Dark</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                            <span class="align-middle"><i
                                    class="ti ti-device-desktop-analytics ti-md me-3"></i>System</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- / Style Switcher-->

            @php
                $ncount = Auth::user()->unreadNotifications()->count();
            @endphp
            <!-- Notification -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                <a class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
                    href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                    aria-expanded="false">
                    <span class="position-relative">
                        <i class="ti ti-bell ti-md"></i>
                        @if ($ncount > 0)
                            <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                        @else
                            <span class="badge rounded-pill bg-success badge-dot badge-notifications border"></span>
                        @endif
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end p-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h6 class="mb-0 me-auto">Notification</h6>
                            <div class="d-flex align-items-center h6 mb-0">
                                <span class="badge bg-label-primary me-2" id="notification-count">{{ $ncount }}
                                    New</span>
                                <a href="javascript:void(0)"
                                    class="btn btn-text-secondary rounded-pill btn-icon dropdown-notifications-all"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read"
                                    id="markAllAsRead">
                                    <i class="ti ti-mail-opened text-heading"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container">
                        <ul class="list-group list-group-flush">
                            @php
                                $user = Auth::user();
                            @endphp

                            @forelse ($user->unreadNotifications as $notification)
                                <li class="dropdown-notifications-list scrollable-container"
                                    data-id="{{ $notification->id }}">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                                        <span class="avatar-initial rounded-circle bg-label-success">
                                                            <i class="ti ti-shopping-cart"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 dropdown-notifications-actions">
                                                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                                                        data-id="{{ $notification->id }}">
                                                        <h6 class="mb-1 small">Whoo! You have new booking 🛒</h6>
                                                        <small
                                                            class="mb-1 d-block text-body">{{ $notification->data['message'] }}</small>
                                                        <small
                                                            class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            @empty
                                <li class="dropdown-notifications-list scrollable-container">
                                    <p class="text-center text-muted mt-3">Tidak ada notifikasi baru</p>
                                </li>
                            @endforelse
                        </ul>
                    </li>
                    <li class="border-top">
                        <div class="d-grid p-4">
                            <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
                                <small class="align-middle">View all notifications</small>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
            <!--/ Notification -->

            @php
                $id = Auth::user()->id;
                $profileData = App\Models\User::find($id);
                $role = Auth::user()->role;
            @endphp

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ !empty($profileData->photo) ? asset('storage/profile/' . $profileData->photo) : asset('assets/img/avatars/no_image.jpg') }}"
                            alt class="rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item mt-0" href="{{ route('agen.profile') }}">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2">
                                    <div class="avatar avatar-online">
                                        <img src="{{ !empty($profileData->photo) ? asset('storage/profile/' . $profileData->photo) : asset('assets/img/avatars/no_image.jpg') }}"
                                            alt class="rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $profileData->name }}</h6>
                                    <small class="text-muted">{{ $profileData->username }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1 mx-n2"></div>
                    </li>
                    <li>
                        @if ($profileData->role == 'admin')
                            <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                <i class="ti ti-user me-3 ti-md"></i><span class="align-middle">My Profile</span>
                            </a>
                        @else
                            <a class="dropdown-item" href="{{ route('agen.profile') }}">
                                <i class="ti ti-user me-3 ti-md"></i><span class="align-middle">My Profile</span>
                            </a>
                        @endif
                    </li>
                    <li>
                        @if ($profileData->role == 'admin')
                            <a class="dropdown-item" href="{{ route('admin.change.password') }}">
                                <i class="ti ti-settings me-3 ti-md"></i><span class="align-middle">Settings</span>
                            </a>
                        @else
                            <a class="dropdown-item" href="{{ route('agen.change.password') }}">
                                <i class="ti ti-settings me-3 ti-md"></i><span class="align-middle">Settings</span>
                            </a>
                        @endif
                    </li>
                    <li>
                        <a class="dropdown-item" href="pages-account-settings-billing.html">
                            <span class="d-flex align-items-center align-middle">
                                <i class="flex-shrink-0 ti ti-file-dollar me-3 ti-md"></i><span
                                    class="flex-grow-1 align-middle">Billing</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1 mx-n2"></div>
                    </li>
                    <li>
                        <div class="d-grid px-2 pt-2 pb-1">
                            @if ($profileData->role == 'admin')
                                <a class="btn btn-sm btn-danger d-flex" href="{{ route('admin.logout') }}">
                                    <small class="align-middle">Logout</small>
                                    <i class="ti ti-logout ms-2 ti-14px"></i>
                                </a>
                            @else
                                <a class="btn btn-sm btn-danger d-flex" href="{{ route('agen.logout') }}">
                                    <small class="align-middle">Logout</small>
                                    <i class="ti ti-logout ms-2 ti-14px"></i>
                                </a>
                            @endif
                        </div>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>


<script>
    function markNotificationAsRead(notificationId) {
        fetch('/mark-notification-as-read/' + notificationId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('notification-count').textContent = data.count;
            })
            .catch(error => {
                console.log('Error', error);
            });
    }
</script>
