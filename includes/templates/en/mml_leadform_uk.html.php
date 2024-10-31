<?php
/*
 * LEAD FORM :: UK
 * This is the lead form template for UK.
 */
?>

<div class="mml_form_body clearfix">
	<div class="mml_col-xs-6 float-label mml_main-col">
		<div class="mml_row clearfix">
			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_movingdate_wrap">
				<input type="text" name="mml_moving_date" placeholder="Moving Date" autocomplete="off" class="mml_col-xs-12 mml_moving_date mml_date_format_uk" value="" max="200" required="" readonly="readonly" />
				<label for="mml_moving_date" class="mml_placeholder">Moving Date</label>
				<span class="mml_days_left_wrap"></span>
			</div>

			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_pickupzip_wrap">
				<input type="text" autocomplete="off" maxlength="8" class="mml_col-xs-12 mml_from_zip" name="mml_from_zip" placeholder="Pickup Postcode" data-abr="uk" value="" required="" />
				<label for="mml_from_zip" class="mml_placeholder">Pickup Postcode</label>
			</div>
			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_arrow-to">
				<span class="mml_from-city"></span>
			</div>
			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_deliverycity_wrap">
				<input type="text" name="mml_delivery_city" placeholder="Delivery City" autocomplete="off" class="mml_col-xs-12 mml_delivery_city"  value="" required="" max="200" autocapitalize="off" />
				<label for="mml_delivery_city" class="mml_placeholder">Delivery City</label>
				<span class="mml_distance"></span>
			</div>
		</div>
		<div class="mml_switch_int-wrap mml_col-xs-12">
			<a class="mml_switch mml_switch_int" data-to="int">Switch to International Form</a>
		</div>
	</div>
	<div class="mml_col-xs-6 float-label mml_main-col">
		<div class="mml_row clearfix">
			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_size_wrap">
				<select name="mml_size" class="mml_col-xs-12 mml_size" required="">
					<option value="" selected disabled>Select Home Size</option>
					<option value="2">Studio</option>
					<option value="3">1 Bedroom Home</option>
					<option value="4">2 Bedroom Home</option>
					<option value="5">3 Bedroom Home</option>
					<option value="6">4 Bedroom Home</option>
					<option value="7">4+ Bedroom Home</option>
					<option value="1">Partial</option>
					<option disabled>----------------</option>
					<option value="7">Office Move</option>
					<option value="7">Commercial Move</option>
				</select>
				<label for="mml_size" class="mml_placeholder">Selected Home Size</label>
				<div class="mml_arrow-down"></div>
			</div>

			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_name_wrap">
				<input type="text" name="mml_customer_name" placeholder="First &amp; Last Name" autocomplete="off" class="mml_col-xs-12" value="" required="" max="200" autocapitalize="off" />
				<label for="mml_customer_name" class="mml_placeholder">First &amp; Last Name</label>
			</div>

			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_phone_wrap">
				<input type="tel" name="mml_customer_phone" placeholder="Your Phone" min="6" max="200" autocomplete="off" class="mml_col-xs-12" value="" required="" />
				<label for="mml_customer_phone" class="mml_placeholder">Your Phone</label>
			</div>

			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_email_wrap">
				<input type="email" name="mml_customer_email" placeholder="Your Email" max="200" autocomplete="off" class="mml_col-xs-12" value="" required="" />
				<label for="mml_customer_email" class="mml_placeholder">Your Email</label>
			</div>
		</div>
		<div class="mml_col-xs-12 mml_termstoagree clearfix">
			<div class="mml_termstoagree-wrap mml_col-xs-12 clearfix">
				<label class="mml_termslabel"><input class="mml_termstoagree" type="checkbox"> Consent for data collection<span class="mml_infoicon"></span></label>
			</div>
		</div>
	</div>
	<div class="mml_hidden">
		<input type="hidden" name="from_to_country" value="GB">
	</div>
	<div class="mml_popup mml_popup_termsofservice mml_col-xs-12" style="display: none;">
		<div class="mml_close mml_closeX">x</div>
		<div class="mml_popup_header">Terms of service</div>
		<div class="mml_popup_body">
			<p>This free service is provided as a courtesy by moveadvisor.com for your convenience.</p>
			<p><em>Upon submitting the quote form, I consent my personal data to be collected and stored by the hosting website, shared with reliable third parties (like professional moving companies) and used for providing moving information and quoting purposes. I accept to be contacted by professional movers via email or phone in order to receive accurate moving quotes and other information regarding my move.</em></p>
			<p>If you later request your data to be corrected or removed from our database, please contact us at the email found in the contact us or about page of the hosting website.</p><span class="mml_linkservices"></span>
		</div>
	</div>
</div>

