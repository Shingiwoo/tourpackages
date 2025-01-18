// Form Booking All Package
document.addEventListener('DOMContentLoaded', function () {
    const bookingButtons = document.querySelectorAll('.dropdown-item.text-success');

    bookingButtons.forEach(button => {
        button.addEventListener('click', function () {
            const packageId = this.dataset.id; // Dapatkan ID paket
            document.getElementById('packageId').value = packageId; // Masukkan ke form
        });
    });
});

// Form Show Package
document.addEventListener('DOMContentLoaded', function () {
    const bookingButtons = document.querySelectorAll('.btn.btn-label-success');

    bookingButtons.forEach(button => {
        button.addEventListener('click', function () {
            const packageId = this.dataset.id; // Ambil nilai ID paket dari atribut data-id
            const packageType = this.dataset.type; // Ambil nilai tipe paket dari atribut data-type

            // Set nilai input hidden untuk package ID
            document.getElementById('package_Id').value = packageId;

            // Set nilai input text untuk package type
            document.getElementById('modalShow_packageType').value = packageType;
        });
    });
});
