/**
 * Service Fee
 */

'use strict';

document.addEventListener('DOMContentLoaded', function () {
    // Pilih semua tombol dengan atribut data-bs-toggle dan target modal
    const editButtons = document.querySelectorAll('[data-bs-target="#enableOTP"]');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Ambil data dari atribut data-*
            const id = this.getAttribute('data-id');
            const duration = this.getAttribute('data-duration');
            const mark = this.getAttribute('data-mark');

            // Set nilai pada form modal
            document.getElementById('duration').value = duration;
            document.getElementById('mark').value = mark;

            // Set action URL dengan ID yang diambil
            const modalForm = document.getElementById('enableOTPForm');
            modalForm.setAttribute('action', `/update/service-fee/${id}`);
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Pilih semua tombol dengan atribut data-bs-toggle dan target modal
    const editButtons = document.querySelectorAll('[data-bs-target="#enableCrew"]');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Ambil data dari atribut data-*
            const id = this.getAttribute('data-id');
            const minUser = this.getAttribute('data-minUser');
            const maxUser = this.getAttribute('data-maxUser');
            const totalCrew = this.getAttribute('data-totalCrew');

            // Set nilai pada form modal
            document.getElementById('min_user').value = minUser;
            document.getElementById('max_user').value = maxUser;
            document.getElementById('total_crew').value = totalCrew;

            // Set action URL dengan ID yang diambil
            const modalForm = document.getElementById('enableCrewForm');
            modalForm.setAttribute('action', `/update/crew/${id}`);
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Pilih semua tombol dengan atribut data-bs-toggle dan target modal
    const editButtons = document.querySelectorAll('[data-bs-target="#enableMeal"]');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Ambil data dari atribut data-*
            const id = this.getAttribute('data-id');
            const mealPrice = this.getAttribute('data-mealPrice');
            const mealType = this.getAttribute('data-mealType');
            const mealDuration = this.getAttribute('data-mealDuration');
            const mealTotal = this.getAttribute('data-mealTotal');
            const city = this.getAttribute('data-city');

            // Set nilai pada form modal
            document.getElementById('meal-Price').value = mealPrice;
            document.getElementById('meal-type').value = mealType;
            document.getElementById('meal-duration').value = mealDuration;
            document.getElementById('meal-total').value = mealTotal;
            document.getElementById('city-district').value = city;

            // Set action URL dengan ID yang diambil
            const modalForm = document.getElementById('enableMealForm');
            modalForm.setAttribute('action', `/update/meal/${id}`);
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Pilih semua tombol dengan atribut data-bs-toggle dan target modal
    const editButtons = document.querySelectorAll('[data-bs-target="#enableReserveFee"]');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Ambil data dari atribut data-*
            const id = this.getAttribute('data-id');
            const priceReserveFee = this.getAttribute('data-reservefeePrice');
            const ReserveFeeDuration = this.getAttribute('data-ReserveFeeDuration');
            const ReserveFeeMinUser = this.getAttribute('data-ReserveFeeMinUser');
            const ReserveFeeMaxUser = this.getAttribute('data-ReserveFeeMaxUser');

            // Set nilai pada form modal
            document.getElementById('reservefee-Price').value = priceReserveFee;
            document.getElementById('reservefee-duration').value = ReserveFeeDuration;
            document.getElementById('reservefee-MinUser').value = ReserveFeeMinUser;
            document.getElementById('reservefee-MaxUser').value = ReserveFeeMaxUser;

            // Set action URL dengan ID yang diambil
            const modalForm = document.getElementById('enableReserveFeeForm');
            modalForm.setAttribute('action', `/update/reservefee/${id}`);
        });
    });
});
