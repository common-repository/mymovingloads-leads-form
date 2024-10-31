<?php
/*
 * LEAD FORM :: CA
 * This is the lead form template for CA.
 */
?>

<div class="mml_form_body clearfix">
	<div class="mml_col-xs-6 float-label mml_main-col">
		<div class="mml_row clearfix">
			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_movingdate_wrap">
				<input type="text" name="mml_moving_date" placeholder="Moving Date" autocomplete="off" class="mml_col-xs-12 mml_moving_date mml_date_format_us" max="200" value="" required="" readonly="readonly" />
				<label for="mml_moving_date" class="mml_placeholder">Moving Date</label>
				<span class="mml_days_left_wrap"></span>
			</div>
			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_pickupzip_wrap">
				<input type="text" autocomplete="off" minlength="2" class="mml_col-xs-12 mml_from_zip" name="mml_from_zip" data-abr="ca" min="3" max="100" placeholder="Pickup Postcode" value="" required="" />
				<label for="mml_from_zip" class="mml_placeholder">Pickup Postcode</label>
				<span class="mml_from-city"></span>
			</div>
			<div class="mml_group mml_delivery_group mml_control">
				<div class="mml_col-xs-12 mml_control mml_field_wrap mml_deliverystate_wrap">

					<select name="mml_delivery_state" class="mml_col-xs-12 mml_delivery_state" required="">
						<option value="" selected disabled>Delivery Province</option>
						<optgroup label="Canada" id="CA">
							<option value="AB">AB - Alberta</option>
							<option value="BC">BC - British Columbia</option>
							<option value="MB">MB - Manitoba</option>
							<option value="NB">NB - New Brunswick</option>
							<option value="NL">NL - Newfoundland and Labrador</option>
							<option value="NT">NT - Northwest Territories</option>
							<option value="NS">NS - Nova Scotia</option>
							<option value="NU">NU - Nunavut</option>
							<option value="ON">ON - Ontario</option>
							<option value="PE">PE - Prince Edward Island</option>
							<option value="QC">QC - Quebec</option>
							<option value="SK">SK - Saskatchewan</option>
							<option value="YT">YT - Yukon</option>
						</optgroup>
						<optgroup label="USA" id="US">
							<option value="AK">AK - Alaska</option>
							<option value="AL">AL - Alabama</option>
							<option value="AR">AR - Arkansas</option>
							<option value="AZ">AZ - Arizona</option>
							<option value="CA">CA - California</option>
							<option value="CO">CO - Colorado</option>
							<option value="CT">CT - Connecticut</option>
							<option value="DC">DC - District of Columbia</option>
							<option value="DE">DE - Delaware</option>
							<option value="FL">FL - Florida</option>
							<option value="GA">GA - Georgia</option>
							<option value="HI">HI - Hawaii</option>
							<option value="ID">ID - Idaho</option>
							<option value="IL">IL - Illinois</option>
							<option value="IN">IN - Indiana</option>
							<option value="IA">IA - Iowa</option>
							<option value="KS">KS - Kansas</option>
							<option value="KY">KY - Kentucky</option>
							<option value="LA">LA - Louisiana</option>
							<option value="MA">MA - Massachusetts</option>
							<option value="MD">MD - Maryland</option>
							<option value="ME">ME - Maine</option>
							<option value="MI">MI - Michigan</option>
							<option value="MN">MN - Minnesota</option>
							<option value="MO">MO - Missouri</option>
							<option value="MS">MS - Mississippi</option>
							<option value="MT">MT - Montana</option>
							<option value="NC">NC - North Carolina</option>
							<option value="ND">ND - North Dakota</option>
							<option value="NE">NE - Nebraska</option>
							<option value="NH">NH - New Hampshire</option>
							<option value="NJ">NJ - New Jersey</option>
							<option value="NM">NM - New Mexico</option>
							<option value="NV">NV - Nevada</option>
							<option value="NY">NY - New York</option>
							<option value="OH">OH - Ohio</option>
							<option value="OK">OK - Oklahoma</option>
							<option value="OR">OR - Oregon</option>
							<option value="PA">PA - Pennsylvania</option>
							<option value="PR">PR - Puerto Rico</option>
							<option value="RI">RI - Rhode Island</option>
							<option value="SC">SC - South Carolina</option>
							<option value="SD">SD - South Dakota</option>
							<option value="TN">TN - Tennessee</option>
							<option value="TX">TX - Texas</option>
							<option value="UT">UT - Utah</option>
							<option value="VT">VT - Vermont</option>
							<option value="VA">VA - Virginia</option>
							<option value="WA">WA - Washington</option>
							<option value="WV">WV - West Virginia</option>
							<option value="WI">WI - Wisconsin</option>
							<option value="WY">WY - Wyoming</option>
						</optgroup>
					</select>
					<label for="mml_delivery_state" class="mml_placeholder">Delivery Province</label>
					<div class="mml_arrow-down"></div>
				</div>
				<div class="mml_col-xs-12 mml_control mml_field_wrap mml_deliverycity_wrap">
					<input type="text" name="mml_delivery_city" placeholder="Delivery City" autocomplete="off" class="mml_col-xs-12 mml_delivery_city" max="200" value="" required="" autocapitalize="off" />
					<label for="mml_delivery_city" class="mml_placeholder">Delivery City</label>
					<span class="mml_distance"></span>
				</div>
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
					<option disabled>-------------</option>
					<option value="7">Office Move</option>
					<option value="7">Commercial Move</option>
				</select>
				<label for="mml_size" class="mml_placeholder">Selected Home Size</label>
				<div class="mml_arrow-down"></div>
			</div>

			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_name_wrap">
				<input type="text" name="mml_customer_name" max="200" placeholder="First &amp; Last Name" autocomplete="off" class="mml_col-xs-12" value="" required="" autocapitalize="off" />
				<label for="mml_customer_name" class="mml_placeholder">First &amp; Last Name</label>
			</div>

			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_phone_wrap">
				<input type="tel" name="mml_customer_phone" placeholder="Your Phone" autocomplete="off" class="mml_col-xs-12" min="6" max="200" value="" required="" />
				<label for="mml_customer_phone" class="mml_placeholder">Your Phone</label>
			</div>

			<div class="mml_col-xs-12 mml_control mml_field_wrap mml_email_wrap">
				<input type="email" name="mml_customer_email" max="200" placeholder="Your Email" autocomplete="off" class="mml_col-xs-12" value="" required="" />
				<label for="mml_customer_email" class="mml_placeholder">Your Email</label>
			</div>
		</div>
		<div class="mml_col-xs-12 mml_termstoagree clearfix">
			<div class="mml_termstoagree-wrap mml_col-xs-12 clearfix">
				<label class="mml_termslabel"><input class="mml_termstoagree" type="checkbox"> I agree to the terms of service<span class="mml_infoicon"></span></label>
			</div>
		</div>
	</div>
	<div class="mml_hidden">
		<input type="hidden" name="from_to_country" value="CA">
		<input type="hidden" name="to_country">
	</div>
	<div class="mml_popup mml_popup_termsofservice mml_col-xs-12" style="display: none;">
		<div class="mml_close mml_closeX">x</div>
		<div class="mml_popup_header">Terms of service</div>
		<div class="mml_popup_body">Upon submitting the quote form, I consent my personal data to be collected and stored by the hosting website, shared with reliable third parties (like professional moving companies) and used for quoting purposes. I accept to be contacted by professional movers via email or phone, including autodialed or pre-dialed calls, in order to receive accurate moving quotes and other information regarding my move. This free service is provided as a courtesy by moveadvisor.com for your convenience. If you later request your data to be corrected or removed from our database, please contact us at the email found in the contact us or about page of the hosting website.<span class="mml_linkservices"></span></div>
	</div>
</div>