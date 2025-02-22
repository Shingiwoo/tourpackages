<!doctype html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="/assets/" data-template="vertical-menu-template" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Tour Packages - Dashboard </title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css') }} " />

    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }} " />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }} " />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }} " />


    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/ui-carousel.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-calendar.css') }}" />



    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('admin.body.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Header -->
                @include('admin.body.header')
                <!-- / Header -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('admin')
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('admin.body.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>


    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/app-academy-dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/forms-extras.js') }}"></script>
    <script src="{{ asset('assets/js/ui-popover.js') }}"></script>
    <script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/js/forms-selects.js') }}"></script>
    <script src="{{ asset('assets/js/ui-carousel.js') }}"></script>
    <script src="{{ asset('assets/js/modal-service-fee.js') }}"></script>
    <script src="{{ asset('assets/js/forms-pickers.js') }}"></script>
    <script src="{{ asset('assets/js/form-data.js') }}"></script>
    <script src="{{ asset('assets/js/form-modal-booking.js') }}"></script>
    <script src="{{ asset('assets/js/form-data-booking.js') }}"></script>
    <script src="{{ asset('assets/js/offcanvas-send-invoice.js') }}"></script>
    <script src="{{ asset('assets/js/app-invoice-add.js') }}"></script>
    <script src="{{ asset('assets/js/app-calendar-events.js') }}"></script>
    <script src="{{ asset('assets/js/app-calendar.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    break;

                case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    break;

                case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    break;

                case 'error':
                    toastr.error(" {{ Session::get('message') }} ");
                    break;
            }
        @endif
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-bs-target="#showData"]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const customId = this.getAttribute('data-id');
                    fetch(`/get-custom-package/${customId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const prices = data.prices;

                                document.querySelector('#showData .package-name').textContent =
                                    prices.package_name;
                                document.querySelector('#showData .duration').textContent =
                                    prices.DurationPackage;
                                document.querySelector('#showData .night').textContent = prices
                                    .night;

                                const destinationList = document.querySelector(
                                    '#showData .list-group.destinations');
                                destinationList.innerHTML = '';
                                prices.destinationNames.forEach(name => {
                                    destinationList.innerHTML +=
                                        `<li class="list-group-item">- ${name}</li>`;
                                });

                                const facilityList = document.querySelector(
                                    '#showData .list-group.facilities');
                                facilityList.innerHTML = '';
                                prices.facilityNames.forEach(name => {
                                    facilityList.innerHTML +=
                                        `<li class="list-group-item">- ${name}</li>`;
                                });

                                document.querySelector('#showData .perperso-cost').textContent =
                                    `Rp ${prices.costPerPerson.toLocaleString('id-ID')}`;
                                document.querySelector('#showData .total-user').textContent =
                                    `${prices.participants} orang`;
                                document.querySelector('#showData .total-cost').textContent =
                                    `Rp ${prices.totalCost.toLocaleString('id-ID')}`;
                                document.querySelector('#showData .down-payment').textContent =
                                    `Rp ${prices.downPayment.toLocaleString('id-ID')}`;
                                document.querySelector('#showData .remaining-costs')
                                    .textContent =
                                    `Rp ${prices.remainingCosts.toLocaleString('id-ID')}`;

                                document.querySelector('#showData .child-cost').textContent =
                                    `Rp ${prices.childCost.toLocaleString('id-ID')}`;
                                document.querySelector('#showData .additional-cost-wna')
                                    .textContent =
                                    `Rp ${prices.additionalCostWna.toLocaleString('id-ID')}`;
                            }
                        });
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pilih semua tombol yang memicu modal
            const showButtons = document.querySelectorAll('[data-bs-target="#viewBookingData"]');

            showButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Ambil data dari atribut data-*
                    const codeBooking = this.getAttribute('data-codeBooking');
                    const agenName = this.getAttribute('data-agenName');
                    const bookingType = this.getAttribute('data-bookingType');
                    const bookingStatus = this.getAttribute('data-bookingStatus');
                    const clientName = this.getAttribute('data-clientName');
                    const startDate = this.getAttribute('data-startDate');
                    const endDate = this.getAttribute('data-endDate');
                    const pricePerPerson = this.getAttribute('data-pricePerperson') || 0;
                    const totalUser = this.getAttribute('data-totalUser');
                    const totalCost = this.getAttribute('data-totalCost');
                    const downPayment = this.getAttribute('data-downPayment');
                    const remainingCost = this.getAttribute('data-remainingCost');
                    const kidsCost = pricePerPerson * 0.3;
                    const wnaCost = pricePerPerson * 0.44;


                    // Set data ke modal
                    document.getElementById('booking-code').innerText = codeBooking;
                    document.getElementById('agen-name').innerText = agenName;
                    document.getElementById('booking-type').innerText = bookingType;
                    document.getElementById('booking-status').innerText = bookingStatus;
                    document.getElementById('client-name').innerText = clientName;
                    document.getElementById('start-date').innerText = startDate;
                    document.getElementById('end-date').innerText = endDate;
                    document.getElementById('price-per-person').innerText =
                        `Rp ${parseInt(pricePerPerson).toLocaleString('id-ID')}`;
                    document.getElementById('total-user').innerText = `${totalUser} orang`;
                    document.getElementById('total-cost').innerText =
                        `Rp ${parseInt(totalCost).toLocaleString('id-ID')}`;
                    document.getElementById('down-payment').innerText =
                        `Rp ${parseInt(downPayment).toLocaleString('id-ID')}`;
                    document.getElementById('remaining-cost').innerText =
                        `Rp ${parseInt(remainingCost).toLocaleString('id-ID')}`;
                    document.getElementById('child-cost').innerText =
                        `Rp ${kidsCost.toLocaleString('id-ID')}`;
                    document.getElementById('additional-cost-wna').innerText =
                        `Rp ${wnaCost.toLocaleString('id-ID')}`;
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".dropdown-notifications-read").forEach(item => {
                item.addEventListener("click", function(event) {
                    event.preventDefault();

                    let notificationId = this.getAttribute("data-id");
                    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                        "content");

                    if (!csrfToken) {
                        console.error("CSRF token tidak ditemukan!");
                        alert("Terjadi kesalahan, coba refresh halaman.");
                        return;
                    }

                    fetch(`${window.location.origin}/notifications/${notificationId}/mark-read`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                                "Content-Type": "application/json",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                id: notificationId
                            }),
                            credentials: "same-origin"
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw new Error(err.message || response.statusText);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                let notifElement = document.querySelector(
                                    `[data-id="${notificationId}"]`);
                                if (notifElement) {
                                    notifElement.remove(); // Hapus dari tampilan
                                }
                            } else {
                                console.error("Gagal menandai notifikasi:", data.message);
                                alert("Gagal menandai notifikasi: " + data.message);
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            alert("Terjadi kesalahan: " + error.message);
                        });
                });
            });


            document.getElementById("markAllRead").addEventListener("click", function() {
                let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

                if (!csrfToken) {
                    console.error("CSRF token tidak ditemukan!");
                    alert("Terjadi kesalahan, coba refresh halaman.");
                    return;
                }

                fetch('/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(text);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            document.getElementById("notification-count").textContent = "0 New";

                        } else {
                            alert("Failed to mark notifications as read.");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert("Terjadi kesalahan: " + error.message);
                    });
            });
        });
    </script>
</body>

</html>
