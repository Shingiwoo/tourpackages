// Form Facility Page
$(document).ready(function () {
    $("#type_facility")
        .change(function () {
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
$(document).ready(function () {
    // Ambil old value menggunakan Blade dan konversi ke format JavaScript
    var oldValue = "{{ old('hotelCapacity', $hotel->capacity ?? '') }}";

    $("#hotel_type")
        .change(function () {
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

// Form Custom Page
$(document).ready(function() {
    // Handler untuk checkbox IncludeMakan
    $('#IncludeMakan').change(function() {
        var isChecked = $(this).is(':checked');
        var mealCostRow = $('#mealCost').closest('.row');
        var mealCostInput = $('#mealCost');
        var totalMealInput = $('#totalMeal');

        if (isChecked) {
            mealCostRow.show();
            mealCostInput.val('');
            totalMealInput.val('');
        } else {
            mealCostRow.hide();
            mealCostInput.val(0);
            totalMealInput.val(1);
        }
    }).trigger('change'); // Inisialisasi saat load

    // Handler untuk checkbox IncludeHotel
    $('#IncludeHotel').change(function() {
        var isChecked = $(this).is(':checked');
        var hotelRow = $('#hotelPrice').closest('.row');
        var hotelPriceInput = $('#hotelPrice');
        var nightInput = $('#night');
        var capacityHotelInput = $('#capacityHotel');

        if (isChecked) {
            hotelRow.show();
            hotelPriceInput.val('');
            nightInput.val('0');
            capacityHotelInput.val('');
        } else {
            hotelRow.hide();
            hotelPriceInput.val(0);
            nightInput.val(0);
            capacityHotelInput.val(1);
        }
    }).trigger('change');
});

// Form booking Page
$(document).ready(function () {
    $("#modal_packageType")
        .change(function () {
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
