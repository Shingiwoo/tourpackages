// Form All Package For booking
$(document).ready(function () {
    $("#modal_packageType").change(function () {
        var selectedValue = $(this).val();
        var hotelContainer = $("#hotel_type_container");
        var hotelTypeInput = $("#modal_hotelType");

        if (selectedValue === "oneday") {
            hotelContainer.hide();
            hotelTypeInput.prop("required", false); // Hapus atribut required
        } else {
            hotelContainer.show();
            hotelTypeInput.prop("required", true); // Tambahkan atribut required
        }
    });

    // Trigger the change event on page load for initial state
    $("#modal_packageType").trigger("change");
});

$(document).ready(function () {
    // Tangkap event 'shown.bs.modal' untuk memastikan modal sudah terbuka
    $('#bookingModal').on('shown.bs.modal', function (e) {
        // Dapatkan data-type dari tombol yang membuka modal
        var button = $(e.relatedTarget); // Tombol yang memicu modal
        var packageType = button.data('type'); // Ambil nilai data-type dari tombol

        // Set nilai pada input modalShow_packageType
        $('#modalShow_packageType').val(packageType);

        // Jalankan fungsi untuk menangani perubahan tipe paket
        handlePackageTypeChange();
    });

    // Fungsi untuk menangani perubahan pada modalShow_packageType
    function handlePackageTypeChange() {
        var inputValue = $("#modalShow_packageType").val();

        var hotelContainer = $("#hotel_type_container");
        var hotelTypeInput = $("#modal_hotelType");

        if (inputValue === "oneday") {
            hotelContainer.hide();  // Sembunyikan kontainer hotel
            hotelTypeInput.prop("required", false);  // Hapus atribut required
        } else {
            hotelContainer.show();  // Tampilkan kontainer hotel
            hotelTypeInput.prop("required", true);  // Tambahkan atribut required
        }
    }
});









