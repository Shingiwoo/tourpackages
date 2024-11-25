/**
 * Sweet Alerts
 */

'use strict';
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-confirm').forEach(function (element) {
        element.addEventListener('click', function () {
            const url = this.dataset.url;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // Redirect ke URL delete
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    toastr["info"]("Deletion cancelled");
                }
            });
        });
    });
});
