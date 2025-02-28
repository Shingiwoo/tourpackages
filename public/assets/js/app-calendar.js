/**
 * App Calendar
 */

/**
 * ! If both start and end dates are same Full calendar will nullify the end date value.
 * ! Full calendar will end the event on a day before at 12:00:00AM thus, event won't extend to the end date.
 * ! We are getting events from a separate file named app-calendar-events.js. You can add or remove events from there.
 *
 **/

"use strict";

let direction = "ltr";

if (isRtl) {
    direction = "rtl";
}

document.addEventListener("DOMContentLoaded", function () {
    (function () {
        const calendarEl = document.getElementById("calendar"),
            appCalendarSidebar = document.querySelector(
                ".app-calendar-sidebar"
            ),
            addEventSidebar = document.getElementById("addEventSidebar"),
            appOverlay = document.querySelector(".app-overlay"),
            calendarsColor = {
                oneday: "danger",
                twoday: "primary",
                threeday: "warning",
                fourday: "success",
                custom: "secondary",
                rent: "info",
            },
            offcanvasTitle = document.querySelector(".offcanvas-title"),
            btnToggleSidebar = document.querySelector(".btn-toggle-sidebar"),
            btnSubmit = document.querySelector("#addEventBtn"),
            btnCancel = document.querySelector(".btn-cancel"),
            eventTitle = document.querySelector("#eventTitle"),
            eventStartDate = document.querySelector("#eventStartDate"),
            eventEndDate = document.querySelector("#eventEndDate"),
            eventLabel = $("#eventLabel"), // ! Using jquery vars due to select2 jQuery dependency
            eventLocation = document.querySelector("#eventLocation"),
            eventDescription = document.querySelector("#eventDescription"),
            allDaySwitch = document.querySelector(".allDay-switch"),
            selectAll = document.querySelector(".select-all"),
            filterInput = [].slice.call(
                document.querySelectorAll(".input-filter")
            ),
            inlineCalendar = document.querySelector(".inline-calendar");

        let eventToUpdate,
            currentEvents = [], // Assign app-calendar-events.js file events (assume events from API) to currentEvents (browser store/object) to manage and update calender events
            isFormValid = false,
            inlineCalInstance;

        // Init event Offcanvas
        let bsAddEventSidebar;
        if (addEventSidebar) {
            bsAddEventSidebar = new bootstrap.Offcanvas(addEventSidebar);
        }

        //! TODO: Update Event label and guest code to JS once select removes jQuery dependency
        // Event Label (select2)
        if (eventLabel.length) {
            function renderBadges(option) {
                if (!option.id) {
                    return option.text;
                }
                var $badge =
                    "<span class='badge badge-dot bg-" +
                    $(option.element).data("label") +
                    " me-2'> " +
                    "</span>" +
                    option.text;

                return $badge;
            }
            eventLabel.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Select value",
                dropdownParent: eventLabel.parent(),
                templateResult: renderBadges,
                templateSelection: renderBadges,
                minimumResultsForSearch: -1,
                escapeMarkup: function (es) {
                    return es;
                },
            });
        }

        // Event start (flatpicker)
        if (eventStartDate) {
            var start = eventStartDate.flatpickr({
                enableTime: true,
                altFormat: "Y-m-dTH:i:S",
                onReady: function (selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        instance.mobileInput.setAttribute("step", null);
                    }
                },
            });
        }

        // Event end (flatpicker)
        if (eventEndDate) {
            var end = eventEndDate.flatpickr({
                enableTime: true,
                altFormat: "Y-m-dTH:i:S",
                onReady: function (selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        instance.mobileInput.setAttribute("step", null);
                    }
                },
            });
        }

        // Inline sidebar calendar (flatpicker)
        if (inlineCalendar) {
            inlineCalInstance = inlineCalendar.flatpickr({
                monthSelectorType: "static",
                inline: true,
            });
        }

        function eventClick(info) {
            const event = info.event;
            const bookingData = event.extendedProps;

            // Isi data ke dalam modal
            document.getElementById("booking-code").textContent = bookingData.code_booking || "-";
            document.getElementById("agen-name").textContent = bookingData.agen_name || "-";
            document.getElementById("booking-type").textContent = bookingData.type || "-";
            document.getElementById("booking-status").textContent = bookingData.status || "-";
            document.getElementById("client-name").textContent = bookingData.client_name || "-";

            // Format tanggal
            const startDate = moment(bookingData.start_date).format("YYYY-MM-DD") || "-";
            const endDate = moment(bookingData.end_date).format("YYYY-MM-DD") || "-";

            // Logika untuk tipe "rent" (tampilkan jam) dan non-"rent" (hanya tanggal)
            if (bookingData.type.toLowerCase() === "rent") {
                // Untuk tipe "rent", tampilkan tanggal dan jam
                document.getElementById("start-date").textContent = startDate;
                document.getElementById("start-trip").textContent =
                    bookingData.start_trip ? moment(bookingData.start_trip, "HH:mm").format("HH:mm") : "-";
                document.getElementById("end-date").textContent = endDate;
                document.getElementById("end-trip").textContent =
                    bookingData.end_trip ? moment(bookingData.end_trip, "HH:mm").format("HH:mm") : "-";
            } else {
                // Untuk tipe selain "rent", hanya tampilkan tanggal
                document.getElementById("start-date").textContent = startDate;
                document.getElementById("start-trip").textContent = ""; // Kosongkan jam
                document.getElementById("end-date").textContent = endDate;
                document.getElementById("end-trip").textContent = ""; // Kosongkan jam
            }

            // Format biaya
            document.getElementById("price-per-person").textContent =
                formatCurrency(bookingData.price_person) || "-";
            document.getElementById("total-user").textContent = bookingData.total_user || "-";
            document.getElementById("total-cost").textContent =
                formatCurrency(bookingData.total_price) || "-";
            document.getElementById("down-payment").textContent =
                formatCurrency(bookingData.down_payment) || "-";
            document.getElementById("remaining-cost").textContent =
                formatCurrency(bookingData.remaining_costs) || "-";

            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById("viewBookingData"));
            modal.show();
        }

        // Fungsi untuk format mata uang
        function formatCurrency(amount) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
            }).format(amount);
        }

        // Modify sidebar toggler
        function modifyToggler() {
            const fcSidebarToggleButton = document.querySelector(
                ".fc-sidebarToggle-button"
            );
            fcSidebarToggleButton.classList.remove("fc-button-primary");
            fcSidebarToggleButton.classList.add(
                "d-lg-none",
                "d-inline-block",
                "ps-0"
            );
            while (fcSidebarToggleButton.firstChild) {
                fcSidebarToggleButton.firstChild.remove();
            }
            fcSidebarToggleButton.setAttribute("data-bs-toggle", "sidebar");
            fcSidebarToggleButton.setAttribute("data-overlay", "");
            fcSidebarToggleButton.setAttribute(
                "data-target",
                "#app-calendar-sidebar"
            );
            fcSidebarToggleButton.insertAdjacentHTML(
                "beforeend",
                '<i class="ti ti-menu-2 ti-lg text-heading"></i>'
            );
        }

        // Filter events by calender
        function selectedCalendars() {
            let selected = [],
                filterInputChecked = [].slice.call(
                    document.querySelectorAll(".input-filter:checked")
                );

            filterInputChecked.forEach((item) => {
                selected.push(item.getAttribute("data-value"));
            });

            return selected;
        }

        // --------------------------------------------------------------------------------------------------
        // AXIOS: fetchEvents
        // * This will be called by fullCalendar to fetch events. Also this can be used to refetch events.
        // --------------------------------------------------------------------------------------------------
        function fetchEvents(info, successCallback) {
            fetch("/bookings")
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    const formattedEvents = data.map((booking) => {
                        let start, end, allDay = true;

                        // Jika tipe "rent", gunakan waktu dari start_trip dan end_trip
                        if (booking.extendedProps.type.toLowerCase() === "rent") {
                            allDay = false;
                            // Gabungkan start_date dengan start_trip, dan end_date dengan end_trip
                            start = moment(
                                `${booking.extendedProps.start_date}T${booking.extendedProps.start_trip || "00:00"}`,
                                "YYYY-MM-DDTHH:mm"
                            );
                            end = moment(
                                `${booking.extendedProps.end_date}T${booking.extendedProps.end_trip || "00:00"}`,
                                "YYYY-MM-DDTHH:mm"
                            );
                        } else {
                            // Untuk tipe lain, gunakan hanya tanggal (all-day)
                            start = moment(booking.start, "YYYY-MM-DD");
                            end = moment(booking.end, "YYYY-MM-DD").add(1, "days"); // Tambah 1 hari untuk all-day
                        }

                        // Jika tanggal start dan end sama untuk all-day, set end ke null
                        if (allDay && start.isSame(end, "day")) {
                            end = null;
                        }

                        return {
                            id: booking.id,
                            title: booking.title,
                            start: start.toDate(), // Konversi ke Date object untuk FullCalendar
                            end: end ? end.toDate() : null, // Null jika tidak ada end
                            allDay: allDay,
                            extendedProps: {
                                code_booking: booking.extendedProps.code_booking,
                                agen_name: booking.extendedProps.agen_name,
                                type: booking.extendedProps.type,
                                status: booking.extendedProps.status,
                                client_name: booking.extendedProps.client_name,
                                start_date: booking.extendedProps.start_date,
                                end_date: booking.extendedProps.end_date,
                                start_trip: booking.extendedProps.start_trip,
                                end_trip: booking.extendedProps.end_trip,
                                price_person: booking.extendedProps.price_person,
                                total_user: booking.extendedProps.total_user,
                                total_price: booking.extendedProps.total_price,
                                down_payment: booking.extendedProps.down_payment,
                                remaining_costs: booking.extendedProps.remaining_costs,
                            },
                        };
                    });

                    let selectedTypes = selectedCalendars();
                    let selectedEvents = formattedEvents.filter((event) =>
                        selectedTypes.includes(event.extendedProps.type.toLowerCase())
                    );

                    successCallback(selectedEvents);
                })
                .catch((error) => {
                    console.error("Error fetching events:", error);
                });
        }

        // Init FullCalendar
        // ------------------------------------------------
        let calendar = new Calendar(calendarEl, {
            initialView: "dayGridMonth",
            events: fetchEvents,
            plugins: [
                dayGridPlugin,
                interactionPlugin,
                listPlugin,
                timegridPlugin,
            ],
            editable: false,
            dragScroll: true,
            dayMaxEvents: 4,
            eventResizableFromStart: false,
            headerToolbar: {
                start: "sidebarToggle, prev,next, title",
                end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
            },
            direction: direction,
            initialDate: new Date(),
            navLinks: true,
            eventClassNames: function ({ event: calendarEvent }) {
                const colorName =
                    calendarsColor[calendarEvent._def.extendedProps.type];
                return ["fc-event-" + colorName];
            },
            eventClick: function (info) {
                eventClick(info); // Panggil fungsi eventClick yang sudah dimodifikasi
            },
            datesSet: function () {
                modifyToggler();
            },
            viewDidMount: function () {
                modifyToggler();
            },
        });

        // Render calendar
        calendar.render();
        // Modify sidebar toggler
        modifyToggler();

        // Sidebar Toggle Btn
        if (btnToggleSidebar) {
            btnToggleSidebar.addEventListener("click", (e) => {
                btnCancel.classList.remove("d-none");
            });
        }

        // Calender filter functionality
        // ------------------------------------------------
        if (selectAll) {
            selectAll.addEventListener("click", (e) => {
                if (e.currentTarget.checked) {
                    document
                        .querySelectorAll(".input-filter")
                        .forEach((c) => (c.checked = 1));
                } else {
                    document
                        .querySelectorAll(".input-filter")
                        .forEach((c) => (c.checked = 0));
                }
                calendar.refetchEvents();
            });
        }

        if (filterInput) {
            filterInput.forEach((item) => {
                item.addEventListener("click", () => {
                    document.querySelectorAll(".input-filter:checked").length <
                    document.querySelectorAll(".input-filter").length
                        ? (selectAll.checked = false)
                        : (selectAll.checked = true);
                    calendar.refetchEvents();
                });
            });
        }

        // Jump to date on sidebar(inline) calendar change
        if (inlineCalInstance) {
            inlineCalInstance.config.onChange.push(function (date) {
                calendar.changeView(
                    calendar.view.type,
                    moment(date[0]).format("YYYY-MM-DD")
                );
                modifyToggler();
                appCalendarSidebar.classList.remove("show");
                appOverlay.classList.remove("show");
            });
        }
    })();
});
