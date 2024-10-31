/* 
 * Version: 1.1
 */

var use_google = false;
function gm_authFailure() {
	console.log('Unauthorized API key');
	use_google = false;
};

function testApiCredentials(){

	//Just in case because we are using external library
	try
	{
		var a = new google.maps.places.AutocompleteService;
		var request = {
			input: 'new york, ny, us',
			types:['geocode']
		};

		a.getQueryPredictions(request, function(data, response){
			if(response == 'OK'){
				//console.log('we can init google autocomplete');
				use_google = true;
			} else if(response == 'REQUEST_DENIED') {
				console.log('Places API is not enabled.');
			} else {
				console.log('Problem with Google Places API.');
			}
		});
	} catch(error) {
		console.error(error);
	}
}



(function($) {
	$(document).ready(function() {

		var autocomplete_countries = ['at', 'ch', 'de', 'gb', 'nz'];
		var autocomplete_states = ['au', 'ca', 'us'];

		/*
		 * PREFILL by cookie
		 * Checks if cookie is set and prefills info. It does not set cookies
		 * This function uses Jquery Cookie Plugin
		 */
		try {
			if ($.cookie('mml_name') !== 'undefined') {
				var value = $.cookie('mml_name');
				$('input[name=mml_customer_name]').val(value);
			}
			if ($.cookie('mml_email') !== 'undefined') {
				var value = $.cookie('mml_email');
				$('input[name=mml_customer_email]').val(value);
			}
			if ($.cookie('mml_email') !== 'undefined') {
				var value = $.cookie('mml_phone');
				$('input[name=mml_customer_phone]').val(value);
			}
		} catch(err) {
			console.log('You are missing jquery.cookie.js');
		}
		

		// GOOGLE API Autocomplete
		function autocomplete_ini(input, country) {
			if (use_google) {
				if (!country) { country = ''};
				var options = {
					types: ['(cities)'],
					componentRestrictions: {country: country}
				};
				var autocomplete = new google.maps.places.Autocomplete(input.get(0), options);
				var autocompleteLst = google.maps.event.addListener(autocomplete, 'place_changed', function() { getCityOnly(input); });
		 	}
		}

		function getCityOnly(input) {
			try {
				var field = input;
				var value = field.val().split(',');
				field.val(value[0]);
			} catch (err) {
				console.log(err);
			}
		}

		/*
		 * PREDEFINED RESUABLE VARIABLES
		 * Here are all the variables that are used more then once
		 */

		var today = new Date();
		var tomorrow = new Date(today.getTime() + 1000 * 60 * 60 * 24);
		var source = [];

		/* Google API variables */
		var start_location = '';
		var end_location = '';

		/*
		 * REUSABLE HANDLERS
		 * Some functions that are used more then once
		 */

		var changeDate = function () {
			var field = $(this);
			var form = field.closest('form.mml_form');

			var target = form.find($('input[name=mml_dateiso]'));
			var day_start = new Date();
			var day_end = new Date(target.val());
			var total_days = (day_end.getTime() - day_start.getTime()) / (1000 * 60 * 60 * 24);
			if (!isNaN(total_days)) {
				if (Math.round(total_days) > 0) {
					field.parent().find('.mml_days_left_wrap').attr('style', 'opacity:1;');
					//field.parent().find('.mml_days_left').text(Math.ceil(total_days)); 
					if (Math.round(total_days) == 1) {
						field.parent().find('.mml_days_left_wrap').html('About <span class="mml_days_left">' +  Math.round(total_days) + '</span><br/>day left');
					} else {
						field.parent().find('.mml_days_left_wrap').html('About <span class="mml_days_left">' +  Math.round(total_days) + '</span><br/>days left');	
					}
				} else {
					field.parent().find('.mml_days_left_wrap').attr('style', 'opacity:1;');
					field.parent().find('.mml_days_left_wrap').html('<span class="mml_days_left">Soon</span>');
				}
			}
		}

		/*
		 * PAGE LOAD TRACKING
		 * If you have a google analytics code and have turned on the feature from the settings
		 * this will send an event when the form loads with the coresponding abriviation.
		 * Hiding the form will still count as seen.
		 */
		if (mml_object.totrack == '1') {
			var abr = $('form.mml_form').attr('data-abr');
			try {
				if (typeof gtag == 'function') {
					// console.log('gtag');
					gtag('event', abr, {'event_category': 'MoveAdvisor Form', 'event_label': 'Successfully-Viewed'});
				} else if (typeof ga == 'function') {
					// console.log('ga');
					ga('send', 'event', 'MoveAdvisor Form', abr , 'Successfully-Viewed');
				} else if (typeof _gaq == 'function') {
					// console.log('_gaq');
					_gaq.push(['_trackEvent', 'MoveAdvisor Form', abr, 'Successfully-Viewed']);
				}
			} catch(err) {
				console.log('No Google Analytics active');
			}
		}

		/*
		 * LOAD FORM TEMPLATE FUNCTION
		 * This loads the template file with the required form.
		 * 		form_abr    -> the new abbreviation the form will take, and file it has to load
		 * 		form 	    -> the form object, used to target one form only on page
		 * 		change_to   -> the abbreviation that the form needs to change back to
		 * 		switch_text -> the text of the switch back link, implemented because of multi-lingual forms
		 * This function allows to load new templates but the submit functionality still need to be implemented.
		 */


		function loadForm ( form_abr, form, change_to, switch_text ) {
			form.find('.mml_body-wrap').slideUp('100');
			form.find('.mml_body-wrap').html();

			// language can be the preffered language en or de
			var language = mml_object.language;
			if (mml_object.language == 'en'){
                var form_url = mml_object.plugin + 'includes/templates/en/mml_leadform_' + form_abr + '.html.php';
			}
			else {
                var form_url = mml_object.plugin + 'includes/templates/en/mml_leadform_' + form_abr + '.html.php';
			}
			
			$.get(form_url, function(data, status) {

				if (status == 'success') {
					form.find('.mml_body-wrap').html(data);
					form.find('.mml_body-wrap').slideDown('100');

					form.attr('data-abr', form_abr);
				
					if ( form_abr == 'int') {
						form.find('select[name="mml_origin_country"] > option').each(function() {
							if (mml_object.user_country == this.value.toLowerCase()) {
								$(this).attr('selected','selected');
								form.find('select[name="mml_origin_country"]').trigger('change');
								return false;
							};
						});
					}

					form.find('div.mml_switch_int-wrap').show();
					form.find('a.mml_switch')
						.attr('data-to', change_to)
						.removeClass('mml_switch_' + form_abr)
						.addClass('mml_switch_'+ change_to)
						.html(switch_text);

					return true;
				} else {
					form.find('.mml_body-wrap').html('No form for this country. Please refresh page.');
				}

			});

		}

		/*
		 * SWITCH FORM LINK
		 * This is the link that switches the form body when clicked,
		 * between the first loaded form and the international one,
		 * and allows the option to switch back.
		 */
		$('.mml_leadform_wrapper').on('click', 'a.mml_switch', function(e) {
			var switch_link = $(this);
			var form = $(this).closest('div.mml_leadform_wrapper').find('form.mml_form');
			var user_cntr = form.attr('data-abr');
			var switch_to = switch_link.attr('data-to');

			switch (user_cntr) {
				case 'us': switch_text = 'Switch to United States form'; break;
				case 'uk': switch_text = 'Switch to United Kingdom form'; break;
				case 'ca': switch_text = 'Switch to Canada form'; break;
				case 'au': switch_text = 'Switch to Australia form'; break;
				case 'nz': switch_text = 'Switch to New Zealand form'; break;
				case 'de': switch_text = 'Deutschland?'; break;
				default: user_cntr = 'int'; switch_text = 'Switch to international form'; break;
			}

			$('input[name="mml_dateiso"]').val('');	// Clear if there was inputted date

			loadForm ( switch_to, form, user_cntr, switch_text ); // load the new form

			// Google Analytics tracking code
			if (mml_object.totrack == '1') {
				try {
					if (typeof gtag == 'function') {
						// console.log('gtag');
						gtag('event', user_cntr, {'event_category': 'MoveAdvisor Form', 'event_label': 'Successfully-Viewed'});
					} else if (typeof ga == 'function') {
						// console.log('ga');
						ga('send', 'event', 'MoveAdvisor Form', user_cntr , 'Successfully-Viewed');
					} else if (typeof _gaq == 'function') {
						// console.log('_gaq');
						_gaq.push(['_trackEvent', 'MoveAdvisor Form', user_cntr, 'Successfully-Viewed']);
					}
				} catch(err) {
					console.log('No Google Analytics active');
				}
			}
			e.preventDefault();
		});
		/*
		 * FORM RELATED CHANGES
		 * These changes are mainly for the frontend of the form
		 */

		 /* Date Picker (ALL FORMS) */

		$('form.mml_form').on('click', '.mml_moving_date', function(){
			var date_field = $(this);

			if ( date_field.hasClass('mml_date_format_uk') ) {
				date_field.datepicker({
					altFormat: 'yy-mm-dd',
					altField: 'input[name=mml_dateiso]',
					showAnim: 'slideDown',
					ignoreReadonly: true,
					minDate: tomorrow,
					maxDate: '+6m',
					showOn:'focus',
					firstDay: 1,
					dateFormat: 'dd/mm/yy',
					beforeShow: function(input, instance) {
						var inputOffset = $(input).offset();
						var inputH = $(input).outerHeight();
						var top = inputOffset.top;
						var left= inputOffset.left;
						setTimeout(function(){
							instance.dpDiv.css({
								top: top + inputH,
								left: left
							});
						}, 0);
					}
				}).focus();
			} else if (date_field.hasClass('mml_date_format_int')) {
				date_field.datepicker({
					altFormat: 'yy-mm-dd',
					altField: 'input[name=mml_dateiso]',
					showAnim: 'slideDown',
					ignoreReadonly: true,
					minDate: tomorrow,
					maxDate: '+6m',
					showOn:'focus',
					firstDay: 1,
					dateFormat: 'd M yy',
					beforeShow: function(input, instance) {
						var inputOffset = $(input).offset();
						var inputH = $(input).outerHeight();
						var top = inputOffset.top;
						var left= inputOffset.left;
						setTimeout(function(){
							instance.dpDiv.css({
								top: top + inputH,
								left: left
							});
						}, 0);
					}
				}).focus();
			} else {
				date_field.datepicker({
					altFormat: 'yy-mm-dd',
					altField: 'input[name=mml_dateiso]',
					showAnim: 'slideDown',
					ignoreReadonly: true,
					minDate: tomorrow,
					maxDate: '+6m',
					showOn:'focus',
					firstDay: 0,
					dateFormat: 'mm/dd/yy',
					beforeShow: function(input, instance) {
						var inputOffset = $(input).offset();
						var inputH = $(input).outerHeight();
						var top = inputOffset.top;
						var left= inputOffset.left;
						setTimeout(function(){
							instance.dpDiv.css({
								top: top + inputH,
								left: left
							});
						}, 0);
					}
				}).focus();
			}
		});

		$('form.mml_form').on('blur', '.mml_moving_date', changeDate);
		$('form.mml_form').on('change', '.mml_moving_date', changeDate);

		/*
		 * CALCULATE DISTANCE
		 * Using Google Maps, calculate the distance between
		 * the origin location and the delivery location.
		 */	
		function calculateDistances(start_location, end_location, metric) {
			origin = start_location; //Get the source string
			destination = end_location; //Get the destination string
			if ( metric ) {
				unit_system = google.maps.UnitSystem.METRIC;
			} else {
				unit_system = google.maps.UnitSystem.IMPERIAL;
			}
			var service = new google.maps.DistanceMatrixService(); //initialize the distance service
			service.getDistanceMatrix(
			{
				origins: [origin], 							// Set origin, you can specify multiple sources here
				destinations: [destination], 				// Set destination, you can specify multiple destinations here
				travelMode: google.maps.TravelMode.DRIVING, // Set the travelmode
				unitSystem: unit_system, 	// The unit system to use when displaying distance
				avoidHighways: false,
				avoidTolls: false
			}, calcDistance); // here calcDistance is the call back function
		}

		function calcDistance(response, status) {
			if (status == google.maps.DistanceMatrixStatus.OK) { // check if there is valid result
				var origins = response.originAddresses;
				var destinations = response.destinationAddresses;

				results = response.rows;
				result = results[0].elements[0]
				result_status = result.status;

				var distance_wrap = $('span.mml_distance');
				
				if (result_status == 'OK') {
					var distance = result.distance.text;
					distance_wrap.text('About ' + distance);
				} else {
					//console.log('No results found');
				}

			} else {
				//console.log('Status: ' + status);
			}

		}

		$('form.mml_form').on('focusout', 'input[name="mml_delivery_city"]', function(){
			if ( mml_object.googleAPI.length > 2 ) {
				var field = $(this);
				var form = field.closest('form.mml_form');
				var abr = form.attr('data-abr');
				var city, state, country;

				switch (abr) {
					case 'us':
						city = field.val();
						state = form.find('select[name="mml_delivery_state"]').val();
						country = 'US';
						end_location = city + ', ' + state + ', ' + country;
						break;
					case 'uk':
						city = field.val();
						country = 'UK';
						end_location = city + ', ' + country;
						break;
					case 'ca':
						city = field.val();
						state = form.find('select[name="mml_delivery_state"]').val();
						country = 'CA';
						end_location = city + ', ' + state + ', ' + country;
						break;
					case 'au':
						city = field.val();
						state = form.find('select[name="mml_delivery_state"]').val();
						country = 'AU';
						end_location = city + ', ' + state + ', ' + country;
					case 'nz':
						city = field.val();
						country = 'NZ';
						end_location = city + ', ' + country;
						break;
					default:
						//console.log('Error');
						break;
				}

				if (start_location !== '' && end_location !== '') {
					if (use_google) {
						if (abr == 'us') {
							calculateDistances(start_location, end_location, false);						
						} else {
							calculateDistances(start_location, end_location, true);
						}
					}
				}
			}
		});

		// For international only
		$('form.mml_form').on('change', 'input[name="mml_destination_city"]', function(){
			var field = $(this);
			var form = field.closest('form.mml_form');
			var abr = form.attr('data-abr');


			var start_city = form.find('input[name="mml_origin_city"]').val();
			var start_country = form.find('select[name="mml_origin_country"]').val();
			var start_state = '';

			switch (start_country) {
				case 'US':
				start_state = form.find('select[name="mml_origin_state_us"]').val();
				start_location = start_city + ', ' + start_state + ', ' + start_country;
				break;

				case 'CA':
				start_state = form.find('select[name="mml_origin_state_ca"]').val();
				start_location = start_city + ', ' + start_state + ', ' + start_country;
				break;

				case 'AU':
				start_state = form.find('select[name="mml_origin_state_au"]').val();
				start_location = start_city + ', ' + start_state + ', ' + start_country;
				break;

				default:
				start_location = start_city + ', ' + start_country;
				break;
			}

			var end_city = form.find('input[name="mml_destination_city"]').val();
			var end_country = form.find('select[name="mml_destination_country"]').val();

			switch (end_country) {
				case 'US':
				end_state = form.find('select[name="mml_delivery_state_us"]').val();
				end_location = end_city + ', ' + end_state + ', ' + end_country;
				break;

				case 'CA':
				end_state = form.find('select[name="mml_delivery_state_ca"]').val();
				end_location = end_city + ', ' + end_state + ', ' + end_country;
				break;

				case 'AU':
				end_state = form.find('select[name="mml_delivery_state_au"]').val();
				end_location = end_city + ', ' + end_state + ', ' + end_country;
				break;

				default:
				end_location = end_city + ', ' + end_country;
				break;
			}

			if (start_location !== '' && end_location !== '') {
				if (use_google) {
					calculateDistances(start_location, end_location);
				}
			}
		});

		$('.mml_close').click(function(){
			var parent = $(this).parent();
			parent.slideUp();
		});

		$('form.mml_form').submit(function (e) {
			var form = $(this);

			var abr = form.attr('data-abr');

			var post_id = form.attr('data-post');

			// Fields
			var field_required		= form.find('input[required=true]');
			var field_error			= form.find('div.mml_error');
			var field_fails			= form.find('input[name="failed"]');
			var honey_field			= form.find('input[type="email"].mml_leadform_3198637');

			var terms_field			= form.find('input[type="checkbox"].mml_termstoagree');

			var inputArray;
			
			proceed = true;			// State of the proccess.

			// Reset errors on submit attempt
			field_error.html();

			if (honey_field.val().length > 0) {
				$('form.mml_form').fadeOut();
				$('.mml_leadform_success').fadeIn();
				proceed = false;
				console.log('Go away, bot.');
			} else {

				var value_moving_date = form.find('input[name="mml_dateiso"]').val();		// Data format in ISO
				var value_size = form.find('select[name="mml_size"]').val();				// Moving Size
				var value_cust_name = form.find('input[name="mml_customer_name"]').val();	// Customer Name
				var value_cust_ip = form.find('input[name="user_id"]').val();				// User IP

				var value_cust_phone = form.find('input[name="mml_customer_phone"]').val();	// Customer Phone
				var field_phone = form.find('input[name="mml_customer_phone"]');
				// Phone number validation (Tier 1)
				var value_cust_phone = value_cust_phone.replace(/\D/g,'');
				if (abr == 'us' || abr == 'ca') {
					// With US/CA remove the country code
					if (value_cust_phone[0] == 1) {
						value_cust_phone = value_cust_phone.substr(1);
					}
					// US/CA phones are always 10 digits
					if (value_cust_phone.length == 10) {
						field_phone.removeClass('mml_error_field');		// remove any previous error
					} else {
						proceed = false;
						field_fails.val(field_fails.val() + "|Phone - Not 10 digits");
						field_phone.addClass('mml_error_field');
						field_error.html('Please enter valid phone.');
					}
				}

				var value_cust_email = form.find('input[name="mml_customer_email"]').val();	// Customer Email
				var field_email = form.find('input[name="mml_customer_email"]');
				// Email validation (Tier 1)
				if ( value_cust_email.length < 13 ) {
					proceed = false;
					field_fails.val(field_fails.val() + "|Email - Email too short");
					field_email.addClass('mml_error_field');
					field_error.html('Please enter valid email.');
				} else {
					field_email.removeClass('mml_error_field');
				}

				var value_failed = form.find('input[name="failed"]').val();					// Record errors

				if (abr !== 'int' && abr !== 'de') {
					var value_from_zip = form.find('input[name="mml_from_zip"]').val();		// Take ZIP code

					if ( abr !== 'uk' || abr !== 'nz' ) {													// UK SPECIFIC
						var value_to_state = form.find('select[name="mml_delivery_state"]').val();	// Take Delivery State
					} else {
						var value_to_state = '';
					}

					var value_delivery_city = form.find('input[name="mml_delivery_city"]').val();
					if (value_delivery_city == 'undefined') {
						proceed = false;
						field_email.addClass('mml_error_field');
						field_error.html('Please enter valid city name.');
					}

					var value_from_country = form.find('input[name="from_to_country"]').val();
					if ( abr == 'ca') {																// CANADA SPECIFIC
						var value_to_country = form.find('input[name="to_country"]').val();
					} else {
						var value_to_country = form.find('input[name="from_to_country"]').val();
					}


					// Fill the data NOT INT
					post_data = {
						customer_name : value_cust_name,
						customer_phone : value_cust_phone,
						customer_email : value_cust_email,
						from_country : value_from_country,
						from_postal_code : value_from_zip,
						to_country : value_to_country,
						to_state : value_to_state,
						to_place : $('input[name="mml_delivery_city"]').val().match(/[0-9]/) ? null : value_delivery_city,
						to_postal_code : $('input[name="mml_delivery_city"]').val().match(/[0-9]/) ? value_delivery_city : null,
						size : value_size,
						moving_date : value_moving_date,
						parent_webpage: mml_object.ref_page,
						failed: value_failed,
						post_id: post_id
					}

				} else {
					// Is INT form or DE form
					var value_from_country = form.find('select[name="mml_origin_country"]').val();
					var value_from_place = form.find('input[name="mml_origin_city"]').val();
					if (value_from_place == 'undefined') {
						proceed = false;
						field_email.addClass('mml_error_field');
						field_error.html('Please enter valid city name.');
					}

					var value_from_state = '';
					if (value_from_country == 'US') {
						value_from_state = form.find('select[name="mml_origin_state_us"]').val();
					} else if (value_from_country == 'CA') {
						value_from_state = form.find('select[name="mml_origin_state_ca"]').val();
					} else if (value_from_country == 'AU') {
						value_from_state = form.find('select[name="mml_origin_state_au"]').val();
					} else {
						value_from_state = '';
					}

					var value_to_country = form.find('select[name="mml_destination_country"]').val();
					var value_delivery_city = form.find('input[name="mml_destination_city"]').val();
					if (value_delivery_city == 'undefined') {
						proceed = false;
						field_email.addClass('mml_error_field');
						field_error.html('Please enter valid city name.');
					}
					var value_to_state = '';
					if (value_to_country == 'US') {
						value_to_state = form.find('select[name="mml_delivery_state_us"]').val();
					} else if (value_to_country == 'CA') {
						value_to_state = form.find('select[name="mml_delivery_state_ca"]').val();
					} else if (value_to_country == 'AU') {
						value_to_state = form.find('select[name="mml_delivery_state_au"]').val();
					} else {
						value_to_state = '';
					}

					// Fill the data Int
					post_data = {
						customer_name : value_cust_name,
						customer_phone : value_cust_phone,
						customer_email : value_cust_email,
						from_country : value_from_country,
						from_state: value_from_state,
						from_place : value_from_place,
						to_country : value_to_country,
						to_place : $('input[name="mml_destination_city"]').val().match(/[0-9]/) ? null : value_delivery_city,
						to_postal_code : $('input[name="mml_destination_city"]').val().match(/[0-9]/) ? value_delivery_city : null,
						to_state : value_to_state,
						size : value_size,
						moving_date : value_moving_date,
						parent_webpage: mml_object.ref_page,
						failed: value_failed,
						post_id: post_id
					}
				}

				// Terms of Service
				if (terms_field.length > 0) {
					if (!terms_field.is(':checked')) {
						terms_field.closest('label').addClass('mml_error_field');
						field_error.html('You must agree on terms of service.');
						proceed = false;
					} else {
						terms_field.closest('label').removeClass('mml_error_field');
					}
				}

				if (!proceed) {
					// General error settings for this form
					form.effect( 'shake' );
					console.log('Something is wrong with the form');
				} else {

					field_error.html('');
					var old_submit_value = form.find('input[type=submit]').val();
					form.find('input[type=submit]').val('Sending...').prop('disabled', true).addClass('disabled');


					if (form.hasClass('mml_isadmin')) {
						$('form.mml_form').fadeOut();
						$('.mml_leadform_success').fadeIn();
						if ($('.mml_switch_int-wrap').length > 0 ) { $('.mml_switch_int-wrap').remove(); }
						$('.mml_admin_block').slideDown().html('This test lead was not submitted to MoveAdvisor.<br/>Please note that only real-time leads by visitors are approved as valid leads according to our affiliate agreement.<br/>Your visitors will not see this message.<br/><br/><a href="">Click to visit your MoveAdvisor affiliate account.</a>');
					} else {
						data = {
							action: 'mml_ajax_action',
							security: mml_object.nonsense,
							form_data: post_data
						}

						$.ajax({
							url: mml_ajax_url.ajaxurl,
							async: true,
							type: 'POST',
							data: data,
							dataType: 'json', // added data type

							success: function(res) {
								if ( res.status !== 'error') {

									// Google Analytics tracking code
									if (mml_object.totrack == '1') {
										try {
											if (typeof gtag == 'function') {
												// console.log('gtag');
												gtag('event', abr, {'event_category': 'MoveAdvisor Form', 'event_label': 'Successfully-Sent'});
											} else if (typeof ga == 'function') {
												// console.log('ga');
												ga('send', 'event', 'MoveAdvisor Form', abr , 'Successfully-Sent');
											} else if (typeof _gaq == 'function') {
												// console.log('_gaq');
												_gaq.push(['_trackEvent', 'MoveAdvisor Form', abr, 'Successfully-Sent']);
											}
										} catch(err) {
											console.log('No Google Analytics active');
										}
									}
									
									$('form.mml_form').fadeOut();
									if (res.ic && res.ic === 1) {

										let agentNumber = "";
										switch (abr) {
											case 'uk':
												agentNumber = '0800 086 9039';
												break;
											case 'au':
												agentNumber = '1800 952 370';
												break;
											case 'nz':
												agentNumber = '0800 244 266';
												break;
											case 'ie':
												agentNumber = '1 800 81 6551';
												break;
											default:
												agentNumber = '+1 (800) 680-6439';
												break;
										}

										$('#call-transfer-number-success').text(agentNumber);

										$('.mml_leadform_phonecall_success').fadeIn();
									} else {
										$('.mml_leadform_success').fadeIn();
									}
									if ($('.mml_switch_int-wrap').length > 0 ) { $('.mml_switch_int-wrap').remove(); }
								
									// If it is a demo test
									if (res.text === 'Demo Test Successful.') {
										$('.mml_leadform_success').append('<div class="demo_warning">This is a Demo Test.<br/>Please contact your MoveAdvisor representative to get an active API key.</div>');
									}
								} else {
									// Handle API errors response
									var errors = res.errors;
									for(i = 0; i < errors.length; i++) {
										if (errors[i].indexOf('name') !== -1) {
											field_fails.val(field_fails.val() + "|Name - " + errors[i] + " (" + value_cust_name + ")");
										} else if(errors[i].indexOf('phone') !== -1) {
											field_fails.val(field_fails.val() + "|Phone - " + errors[i] + " (" + value_cust_phone + ")");
										} else if (errors[i].indexOf('E-Mail') !== -1) {
											field_fails.val(field_fails.val() + "|Email - " + errors[i] + " (" + value_cust_email + ")");
										} else {
											field_fails.val(field_fails.val() + "|" + errors[i]);
										}
									}
									form.effect( 'shake' );
									field_error.html(errors[0]);
								}
								form.find('input[type=submit]').val(old_submit_value).prop('disabled', false).removeClass('disabled');
							},
							error: function (error) {
								console.log(error);
								form.effect( 'shake' );
								field_error.html(error.errors + ' Please try again.');
								form.find('input[type=submit]').val(old_submit_value).prop('disabled', false).removeClass('disabled');
							}
						});	
					}

				}
			}
			
			e.preventDefault();
		});


		var key_pressed_timeout = null;
		function findZip(abr, field){

			var country = '';
			form = field.closest('form.mml_form');
			switch(abr) {
				case 'uk': country = 'GB'; break;
				case 'us': country = 'US'; break;
				case 'ca': country = 'CA'; break;
				case 'au': country = 'AU'; break;
				case 'nz': country = 'NZ'; break;
				case 'usa': country = 'US'; break;
			}
			postal_code = $('.mml_from_zip').val();
			address = $('.mml_from_zip').val() + ', ' + ((country == 'GB') ? 'UK' : country);



			geocoder = new google.maps.Geocoder();
			geocoder.geocode( { 'address': address, 'country': country}, function(results, status) {
				var found = false;
				var country_match = false;
				var postal_match = false;
				var locality;
				var state;
				var print_address;

				if (status == "OK") {

					$.each(results, function(key, result) {
						country_match = false;
						postal_match = false;
						$.each(result['address_components'], function(key,component) {
							if (component['types'][0] == 'postal_code') {
								if (component['short_name'] == postal_code.toUpperCase()) {
									postal_match = true;
								} else if (component['short_name'] == postal_code.substring(0, 3).toUpperCase() && country !== 'US') {
									postal_match = true;
								}
							}
							if (component['types'][0] == 'country' && component['short_name'] == country) {
								country_match = true;
							}
							if (component['types'][0] == 'administrative_area_level_1') {
								state = component['short_name'];
							}
							if (country == 'US' || country == 'CA' || country == 'AU') {
								if (component['types'][0] == 'locality') {
									locality = component['long_name'];
								}
							} else if (country == 'GB') {
								if (component['types'][0] == 'postal_town') {
									locality = component['long_name'];
								}
							}

						});

						if ( country_match == true && postal_match == true) {
							print_address = ((locality !== undefined) ? locality + ', ' : '') + ((state !== undefined) ? state : '');
							form.find('.mml_from-city').html(print_address);
							start_location = result['formatted_address'];
							found = true;
							return;
						}

					});
				}

				if(!found) {
					$('.mml_from-city').empty();
					start_location = '';
				}
			});

		};

		$('form.mml_form').on('keypress', '.mml_from_zip', function(){

			if ( mml_object.googleAPI.length > 2 ) {	// shows that there is no google api entered in the 
				var field = $(this);
				var abr = field.attr('data-abr');

				if(key_pressed_timeout != null) {
					clearTimeout(key_pressed_timeout);
					key_pressed_timeout = null;
				}
				key_pressed_timeout = setTimeout(function(){ findZip(abr, field); }, 500);
			}

		});

		// International Form - Origin: Show states for countries with states
		$('form.mml_form').on('change', 'select[name=mml_origin_country]', function() {
			var form = $(this).closest('form.mml_form');
			var select_wrap = $(this).parent();
			if ($(this).val() == 'US') {
				select_wrap.removeClass('mml_col-xs-12').addClass('mml_col-xs-6');
				form.find('.mml_originstate_wrap.mml_int_us').show();
				form.find('.mml_originstate_wrap.mml_int_ca').hide();
				form.find('.mml_originstate_wrap.mml_int_au').hide();
				form.find('.mml_originstate_wrap.mml_int_us > select').prop('required', true);
				form.find('.mml_originstate_wrap.mml_int_ca > select').prop('required', false);
				form.find('.mml_originstate_wrap.mml_int_au > select').prop('required', false);
			} else if ($(this).val() == 'CA') {
				select_wrap.removeClass('mml_col-xs-12').addClass('mml_col-xs-6');
				form.find('.mml_originstate_wrap.mml_int_us').hide();
				form.find('.mml_originstate_wrap.mml_int_ca').show();
				form.find('.mml_originstate_wrap.mml_int_au').hide();
				form.find('.mml_originstate_wrap.mml_int_us > select').prop('required', false);
				form.find('.mml_originstate_wrap.mml_int_ca > select').prop('required', true);
				form.find('.mml_originstate_wrap.mml_int_au > select').prop('required', false);
			} else if ($(this).val() == 'AU') {
				select_wrap.removeClass('mml_col-xs-12').addClass('mml_col-xs-6');
				form.find('.mml_originstate_wrap.mml_int_us').hide();
				form.find('.mml_originstate_wrap.mml_int_ca').hide();
				form.find('.mml_originstate_wrap.mml_int_au').show();
				form.find('.mml_originstate_wrap.mml_int_us > select').prop('required', false);
				form.find('.mml_originstate_wrap.mml_int_ca > select').prop('required', false);
				form.find('.mml_originstate_wrap.mml_int_au > select').prop('required', true);
			} else {
				select_wrap.removeClass('mml_col-xs-6').addClass('mml_col-xs-12');
				form.find('.mml_originstate_wrap.mml_int_us').hide();
				form.find('.mml_originstate_wrap.mml_int_ca').hide();
				form.find('.mml_originstate_wrap.mml_int_au').hide();
				form.find('.mml_originstate_wrap.mml_int_us > select').prop('required', false);
				form.find('.mml_originstate_wrap.mml_int_ca > select').prop('required', false);
				form.find('.mml_originstate_wrap.mml_int_au > select').prop('required', false);
			}
		});

		// International Form - Delivery: Show states for countries with states
		$('form.mml_form').on('change', 'select[name=mml_destination_country]', function() {
			var form = $(this).closest('form.mml_form');
			var select_wrap = $(this).parent();
			if ($(this).val() == 'US') {
				select_wrap.removeClass('mml_col-xs-12').addClass('mml_col-xs-6');
				form.find('.mml_deliverystate_wrap.mml_int_us').show();
				form.find('.mml_deliverystate_wrap.mml_int_ca').hide();
				form.find('.mml_deliverystate_wrap.mml_int_au').hide();
				form.find('.mml_deliverystate_wrap.mml_int_us > select').prop('required', true);
				form.find('.mml_deliverystate_wrap.mml_int_ca > select').prop('required', false);
				form.find('.mml_deliverystate_wrap.mml_int_au > select').prop('required', false);
			} else if ($(this).val() == 'CA') {
				select_wrap.removeClass('mml_col-xs-12').addClass('mml_col-xs-6');
				form.find('.mml_deliverystate_wrap.mml_int_us').hide();
				form.find('.mml_deliverystate_wrap.mml_int_ca').show();
				form.find('.mml_deliverystate_wrap.mml_int_au').hide();
				form.find('.mml_deliverystate_wrap.mml_int_us > select').prop('required', false);
				form.find('.mml_deliverystate_wrap.mml_int_ca > select').prop('required', true);
				form.find('.mml_deliverystate_wrap.mml_int_au > select').prop('required', false);
			} else if ($(this).val() == 'AU') {
				select_wrap.removeClass('mml_col-xs-12').addClass('mml_col-xs-6');
				form.find('.mml_deliverystate_wrap.mml_int_us').hide();
				form.find('.mml_deliverystate_wrap.mml_int_ca').hide();
				form.find('.mml_deliverystate_wrap.mml_int_au').show();
				form.find('.mml_deliverystate_wrap.mml_int_us > select').prop('required', false);
				form.find('.mml_deliverystate_wrap.mml_int_ca > select').prop('required', false);
				form.find('.mml_deliverystate_wrap.mml_int_au > select').prop('required', true);
			} else {
				select_wrap.removeClass('mml_col-xs-6').addClass('mml_col-xs-12');
				form.find('.mml_deliverystate_wrap.mml_int_us').hide();
				form.find('.mml_deliverystate_wrap.mml_int_ca').hide();
				form.find('.mml_deliverystate_wrap.mml_int_au').hide();
				form.find('.mml_deliverystate_wrap.mml_int_us > select').prop('required', false);
				form.find('.mml_deliverystate_wrap.mml_int_ca > select').prop('required', false);
				form.find('.mml_deliverystate_wrap.mml_int_au > select').prop('required', false);
			}
		});

		// NB! Canada Form - On delivery, take if the state is in CA or US
		$('form.mml_form').on('change', 'select[name=mml_delivery_state]', function() {
			var form = $(this).closest('form.mml_form');
			var state = $(this).val().toLowerCase();

			var typeahead_source_url = '';
			if (form.attr('data-abr') == 'ca') {
				var group_selection = $("option:selected", this).closest('optgroup').attr('id');
				if (group_selection == "US") {
					form.find('input[name="to_country"]').val('US');
					typeahead_source_url = mml_object.plugin + 'assets/typeahead/us/'+state+'.json';
				} else {
					form.find('input[name="to_country"]').val('CA');
					typeahead_source_url = mml_object.plugin + 'assets/typeahead/ca/'+state+'.json';
				}

			} else if (form.attr('data-abr') == 'us') {
				typeahead_source_url = mml_object.plugin + 'assets/typeahead/us/'+state+'.json';
			} else if (form.attr('data-abr') == 'au') {
				typeahead_source_url = mml_object.plugin + 'assets/typeahead/au/'+state+'.json';
			} else {
				//console.log(form.attr('data-abr'));
			}

			$.get( typeahead_source_url, function( data ) {
				source = data;
			}).fail(function(error){ console.log(error); source = [];});

			var input = form.find('input[name="mml_delivery_city"]');
			$(input).replaceWith(input.clone());
		});

		// States in international forms
		$('form.mml_form').on('change', '.mml_stateselect', function() {
			var form = $(this).closest('form.mml_form');
			var state = $(this).val().toLowerCase();

			if ($(this).parent().hasClass('mml_originstate_wrap'))
			{
				country = form.find('select[name=mml_origin_country]').val();
				input = form.find('input[name="mml_origin_city"]');
			} else {
				country = form.find('select[name=mml_destination_country]').val();
				input = form.find('input[name="mml_destination_city"]');
			}

			var typeahead_source_url = '';

			typeahead_source_url = mml_object.plugin + 'assets/typeahead/'+country.toLowerCase()+'/'+state+'.json';

			$.get( typeahead_source_url, function( data ) {
				source = data;
			}).fail(function(error){ console.log(error); source = [];});

			$(input).replaceWith(input.clone());
		});

		// Sets for countries without state and resets input autocomplete/typeahead
		$('form.mml_form').on('change', '.mml_countryselect', function() {
			var form = $(this).closest('form.mml_form');
			
			if ($(this).parent().hasClass('mml_origincountry_wrap'))
			{
				input = form.find('input[name="mml_origin_city"]');
			} else {
				input = form.find('input[name="mml_destination_city"]');
			}

			var country_val = $(this).val().toLowerCase();

			if($.inArray(country_val, autocomplete_countries) != -1) {
				typeahead_source_url = mml_object.plugin + 'assets/typeahead/'+country_val+'.json';
				$.get( typeahead_source_url, function( data ) {
					source = data;
				}).fail(function(error){ console.log(error); source = [];});		
			}

			$(input).replaceWith(input.clone());
		});

		// For NZ only, since it does not have states
		$('form.mml_form').on('focusin', '.mml_to_city_nz > input', function() {
			var form = $(this).closest('form.mml_form');
			var typeahead_source_url = mml_object.plugin + 'assets/typeahead/nz.json';
			var input = $(this);

			$.get( typeahead_source_url, function( data ) {
				source = data;
			})
			.fail(function(error){ console.log(error); source = [];})
			.done(function(){
				try {
					input.mml_typeahead({
						source: source,
						items: 4
					});
				} catch ( err) {
					console.log(err);
				}

			});
		});

		$('form.mml_form').on('focus', 'input[name="mml_delivery_city"]', function(){
			var form = $(this).closest('form.mml_form');
			var abr = form.attr('data-abr');

			var input = $(this);
			if (source.length > 0 ) {
				if (abr !== 'uk') {
					// Typeahead Bootstrap 2
					try {
						input.mml_typeahead({
							source: source,
							items: 4
						});
					} catch (error) {
						// No Typeahead loaded
						console.log(error);
					}
				}
			}

		});

		$('form.mml_form').on('focusin', 'input.mml_cityfield', function(){
			var form = $(this).closest('form.mml_form');
			var input = $(this);

			if (input.attr('name') == 'mml_origin_city') {
				var country = form.find('select[name="mml_origin_country"]').val();
			} else {
				var country = form.find('select[name="mml_destination_country"]').val();
			}

			if (country !== null) {
				country = country.toLowerCase();
			}
			if ( $.inArray(country, autocomplete_countries) != -1 || $.inArray(country, autocomplete_states) != -1) {
				if (source.length > 0 ) {

					// Typeahead Bootstrap 2
					try {
						input.mml_typeahead({
							source: source,
							items: 4
						});
					} catch (error) {
						// No Typeahead loaded
						console.log(error);
					}
				}
			} else {
				autocomplete_ini(input, country);				
			}
		});

		/*
		 * PRIVACY POLICY POPUP
		 */
		$('form.mml_form').on('click', '.mml_infoicon', function(e) {
			var form = $(this).closest('form.mml_form');
			var popup = form.find('.mml_popup.mml_popup_termsofservice');
			var link = $('.mml_popup.mml_popup_termsofservice span.mml_linkservices');
			var element;

			if (mml_object.default_privacy == '1') {
				element = '<a href="' + mml_object.plugin + 'assets/files/mml-tou-pp.htm" target="_blank" rel="nofollow">Read terms of service.</a>';
			} else {
				element = '<strong>Read terms of service below.</strong>';
			}
			link.html(element);

			if (popup.css('display') == 'none') {
				popup.fadeIn(350);				
			} else {
				popup.fadeOut(350);
			}

			e.preventDefault();
		});
		$(document).on('click', function(e) {
			var target = $(e.target);
			if (!target.is('.mml_infoicon')) {
				$('.mml_popup.mml_popup_termsofservice').fadeOut(350);
			}
		});

	}); // Close document Ready

}(jQuery));
