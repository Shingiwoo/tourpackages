/**
 * Add Permission Modal JS
 */

'use strict';

// Add permission form validation
document.addEventListener('DOMContentLoaded', function () {
    // Tangkap tombol yang memicu modal
    const addPermissionButton = document.querySelector('[data-bs-target="#addPermissionModal"]');

    addPermissionButton.addEventListener('click', function () {
        // Reset form modal setiap kali tombol di-klik
        const form = document.getElementById('addPermissionForm');
        form.reset();
    });
});

