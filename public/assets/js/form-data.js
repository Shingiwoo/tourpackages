// Form Facility Page
jQuery(function () {
    $("#type_facility")
        .on('change', function () {
            var selectedValue = $(this).val();
            var priceContainer = $("#price_facility_container");
            var maxuserContainer = $("#maxuser_facility_container");
            var priceInput = $("#price_facility");
            var maxuserInput = $("#maxuser_facility");

            if (selectedValue === "info") {
                priceContainer.hide();
                maxuserContainer.hide();

                priceInput.val(400);
                maxuserInput.val(1);
            } else {
                priceContainer.show();
                maxuserContainer.show();
            }
        })
        .trigger("change"); // Panggil trigger untuk inisialisasi saat load
});

// Form Hotel Page
jQuery(function () {
    // Ambil old value menggunakan Blade dan konversi ke format JavaScript
    var oldValue = "{{ old('hotelCapacity', $hotel->capacity ?? '') }}";

    $("#hotel_type")
        .on('change', function () {
            var selectedValue = $(this).val();
            var capacityContainer = $("#capacity").closest(".col"); // Ambil container input capacity
            var capacityInput = $("#capacity");

            // Tentukan tipe yang memunculkan input
            if (
                ["Villa", "Homestay", "Cottage", "Cabin"].includes(
                    selectedValue
                )
            ) {
                capacityContainer.show(); // Tampilkan input capacity
                if (oldValue) {
                    capacityInput.val(oldValue); // Tampilkan old value jika tersedia
                } else {
                    capacityInput.val(""); // Kosongkan input agar dapat diisi oleh pengguna
                }
            } else {
                capacityContainer.hide(); // Sembunyikan input capacity
                capacityInput.val(2); // Set nilai default ke 2
            }
        })
        .trigger("change"); // Panggil trigger untuk inisialisasi saat load
});

// Form booking Page
jQuery(function () {
    $("#modal_packageType")
        .on('change', function () {
            var selectedValue = $(this).val();
            var hotelContainer = $("#hotel_type_container");

            if (selectedValue === "oneday") {
                hotelContainer.hide();
            } else {
                hotelContainer.show();
            }
        })
        .trigger("change"); // Panggil trigger untuk inisialisasi saat load
});


