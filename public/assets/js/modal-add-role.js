/**
 * Add new role Modal JS
 */

'use strict';

document.addEventListener('DOMContentLoaded', function () {
    // Tangkap tombol yang memicu modal
    const addRoleButton = document.querySelector('[data-bs-target="#addRoleModal"]');

    addRoleButton.addEventListener('click', function () {
        // Reset form modal setiap kali tombol di-klik
        const form = document.getElementById('addRoleForm');
        form.reset();
    });
});

