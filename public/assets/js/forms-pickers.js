/**
 * Form Picker
 */

'use strict';

// * Pickers with jQuery dependency (jquery)
$(function () {
  // Bootstrap Datepicker
  // --------------------------------------------------------------------
  var bsDatepickerBasic = $('#bs-datepicker-basic'),
    bsDatepickerFormat = $('#bs-datepicker-format'),
    bsDatepickerRange = $('#bs-datepicker-daterange'),
    bsDatepickerDisabledDays = $('#bs-datepicker-disabled-days'),
    bsDatepickerMultidate = $('#bs-datepicker-multidate'),
    bsDatepickerOptions = $('#bs-datepicker-options'),
    bsDatepickerAutoclose = $('#bs-datepicker-autoclose'),
    bsDatepickerAutoclose2 = $('#bs-datepicker-autoclose2'),
    bsDatepickerInlinedate = $('#bs-datepicker-inline');

  // Basic
  if (bsDatepickerBasic.length) {
    bsDatepickerBasic.datepicker({
      todayHighlight: true,
      orientation: isRtl ? 'auto right' : 'auto left'
    });
  }

  // Format
  if (bsDatepickerFormat.length) {
    bsDatepickerFormat.datepicker({
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      orientation: isRtl ? 'auto right' : 'auto left'
    });
  }

  // Range
  if (bsDatepickerRange.length) {
    bsDatepickerRange.datepicker({
      todayHighlight: true,
      orientation: isRtl ? 'auto right' : 'auto left'
    });
  }

  // Disabled Days
  if (bsDatepickerDisabledDays.length) {
    bsDatepickerDisabledDays.datepicker({
      todayHighlight: true,
      daysOfWeekDisabled: [0, 6],
      orientation: isRtl ? 'auto right' : 'auto left'
    });
  }

  // Multiple
  if (bsDatepickerMultidate.length) {
    bsDatepickerMultidate.datepicker({
      multidate: true,
      todayHighlight: true,
      orientation: isRtl ? 'auto right' : 'auto left'
    });
  }

  // Options
  if (bsDatepickerOptions.length) {
    bsDatepickerOptions.datepicker({
      calendarWeeks: true,
      clearBtn: true,
      todayHighlight: true,
      orientation: isRtl ? 'auto left' : 'auto right'
    });
  }

  // Auto close
  if (bsDatepickerAutoclose.length) {
    bsDatepickerAutoclose.datepicker({
      todayHighlight: true,
      autoclose: true,
      orientation: isRtl ? 'auto right' : 'auto left'
    });
  }

  // Auto close
  if (bsDatepickerAutoclose2.length) {
    bsDatepickerAutoclose2.datepicker({
      todayHighlight: true,
      autoclose: true,
      orientation: isRtl ? 'auto right' : 'auto left'
    });
  }

  // Inline picker
  if (bsDatepickerInlinedate.length) {
    bsDatepickerInlinedate.datepicker({
      todayHighlight: true
    });
  }
});
