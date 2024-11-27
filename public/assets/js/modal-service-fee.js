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
