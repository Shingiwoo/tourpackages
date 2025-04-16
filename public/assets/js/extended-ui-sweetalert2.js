/**
 * Sweet Alerts
 */

'use strict';
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-confirm');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const id = this.getAttribute('data-id');
            const url = this.getAttribute('data-url');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim permintaan DELETE
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'The item has been deleted.',
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            }).then(() => {
                                location.reload(); // Refresh halaman setelah sukses
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to delete the item.',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An unexpected error occurred.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    });
                }
            });
        });
    });
});
