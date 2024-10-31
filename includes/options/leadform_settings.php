<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* MoveAdvisor Lead Form Settings Page
*/
class MML_LeadFormSettings
{
	private $key;
	private $key_len = 40;
	private $demokey = 'demokey';

	/* STATUS OF THE MML API
	 * get_option('mml_leadform_option_hasapi') -> Stores the state of the MML API
	 *	0 = invalid api
	 *	1 = valid API
	 *	2 = demo key
	 */
	
	function __construct()
	{
		add_action('admin_menu', array( $this, 'mml_leadform_create_menu')) ;
		add_action('admin_head', array( $this, 'mml_notice_check'));
		add_action('admin_enqueue_scripts', array( $this, 'mw_enqueue_color_picker'));
		
		$this->setMMLKey( get_option('mml_leadform_option_apikey') );

	}

	function mml_leadform_create_menu()
	{
        $page_title = __('MoveAdvisor - Moving Leads Form Settings', MML_WPLANG);
        $menu_title = __('Moving Leads', MML_WPLANG);
		//create new top-level menu
		add_menu_page(
            $page_title,	                                // $page_title
            $menu_title,									// $menu_title
			'administrator',								// $capability
			'mml_leadform_dashboard',						// $menu_slug
			array( $this, 'mml_leadform_settings_page'),	// $callback
			MML_LEADFORM_URL . 'assets/img/admin-logo-16x16.svg'	// $icon_url
		);

		// Set Default
		if (get_option('mml_leadform_option_hasapi') === false) { update_option('mml_leadform_option_hasapi', 0); }
		if (get_option('mml_leadform_option_formtitle') === false) { update_option('mml_leadform_option_formtitle', 'Free Moving Estimate'); }
		if (get_option('mml_leadform_option_privacy') === false) { update_option('mml_leadform_option_privacy', 1); }
		if (get_option('mml_leadform_option_buttoncolor') === false) { update_option('mml_leadform_option_buttoncolor', '#f27208'); }
		if (get_option('mml_leadform_option_formbg') === false) { update_option('mml_leadform_option_formbg', 0); }
		if (get_option('mml_leadform_option_formbgcolor') === false) { update_option('mml_leadform_option_formbgcolor', '#f5f5f5'); }
        if (get_option('mml_leadform_option_selectlang') === false) { update_option('mml_leadform_option_selectlang', 'EN'); }
		//call register settings function
		add_action( 'admin_init', array( $this, 'mml_leadform_register_plugin_settings') );
	}

	function mml_leadform_register_plugin_settings()
	{
		//register our settings
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_apikey', array($this, 'validate_apikey') );
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_googlekey', array($this, 'validate_googleapikey') );
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_selecttheme' );
        register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_selectlang' );
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_formtitle', array($this, 'validate_buttontext') );
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_titlealignment' );
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_buttontext', array($this, 'validate_buttontext') );
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_thankyou');
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_trackform');
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_privacy' );
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_buttoncolor');
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_devadminbox');
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_formbg');
		register_setting( 'mml_leadform_settings_group', 'mml_leadform_option_formbgcolor');
	}


	// Validate the API Key
	public function validate_apikey($input)
	{
        $mml_lang = MML_WPLANG;

		$input = filter_var(trim($input), FILTER_SANITIZE_STRIPPED);

		if (strlen($input) === $this->key_len ) {

			$mml_leads_url = 'https://portal.moveadvisor.com/services/leads/post';

			$data = array(
				'key'   => $input
			);
			$response = wp_remote_post(
				$mml_leads_url,
				array(
					'method' => 'POST',
					'body' => $data
				)
			);

			// API is invalid
			if ( $response['status'] == "error" && $response["errors"][0] == 'Please provide a valid API Key' )
			{
				// Update API status - false
				update_option('mml_leadform_option_hasapi', 0);

				$call_error = __('The MoveAdvisor API Key you have entered is invalid. Please contact MoveAdvisor.com to get a working API Key.', $mml_lang);
				// Call an error
				add_settings_error(
					'invalidAPIKey',
					'error',
                    $call_error,
					'error');
				return '';
			} else {
				// API is valid
				$this->setMMLKey($input);
				update_option('mml_leadform_option_hasapi', 1);

				// Save the API
				return sanitize_key( $input );
			}

		} else if ($input === $this->demokey) {

			// API is a demo
			update_option('mml_leadform_option_hasapi', 2);

			$settings_error = __('You are using a Demo Key. Please contact MoveAdvisor.com to get a working API Key.', $mml_lang);
			// Call Demo Key warning
			add_settings_error(
				'usingDemoKey',
				'warning',
                $settings_error,
				'warning');

			return $this->demokey;
		} else {

			// API is invalid
			update_option('mml_leadform_option_hasapi', 0);
            $contact_mml = filter_var( 'https://moveadvisor.com/about?utm_source=MML_Plugin&utm_medium=Settings_GetAPI',  FILTER_SANITIZE_URL );
			$error = sprintf(__('The MoveAdvisor API Key is either missing or invalid. Please <a href="%s" target="_blank">contact MoveAdvisor.com</a> to get a working API Key.', $mml_lang), $contact_mml);
			// Invalid API error call
			add_settings_error(
				'invalidAPIKey',
				'error',
				$error,
				'error');
			return '';
		}
	}

