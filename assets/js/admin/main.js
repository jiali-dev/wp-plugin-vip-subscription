jQuery(function($) {
    "use strict";

    // Initialize the datepicker
    jalaliDatepicker.startWatch(
        {
            zIndex: 1100,
            separatorChars: { date: '-'},
            persianDigits: true
        }
    );
})