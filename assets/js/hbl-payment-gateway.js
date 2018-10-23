(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	//console.log('asdfdsa');
	$( window ).load(function() {
		jQuery('#hash_code').after('<div class="info-hbl">Hash code will be something like this. (L3YN0LCZM6JFGMGA49XS7FK07BIHLA3L) </div>');
		jQuery('#Paymentgetway_ID').after('<div class="info-hbl">Payment gateway code will be something like this(10 characters). (9103332318) </div>');
		jQuery('#invoice_number').after('<div class="info-hbl">Please use numeric (Must be 20 characters).Invoice number  will be something like this. (00000001234567890301) </div>');
		       

		//price format changing
		// //change price formatt for HBL API
	    function padDigits(number, digits) {
	      return Array(Math.max(digits - String(number).length + 1, 0)).join(0) + number;
	    }
		var user_price = $('.cal-price').html();
	      var formatted_price = padDigits(user_price,10)+'00';
	      jQuery('#amount').val(formatted_price);
	});

})( jQuery );
