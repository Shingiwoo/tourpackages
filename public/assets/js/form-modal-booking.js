$(document).ready(function () {
    // Fungsi untuk menangani perubahan pada tipe paket
    function handlePackageTypeChange(inputValue) {
        const hotelContainer = $("#hotel_type_container");
        const hotelTypeInput = $("#modal_hotelType");
        const totalUserContainer = $("#total_user_container");
        const totalUserInput = $("#modalTotalUser");
        const mealContainer = $("#meal_container");
        const mealUserInput = $("#mealStatus");

        // Reset semua elemen terlebih dahulu
        hotelContainer.hide();
        hotelTypeInput.prop("required", false).prop("disabled", true);
        totalUserContainer.hide();
        totalUserInput.prop("required", false).prop("disabled", true);
        mealContainer.hide();
        mealUserInput.prop("required", false).prop("disabled", true);

        // Tampilkan dan aktifkan elemen sesuai dengan tipe paket
        if (inputValue === "oneday") {
            totalUserContainer.show();
            totalUserInput.prop("required", true).prop("disabled", false);
            mealContainer.show();
            mealUserInput.prop("required", true).prop("disabled", false);
        } else if (inputValue === "custom") {
            // Tidak melakukan apapun, semua elemen tetap tersembunyi
        } else {
            hotelContainer.show();
            hotelTypeInput.prop("required", true).prop("disabled", false);
            totalUserContainer.show();
            totalUserInput.prop("required", true).prop("disabled", false);
            mealContainer.show();
            mealUserInput.prop("required", true).prop("disabled", false);
        }
    }

    // Event handler untuk perubahan pada #modal_packageType
    $("#modal_packageType").change(function () {
        const selectedValue = $(this).val();
        handlePackageTypeChange(selectedValue);
    });

    // Trigger perubahan saat halaman dimuat
    $("#modal_packageType").trigger("change");

    // Event handler ketika modal booking muncul
    $("#bookingModal").on("shown.bs.modal", function (e) {
        const button = $(e.relatedTarget);
        const packageType = button.data("type");

        // Set nilai pada #modal_packageType jika diperlukan
        $("#modal_packageType").val(packageType).trigger("change");
    });

    // Modal untuk detail booking
    $('#example').on('click', '.btn-warning', function () {
        var id = $(this).data('id');
        $.ajax({
            url: '/booking/details/' + id,
            type: 'GET',
            success: function (response) {
                $('#showBookData .modal-body').html(response.html);
            },
            error: function () {
                alert('Gagal memuat detail booking.');
            },
        });
    });
});
