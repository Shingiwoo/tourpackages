/**
 * Add Expense Page
 */
'use strict';

(function () {
    // Initialize Cleave for numeral-mask inputs
    function initializeCleave() {
        $('.numeral-mask').each(function () {
            if (!$(this).data('cleave')) { // Cegah inisialisasi ganda
                new Cleave(this, {
                    delimiter: ',',
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
                $(this).data('cleave', true);
            }
        });
    }

    // Initialize Select2 for select elements
    function initializeSelect() {
        $('.select').each(function () {
            if (!$(this).hasClass('select2-hidden-accessible')) { // Cegah inisialisasi ganda
                $(this).select({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Select Account'
                });
            }
        });
    }

    $(document).ready(function () {
        // Initialize existing inputs
        initializeCleave();
        initializeSelect();

        // Repeater for expense items
        if ($('#expenseItemsContainer').length) {
            $('#expenseItemsContainer').repeater({
                initEmpty: false,
                show: function () {
                    console.log('New item added'); // Debugging
                    $(this).slideDown();

                    // Destroy existing Select to prevent duplication
                    $(this).find('.select').select('destroy');

                    // Initialize Cleave and Select for new item
                    initializeCleave();
                    initializeSelect();

                    // Update names with new index
                    var index = $('.expense-item').length - 1;
                    $(this).find('input, select, textarea').each(function () {
                        var name = $(this).attr('name');
                        if (name) {
                            $(this).attr('name', name.replace(/expenses\[\d+\]/, `expenses[${index}]`));
                        }
                    });

                    // Clear values
                    $(this).find('input[type="text"]').val('');
                    $(this).find('textarea').val('');
                    $(this).find('.select').val('').trigger('change');

                    // Copy BookingId from first item
                    var bookingId = $('input[name="expenses[0][BookingId]"]').val();
                    $(this).find('input[name$="[BookingId]"]').val(bookingId);
                },
                hide: function (deleteElement) {
                    if ($('.expense-item').length > 1) {
                        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                            console.log('Item deleted'); // Debugging
                            // Destroy Select before removing
                            $(this).find('.select').select('destroy');
                            $(this).slideUp(function () {
                                deleteElement(); // Panggil deleteElement untuk menghapus dari DOM
                            });
                        }
                    } else {
                        alert('Minimal satu item harus ada.');
                    }
                }
            });
        }

        // Form submission handling
        $('#addAccountForm').on('submit', function (e) {
            // Remove Cleave formatting before submission
            $('.numeral-mask').each(function () {
                var rawValue = $(this).val().replace(/,/g, '');
                $(this).val(rawValue);
            });
        });
    });
})();