	public function validate_googleapikey($input) {
		$mml_google_url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input=Boston&key='.$input;

		$response = wp_remote_get($mml_google_url);

		$json = json_decode($response['body'], true);
		if ($json['status'] == 'OK') {
			return $input;
		} else {
			add_settings_error(
				'invalidGoogleAPIKey',
				'error',
				'Google Error: '.$json['error_message'],
				'error');
			return '';
		}

		return $input;
	}


	// Clean up the input for the text button, from the Settings menu
	public function validate_buttontext($input) {
		return sanitize_text_field( $input );
	}


	// Setter for API key
	private function setMMLKey($key) {
		return $this->key = $key;
	}


	// Getter for API key
	public function getMMLKey() {
		return $this->key;
	}

	// Admin Notification check
	function mml_notice_check() {
		global $pagenow;
		if ( $pagenow == 'index.php' || $pagenow == 'plugins.php' ) {

			if ($this->key == $this->demokey) {
				add_action( 'admin_notices', array($this,'mml_notice_demokey') );
			} else if ($this->key === '' || $this->key === null) {
				add_action( 'admin_notices', array($this,'mml_notice_apineeded') );
			}

		}
	}

	// Admin notification when you use Demo Key
	function mml_notice_demokey() {
        $mml_lang = MML_WPLANG;
        $plugin_settings = filter_var( 'admin.php?page=mml_leadform_dashboard',  FILTER_SANITIZE_URL );
	?>
		<div class="notice notice-warning is-dismissible">
			<p><?php sprintf(__('You are using a Demo Key for MoveAdvisor Form. To have a working form, <strong>\'please enter a valid API key\ in the</strong> <a href="%s" target="_blank">Plugin Settings</a> page.', $mml_lang), $plugin_settings);?></p>
		</div>
	<?php
	}

	// Admin notificiation when you do not have a valid API Key
	function mml_notice_apineeded() {
        $mml_lang = MML_WPLANG;
        $plugin_settings = filter_var( 'admin.php?page=mml_leadform_dashboard#mml-api-key-field',  FILTER_SANITIZE_URL );
	?>
		<div class="notice notice-error is-dismissible">
			<p><?php sprintf(__('To use the MoveAdvisor Form, <strong>please enter a valid API key</strong> in the <a href="%s" target="_blank">Plugin Settings</a> page.', $mml_lang), $plugin_settings);?></p>
		</div>
	<?php
	}


