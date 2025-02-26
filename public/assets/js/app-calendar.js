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
            currentEvents = events, // Assign app-calendar-events.js file events (assume events from API) to currentEvents (browser store/object) to manage and update calender events
            isFormValid = false,
            inlineCalInstance;

        // Init event Offcanvas
        const bsAddEventSidebar = new bootstrap.Offcanvas(addEventSidebar);

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
            eventToUpdate = info.event;

            bookingCode.value = eventToUpdate.title;
            start.setDate(eventToUpdate.start, true, "Y-m-d");
            eventToUpdate.allDay === true
                ? (allDaySwitch.checked = true)
                : (allDaySwitch.checked = false);
            eventToUpdate.end !== null
                ? end.setDate(eventToUpdate.end, true, "Y-m-d")
                : end.setDate(eventToUpdate.start, true, "Y-m-d");
            eventDescription.value =
                eventToUpdate.extendedProps.description ||
                "No description available.";
            eventLabel.val(eventToUpdate.extendedProps.type).trigger("change");

            // Ambil data booking dari event
            const bookingData = eventToUpdate.extendedProps;

            // Isi data ke dalam modal
            document.getElementById("booking-code").textContent =
                bookingData.code_booking || "-";
            document.getElementById("agen-name").textContent =
                bookingData.agen_name || "-";
            document.getElementById("booking-type").textContent =
                bookingData.type || "-";
            document.getElementById("booking-status").textContent =
                bookingData.status || "-";
            document.getElementById("client-name").textContent =
                bookingData.client_name || "-";
            document.getElementById("start-date").textContent =
                moment(bookingData.start_date).format("YYYY-MM-DD") || "-";
            document.getElementById("end-date").textContent =
                moment(bookingData.end_date).format("YYYY-MM-DD") || "-";
            document.getElementById("price-per-person").textContent =
                formatCurrency(bookingData.price_person) || "-";
            document.getElementById("total-user").textContent =
                bookingData.total_user || "-";
            document.getElementById("total-cost").textContent =
                formatCurrency(bookingData.total_price) || "-";
            document.getElementById("down-payment").textContent =
                formatCurrency(bookingData.down_payment) || "-";
            document.getElementById("remaining-cost").textContent =
                formatCurrency(bookingData.remaining_costs) || "-";

            // Tampilkan modal
            const modal = new bootstrap.Modal(
                document.getElementById("viewBookingData")
            );
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
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then((data) => {
                    //console.log("Data from backend:", data);
                    const formattedEvents = data.map(booking => ({
                        id: booking.id,
                        title: booking.title, // Gunakan code_booking sebagai title
                        start: booking.start,
                        end: moment(booking.end).add(1, 'days').format("YYYY-MM-DD"),
                        extendedProps: {
                            code_booking: booking.extendedProps.code_booking,
                            agen_name: booking.extendedProps.agen_name,
                            type: booking.extendedProps.type,
                            status: booking.extendedProps.status,
                            client_name: booking.extendedProps.client_name,
                            start_date: booking.start,
                            end_date: booking.end,
                            price_person: booking.extendedProps.price_person,
                            total_user: booking.extendedProps.total_user,
                            total_price: booking.extendedProps.total_price,
                            down_payment: booking.extendedProps.down_payment,
                            remaining_costs: booking.extendedProps.remaining_costs,
                        },
                    }));
                    //console.log("Formatted events:", formattedEvents); // Debugging

                    let selectedTypes = selectedCalendars();
                    let selectedEvents = formattedEvents.filter((event) =>
                        selectedTypes.includes(
                            event.extendedProps.type.toLowerCase()
                        )
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
            dateClick: function (info) {
                let date = moment(info.date).format("YYYY-MM-DD");
                resetValues();
                bsAddEventSidebar.show();
                if (offcanvasTitle) {
                    offcanvasTitle.innerHTML = "Add Event";
                }
                btnSubmit.innerHTML = "Add";
                btnSubmit.classList.remove("btn-update-event");
                btnSubmit.classList.add("btn-add-event");
                eventStartDate.value = date;
                eventEndDate.value = date;
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

        // Add Event
        // ------------------------------------------------
        function addEvent(eventData) {
            // ? Add new event data to current events object and refetch it to display on calender
            // ? You can write below code to AJAX call success response

            currentEvents.push(eventData);
            calendar.refetchEvents();

            // ? To add event directly to calender (won't update currentEvents object)
            // calendar.addEvent(eventData);
        }

        // Update Event
        // ------------------------------------------------
        function updateEvent(eventData) {
            // ? Update existing event data to current events object and refetch it to display on calender
            // ? You can write below code to AJAX call success response
            eventData.id = parseInt(eventData.id);
            currentEvents[
                currentEvents.findIndex((el) => el.id === eventData.id)
            ] = eventData; // Update event by id
            calendar.refetchEvents();

            // ? To update event directly to calender (won't update currentEvents object)
            // let propsToUpdate = ['id', 'title', 'url'];
            // let extendedPropsToUpdate = ['calendar', 'guests', 'location', 'description'];

            // updateEventInCalendar(eventData, propsToUpdate, extendedPropsToUpdate);
        }

        // Remove Event
        // ------------------------------------------------

        function removeEvent(eventId) {
            // ? Delete existing event data to current events object and refetch it to display on calender
            // ? You can write below code to AJAX call success response
            currentEvents = currentEvents.filter(function (event) {
                return event.id != eventId;
            });
            calendar.refetchEvents();

            // ? To delete event directly to calender (won't update currentEvents object)
            // removeEventInCalendar(eventId);
        }

        // (Update Event In Calendar (UI Only)
        // ------------------------------------------------
        const updateEventInCalendar = (
            updatedEventData,
            propsToUpdate,
            extendedPropsToUpdate
        ) => {
            const existingEvent = calendar.getEventById(updatedEventData.id);

            // --- Set event properties except date related ----- //
            // ? Docs: https://fullcalendar.io/docs/Event-setProp
            // dateRelatedProps => ['start', 'end', 'allDay']
            // eslint-disable-next-line no-plusplus
            for (var index = 0; index < propsToUpdate.length; index++) {
                var propName = propsToUpdate[index];
                existingEvent.setProp(propName, updatedEventData[propName]);
            }

            // --- Set date related props ----- //
            // ? Docs: https://fullcalendar.io/docs/Event-setDates
            existingEvent.setDates(
                updatedEventData.start,
                updatedEventData.end,
                {
                    allDay: updatedEventData.allDay,
                }
            );

            // --- Set event's extendedProps ----- //
            // ? Docs: https://fullcalendar.io/docs/Event-setExtendedProp
            // eslint-disable-next-line no-plusplus
            for (var index = 0; index < extendedPropsToUpdate.length; index++) {
                var propName = extendedPropsToUpdate[index];
                existingEvent.setExtendedProp(
                    propName,
                    updatedEventData.extendedProps[propName]
                );
            }
        };

        // Remove Event In Calendar (UI Only)
        // ------------------------------------------------
        function removeEventInCalendar(eventId) {
            calendar.getEventById(eventId).remove();
        }

        // Add new event
        // ------------------------------------------------
        btnSubmit.addEventListener("click", (e) => {
            if (btnSubmit.classList.contains("btn-add-event")) {
                if (isFormValid) {
                    let newEvent = {
                        id: calendar.getEvents().length + 1,
                        title: eventTitle.value,
                        start: eventStartDate.value,
                        end: eventEndDate.value,
                        startStr: eventStartDate.value,
                        endStr: eventEndDate.value,
                        display: "block",
                        extendedProps: {
                            location: eventLocation.value,
                            guests: eventGuests.val(),
                            calendar: eventLabel.val(),
                            description: eventDescription.value,
                        },
                    };
                    if (eventUrl.value) {
                        newEvent.url = eventUrl.value;
                    }
                    if (allDaySwitch.checked) {
                        newEvent.allDay = true;
                    }
                    addEvent(newEvent);
                    bsAddEventSidebar.hide();
                }
            } else {
                // Update event
                // ------------------------------------------------
                if (isFormValid) {
                    let eventData = {
                        id: eventToUpdate.id,
                        title: eventTitle.value,
                        start: eventStartDate.value,
                        end: eventEndDate.value,
                        url: eventUrl.value,
                        extendedProps: {
                            location: eventLocation.value,
                            guests: eventGuests.val(),
                            calendar: eventLabel.val(),
                            description: eventDescription.value,
                        },
                        display: "block",
                        allDay: allDaySwitch.checked ? true : false,
                    };

                    updateEvent(eventData);
                    bsAddEventSidebar.hide();
                }
            }
        });

        // Reset event form inputs values
        // ------------------------------------------------
        function resetValues() {
            eventEndDate.value = "";
            eventStartDate.value = "";
            bookingCode.value = "";
            allDaySwitch.checked = false;
            eventDescription.value = "";
        }

        // When modal hides reset input values
        addEventSidebar.addEventListener("hidden.bs.offcanvas", function () {
            resetValues();
        });

        // Hide left sidebar if the right sidebar is open
        btnToggleSidebar.addEventListener("click", (e) => {
            if (offcanvasTitle) {
                offcanvasTitle.innerHTML = "Add Event";
            }
            btnSubmit.innerHTML = "Add";
            btnSubmit.classList.remove("btn-update-event");
            btnSubmit.classList.add("btn-add-event");
            appCalendarSidebar.classList.remove("show");
            appOverlay.classList.remove("show");
        });

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
        inlineCalInstance.config.onChange.push(function (date) {
            calendar.changeView(
                calendar.view.type,
                moment(date[0]).format("YYYY-MM-DD")
            );
            modifyToggler();
            appCalendarSidebar.classList.remove("show");
            appOverlay.classList.remove("show");
        });
    })();
});