	// THE SETTINGS PAGE OUTPUT
	function mml_leadform_settings_page() {

		/* SETTINGS PAGE FORM
		 *
		 * - Do NOT change method and action of forms.
		 * - Do NOT change names of fields.
		 * - Do NOT change values of fields.
		 * - Do NOT use <button> tags.
		 * - Can add classes.
		 * - Avoid changing IDs.
		 * - Avoid more then one submit_button() on page.
		 * - Avoid moving out of form tags input/select fields.
		 */

        $mml_lang = MML_WPLANG;

        // Links
        if ($mml_lang == 'de') {
        	$default_privicy_policy = filter_var( '/wp-content/plugins/mymovingloads-leads-form/assets/files/mml-tou-pp-de.htm',  FILTER_SANITIZE_URL );
        }
        else{
        	$default_privicy_policy = filter_var( '/wp-content/plugins/mymovingloads-leads-form/assets/files/mml-tou-pp.htm',  FILTER_SANITIZE_URL );
        }
        
        $get_api_key = filter_var( 'https://moveadvisor.com/about?utm_source=MML_Plugin&utm_medium=Settings_GetAPI',  FILTER_SANITIZE_URL );
        $api_key_questions = filter_var( 'https://moveadvisor.com/biz/wp-lead-form#Google_API_Key_Questions',  FILTER_SANITIZE_URL );
        $get_api_tutorial = filter_var( 'https://moveadvisor.com/biz/wp-lead-form#How_can_I_get_a_Google_API_key',  FILTER_SANITIZE_URL );
        $api_track_info = filter_var( 'https://moveadvisor.com/biz/wp-lead-form#What_does_the_8220Track_Form8221_functionality_do',  FILTER_SANITIZE_URL );
        $about_the_plugin = filter_var( 'https://moveadvisor.com/biz/wp-lead-form',  FILTER_SANITIZE_URL );
        $api_providers = filter_var( 'https://portal.moveadvisor.com/providers/',  FILTER_SANITIZE_URL );
		?>
	<?php if (get_option('mml_leadform_option_hasapi') === '0') { ?>
		<div class="mml_keynotice mml_keynotice-noapi">
            <?php echo sprintf(__('To use the MoveAdvisor Form, <strong>please enter a valid API key</strong>. To get one, please <a href="%s" target="_blank">contact MoveAdvisor</a>.', $mml_lang), $get_api_key);?>
		</div>
	<?php } else if (get_option('mml_leadform_option_hasapi') === '2') {?>
	<div class="mml_keynotice mml_keynotice-demokey">
        <?php echo sprintf(__('You are using a Demo Key for MoveAdvisor Form. To have a working form, <strong>please enter a valid API key</strong>. To get one, please <a href="%s" target="_blank">contact MoveAdvisor</a>.', $mml_lang), $get_api_key);?>
	</div>
	<?php } ?>

	<div id="left-content">
		<div class="wrap mml_options">
			<h1><?php _e('Moving Leads Form - General Settings', MML_WPLANG);?></h1>

			<?php
				settings_errors();
				$active_tab = isset( $_GET[ 'tab' ] ) ? $active_tab = $_GET[ 'tab' ] : 'general_settings';
	        ?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=mml_leadform_dashboard&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>"><?php _e('General Settings', $mml_lang);?></a>
				<a href="?page=mml_leadform_dashboard&tab=how_to_use" class="nav-tab <?php echo $active_tab == 'how_to_use' ? 'nav-tab-active' : ''; ?>"><?php _e('How to use', $mml_lang);?></a>
			</h2>

			<?php if ($active_tab != 'how_to_use') { ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'mml_leadform_settings_group' ); ?>
				<?php do_settings_sections( 'mml_leadform_settings_group' ); ?>

				<div class="mml_settings_block mml_apisettings">
					<h1><span class="dashicons dashicons-admin-network"></span><?php _e(' API Keys', $mml_lang);?></h1>
					<table class="form-table mml_table">

						<tr valign="top" class="mml_mml_api">
							<th scope="row"><label for="mml-api-key-field"><?php _e('MoveAdvisor API Key', $mml_lang);?></label></th>
							<td><input type="text" id="mml-api-key-field" name="mml_leadform_option_apikey" value="<?php echo esc_attr( get_option('mml_leadform_option_apikey') ); ?>" />
								<?php if (get_option('mml_leadform_option_hasapi') === '2') { ?>
								<span class="dashicons dashicons-warning mml_icon mml_icon_demokey" title="<?php _e('You using a demo API key for testing purposes.', $mml_lang);?>"></span>
								<?php } else if (get_option('mml_leadform_option_hasapi') === '1') { ?>
								<span class="dashicons dashicons-yes mml_icon mml_icon_valid" title="<?php _e('You using a valid, official API key.', $mml_lang);?>"></span>
								<?php } else { ?>
								<span class="dashicons dashicons-no-alt mml_icon mml_icon_invalid" title="<?php _e('This API key is invalid.', $mml_lang);?>"></span>
								<?php }?>
								<br>
								<em><?php echo sprintf(__('You need a MoveAdvisor API key to have the form submit leads. <a href="%s" target="_blank">Contact MoveAdvisor</a> to get your unique API Key.', $mml_lang), $get_api_key);?></em>
								<br>
								<em><?php _e('Want to test the form first without submitting leads? Enter the following: demokey ', $mml_lang);?></em>
							</td>
						</tr>

						<tr valign="top" class="mml_google_api">
							<th scope="row"><label for="google-api-key-field"><?php _e('Google API Key', $mml_lang);?></label></th>
							<td>
								<input type="text" id="google-api-key-field" name="mml_leadform_option_googlekey" value="<?php echo esc_attr( get_option('mml_leadform_option_googlekey') ); ?>" />
								<div id="add_googleAPI"><?php _e('Add Google API Key', $mml_lang);?></div>
								<br>
								<em><?php echo sprintf(__('Google API is used to <a href="%s" target="_blank">unlock extra design features</a> of your form. See the <a href="%s" target="_blank">simple tutorial on how to get a Google API key</a>.', $mml_lang), $api_key_questions, $get_api_tutorial); ?></em>
							</td>
						</tr>

						<tr valign="top" class="mml_formtracking">
							<th scope="row"><label for="mml_leadform_option_trackform"><?php _e('Track Form', $mml_lang);?></label></th>
							<td>
								<input type="checkbox" id="mml_leadform_option_trackform" name="mml_leadform_option_trackform" value="1" <?php checked(1, get_option('mml_leadform_option_trackform'), true); ?> />
								<em><?php echo sprintf(__('Start <a href="%s" target="_blank">tracking views and submissions of your forms.</a> (you need to have Google Analytics on your website)', $mml_lang), $api_track_info);?></em>
							</td>
						</tr>

						<tr valign="top" class="mml_defaultprivacy">
							<th scope="row"><label for="mml_leadform_option_privacy"><?php _e('Default Policy', $mml_lang);?></label></th>
							<td>
								<input type="checkbox" id="mml_leadform_option_privacy" name="mml_leadform_option_privacy" value="1" <?php checked(1, get_option('mml_leadform_option_privacy'), true); ?> />
								<em><?php echo sprintf(__('Use the <a href="%s" target="_blank">default privacy policy we\'ve designed</a> for your users, or keep unchecked if you already have one.', $mml_lang), $default_privicy_policy) ;?> </em>
							</td>
						</tr>
					</table>
					<h1><span class="dashicons dashicons-admin-customizer"></span><?php _e(' Form Design', $mml_lang);?></h1>
					<table class="form-table mml_table">

						<tr valign="top" class="mml_theme">
							<th scope="row"><label for="form-theme-field"><?php _e('Color Scheme', $mml_lang);?></label></th>
							<td>
								<select id="form-theme-field" name="mml_leadform_option_selecttheme">
									<option value="default" <?php selected( get_option('mml_leadform_option_selecttheme'), "default" ) ?>><?php _e('Blue (Default)', $mml_lang);?></option>
									<option value="theme01" <?php selected( get_option('mml_leadform_option_selecttheme'), "theme01" ); ?>><?php _e('Green', $mml_lang);?></option>
									<option value="theme02" <?php selected( get_option('mml_leadform_option_selecttheme'), "theme02" ); ?>><?php _e('Orange', $mml_lang);?></option>
								</select>
								<br>
								<em><?php _e('Change the border color of all input fields, the background of the autosuggest as well as the dynamic information displayed inside some input fields (for example the notification “About x days left”).', $mml_lang);?></em>
							</td>
						</tr>

                        <tr valign="top" class="mml_language">
                            <th scope="row"><label for="form-lang-field"><?php _e('Language', $mml_lang);?></label></th>
                            <td>
                                <select id="form-lang-field" name="mml_leadform_option_selectlang">
                                    <option value="en" <?php selected( get_option('mml_leadform_option_selectlang'), "en" ); ?>>English</option>
                                    <option value="de" <?php selected( get_option('mml_leadform_option_selectlang'), "de" ); ?>>German</option>
                                </select>
                                <br>
                                <em><?php _e('Set preferred language of the quote form (currently only for the international quote form)', $mml_lang);?></em>
                            </td>
                        </tr>

						<tr valign="top" class="mml_title">
							<th scope="row"><label for="form-title-field"><?php _e('Form Title', $mml_lang);?></label></th>
							<td>
								<input id="form-title-field" type="text" name="mml_leadform_option_formtitle" value="<?php echo esc_attr( get_option('mml_leadform_option_formtitle') ); ?>" placeholder="<?php _e('No title by default.', $mml_lang);?>" />
								<br>
								<em><?php _e('(Optional) Add title to your lead forms or leave blank for no title.', $mml_lang);?></em>
							</td>
						</tr>

						<tr valign="top" class="mml_title_align">
							<th scope="row"><label for="form-titlealign-field"><?php _e('Title Aligment', $mml_lang);?></label></th>
							<td>
								<select id="form-titlealign-field" name="mml_leadform_option_titlealignment">
									<option value="center" <?php selected( get_option('mml_leadform_option_titlealignment'), "center" ) ?>><?php _e('Center (Default)', $mml_lang);?></option>
									<option value="left" <?php selected( get_option('mml_leadform_option_titlealignment'), "left" ); ?>><?php _e('Left', $mml_lang);?></option>
									<option value="right" <?php selected( get_option('mml_leadform_option_titlealignment'), "right" ); ?>><?php _e('Right', $mml_lang);?></option>
								</select>
								<br>
								<em><?php _e('(Optional) Adjust the title alignment. Default is center. The alignment does not affect widget title.', $mml_lang);?></em>
							</td>
						</tr>

						<tr valign="top" class="mml_button">
							<th scope="row"><label for="form-button-field"><?php _e('Button Text', $mml_lang);?></label></th>
							<td>
								<input id="form-button-field" type="text" name="mml_leadform_option_buttontext" value="<?php echo esc_attr( get_option('mml_leadform_option_buttontext') ); ?>" placeholder="<?php _e('Default: Get My Free Quote', $mml_lang);?>" />
								<br>
								<em><?php _e('(Optional) Add the text that should be on the button or leave blank for default text, which is designed to convert best.', $mml_lang);?></em>
							</td>
						</tr>

						<tr valign="top" class="mml_button">
							<th scope="row"><label for="form-button-field-color"><?php _e('Button Color', $mml_lang);?></label></th>
							<td>
								<input id="form-button-field-color" type="text" name="mml_leadform_option_buttoncolor" value="<?php echo esc_attr( get_option('mml_leadform_option_buttoncolor') ); ?>" data-default-color="#f27208" placeholder="#f27208" />
								<em><?php _e('(Optional) Add the color that should be on the button or click default, which is designed to convert best.', $mml_lang);?></em>
							</td>
						</tr>

						<tr valign="top" class="mml_formbgcolor">
							<th scope="row"><label for="mml_leadform_option_formbg"><?php _e('Background', $mml_lang);?></label></th>
							<td>
								<input type="checkbox" id="mml_leadform_option_formbg" name="mml_leadform_option_formbg" value="1" <?php checked(1, get_option('mml_leadform_option_formbg'), true); ?> />
								<em><?php _e('Set the background of the form.', $mml_lang);?></em>
								<div id="bg_cp_options" <?php echo (get_option('mml_leadform_option_formbg') == 1) ? '' : 'class="hide_div"'; ?>>
									<input id="form-background-field-color" type="text" name="mml_leadform_option_formbgcolor" value="<?php echo esc_attr( get_option('mml_leadform_option_formbgcolor') ); ?>" data-default-color="#f5f5f5" placeholder="#f5f5f5" />
								</div>
							</td>
						</tr>

						<tr valign="top" class="mml_thankyou">
							<th scope="row"><label for="form-thankyou"><?php _e('Thank You Text', $mml_lang);?></label></th>
							<td>
								<textarea id="form-thankyou" name="mml_leadform_option_thankyou" placeholder="<?php _e('Default: Thank you. You will hear from us soon.', $mml_lang);?>"><?php echo get_option('mml_leadform_option_thankyou'); ?></textarea>
								<br>
								<em><?php _e('(Optional) Customize your Thank You message when the form is submitted or leave blank for default text.', $mml_lang);?></em>
							</td>
						</tr>

					</table>

					<table class="form-table mml_table">
						<tr valign="top" class="mml_devadmin">
							<th scope="row"><label for="mml_leadform_option_devadminbox"><?php _e('Admin Box', $mml_lang);?></label></th>
							<td>
								<input type="checkbox" id="mml_leadform_option_devadminbox" name="mml_leadform_option_devadminbox" value="1" <?php checked(1, get_option('mml_leadform_option_devadminbox'), true); ?> />
								<em><?php _e('Disable the yellow admin box on top of the form.', $mml_lang);?></em>
							</td>
						</tr>

					</table>
					<?php submit_button(__('Save Changes', $mml_lang), 'primary large', 'mml_leadform_submit_button'); ?>
				</div>
			</form>
			<?php } else { ?>

			<div class="mml_settings_block mml_howto">
				<h1><span class="dashicons dashicons-welcome-learn-more"></span><?php _e(' How to Use the Form', $mml_lang);?></h1>
				<br>
				<h2><?php _e('Enter demo key', $mml_lang);?></h2>
				<p><?php _e('To see and test how the form displays on your website, please do the following:', $mml_lang);?>
				<ol>
					<li><?php _e('Enter the demo API key (for testing purposes only). Simply copy this keyword: ', $mml_lang);?>
                        <input type="text" onfocus="this.select();" readonly="readonly" class="code" value="demokey"><?php _e(' and paste it into the MoveAdvisor API key section in the "General Settings" tab.', $mml_lang);?></li>
					<li><?php _e('After pasting, click the "Save Changes" button to commit changes.', $mml_lang);?></li>
				</ol>
				<p><?php _e('Now you are <strong>ready to place the form on your website for testing purposes</strong>, it will not be submitting moving leads to our servers.', $mml_lang);?></p>
				<br>
				<h2><?php _e('Show the test form in your website', $mml_lang);?></h2>
				<p><?php _e('There are 3 ways to include the form into your website.', $mml_lang);?></p>
				<h3><?php _e('Place in a Page or Post', $mml_lang);?></h3>
				<h4><?php _e('Show automatic form type based on location detection (recommended)', $mml_lang);?></h4>
				<p><?php _e('To have the form automatically show the right version of the form based on the user’s location, edit the page or post which should have the form and paste in this shortcode:', $mml_lang);?><br>
					<input type="text" onfocus="this.select();" readonly="readonly" class="code" value="[mml_leadform]"></p>
				<h4><?php _e('Show a specific type of form without location detection', $mml_lang);?></h4>
				<p><?php _e('In rare cases, you might want to show a specific type of form without taking into account the user’s location, you can use the following shortcodes:', $mml_lang);?></p>
				<p><strong><?php _e('US version of the form', $mml_lang);?></strong>:<br>
					<input type="text" style="width:250px;" onfocus="this.select();" readonly="readonly" class="code" value='[mml_leadform country="us"]'></p>
				<p><strong><?php _e('Canadian version of the form', $mml_lang);?></strong>:<br>
					<input type="text" style="width:250px;" onfocus="this.select();" readonly="readonly" class="code" value='[mml_leadform country="ca"]'></p>
				<p><strong><?php _e('Australian version of the form', $mml_lang);?></strong>:<br>
					<input type="text" style="width:250px;" onfocus="this.select();" readonly="readonly" class="code" value='[mml_leadform country="au"]'></p>
				<p><strong><?php _e('UK’s version of the form', $mml_lang);?></strong>:<br>
					<input type="text" style="width:250px;" onfocus="this.select();" readonly="readonly" class="code" value='[mml_leadform country="uk"]'></p>
				<p><strong><?php _e('New Zealand version of the form', $mml_lang);?></strong>:<br>
					<input type="text" style="width:250px;" onfocus="this.select();" readonly="readonly" class="code" value='[mml_leadform country="nz"]'></p>
				<p><strong><?php _e('German version of the form', $mml_lang);?></strong>:<br>
					<input type="text" style="width:250px;" onfocus="this.select();" readonly="readonly" class="code" value='[mml_leadform country="de"]'></p>
				<p><strong><?php _e('International version of the form', $mml_lang);?></strong>:<br>
					<input type="text" style="width:250px;" onfocus="this.select();" readonly="readonly" class="code" value='[mml_leadform country="int"]'></p>

				<h3><?php _e('Place in the Sidebar', $mml_lang);?></h3>
				<p><?php _e('Click on Appearance > Widgets from the sidebar of this admin panel. You will find the widget called "MoveAdvisor Lead Form". Simply drag and drop it into your "Sidebar" section for the form to appear in your sidebar.', $mml_lang);?></p>

				<?php if (get_option('mml_leadform_option_hasapi') !== '1') { ?>
				<p><?php echo sprintf(__('<i><strong>Please note that you need a MoveAdvisor API key to have the form working on this website. To get one, please <a href="%s" target="_blank">contact MoveAdvisor</a>.</strong></i>', $mml_lang), $get_api_key);?></p>
				<?php } ?>
				<h3><?php _e('Inserting the form into a template file (for advanced users)', $mml_lang);?></h3>
				<p><?php _e('To insert the form into a template file, use the following PHP in your file: do_shortcode(‘[mml_leadform]’)', $mml_lang);?></p>
				<p><?php echo sprintf(__('<strong>Need help placing the form in your website? <a href="%s" target="_blank">See more details</a></strong>', $mml_lang), $about_the_plugin);?></p>
				<br>
				<h2><?php _e('I\'ve tested the form with the demo key and it looks great, now what?', $mml_lang);?></h2>
				<p><?php echo sprintf(__('Already integrated the form and ready to get leads? Please <a href="%s" target="_blank">contact us here</a> to get an official, valid API key to start receiving leads.', $mml_lang), $get_api_key);?></p>
				<p><a class="button button-primary button-large" href="https://moveadvisor.com/about?utm_source=MML_Plugin&utm_medium=Settings_GetAPI" target="_blank"><?php _e('Get an official MoveAdvisor API key', $mml_lang);?></a></p>
			</div>

			<?php } // Close tab if ?>
		</div>

		<?php
		/*
		 * FEEDBACK
		 * The feedback option is available only if there is a valid API key.
		 */
		?>
	</div>
	<div id="right-content">
		<div class="wrap">
			<h1><?php _e('Additional Info', $mml_lang);?></h1>
			<div class="mml_settings_block">
				<h2><?php _e('Need help?', $mml_lang);?></h2>
				<p><?php echo sprintf(__('No worries, we\'ve got you covered. Your question is probably answered in the <a href="%s" target="_blank">detailed plugin documentation</a>.', $mml_lang), $about_the_plugin);?></p>
				<hr>
				<h2><?php _e('See the leads you\'ve generated', $mml_lang);?></h2>
				<p><?php echo sprintf(__('Now you can see all the leads you\'ve generated in your own <a href="%s" target="_blank">MoveAdvisor administration panel here</a>.', $mml_lang), $api_providers);?></p>
				<p><a class="button button-primary button-large" href="https://portal.moveadvisor.com/providers/" target="_blank"><?php _e('Login to MoveAdvisor Admin', $mml_lang);?></a></p>
		<?php if (get_option('mml_leadform_option_hasapi') === '1') { ?>
				<hr>
				<form id="mml_feedback" method="post" action="<?php echo MML_LEADFORM_URL . 'includes/options/mml_feedback.php'; ?>">
					<h2><span class="dashicons dashicons-smiley"></span><?php _e(' Feedback &amp; Suggestions', $mml_lang);?></h2>
					<p><?php _e('We would love to hear what you think about our plugin. Drop us a comment or send us an email to share your experience. Any feedback and suggestions are always appreciated.', $mml_lang);?></p>
					<p><a class="button button-primary button-large" href="https://moveadvisor.com/about" target="_blank"><?php _e('Leave us feedback', $mml_lang);?></a></p>
				</form>
		<?php } ?>
			</div>
		</div>
	</div>

	<?php } // Close setting page function

	function mw_enqueue_color_picker( $hook_suffix ) {
		// first check that $hook_suffix is appropriate for your admin page
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'my-script-handle', MML_LEADFORM_URL.'assets/js/settings.js', array( 'wp-color-picker' ), false, true );
	}

}

$mml_leadform_settings = new MML_LeadFormSettings();

?>