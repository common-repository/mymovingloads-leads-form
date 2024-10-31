<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Class that collects and defines all shortcodes that you will need ... Ever.
*/
class MML_LeadForm_Shortcodes
{
	private $google_key;	// Google API key
	private $hasapi;		// Is there a valid API for MML (0 = invalid; 1 = valid; 2 = test)
	private $form_abr;		// The country the form is right now
	private $custom_api;	// If the person had added the api in the shortcode
    private $language;

	function __construct()
	{
		$this->google_key = get_option('mml_leadform_option_googlekey');	// Set the Google API key
		$this->hasapi = get_option('mml_leadform_option_hasapi');			// Set the status of the API

		add_shortcode('mml_leadform', array( $this, 'mml_leadform_shortcode_fun' ) );	// Define the shortcode
		add_action( 'wp_enqueue_scripts', array( $this, 'mml_leadform_register_scripts' ) ); // Call scripts

		// ajax for logged in users
		add_action( 'wp_ajax_mml_ajax_action', array( $this, 'mml_ajax_action' ) );
		// ajax for not logged in users
		add_action( 'wp_ajax_nopriv_mml_ajax_action', array( $this, 'mml_ajax_action') );

		add_filter('script_loader_tag', array( $this, 'add_async_defer_attribute'), 10, 2);
	}


	/* GET USER IP
	 * User IP is used for country detection and form validation.
	 */

	function getRealIP()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))				//check ip from share internet
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))	//to check ip is pass from proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	/* GET USER COUNTRY
	 * Detecting the user country by IP allows us to server the correct form.
	 */

	function getUserCountry()
	{
		global $wpdb;

		$user_ip = $this->getRealIP();

		$request = sprintf("" .
			"SELECT
				country
			FROM
				mml_countries
			WHERE
				ip < INET_ATON('%s')
			ORDER BY
				ip DESC
			LIMIT 0,1", $user_ip);

		$country = $wpdb->get_row($request);
		$result = $country->country;

		return $result;
	}

	/* SHORTCODE FUNCTION
	 * This is the code that executes when the shortcode is called.
	 * All the attributes are completely optional.
	 * 
	 * Width: value with px or %.
	 *		Put in style attribute to customize the width of the form.
	 * Country: us/ca/uk/int/de
	 * 		Force a form to show and ignore user country.
	 */

	function mml_leadform_shortcode_fun( $atts )
	{

		$user_country = $this->getUserCountry();
        $preferredLanguage = get_option('mml_leadform_option_selectlang');
		$opts = shortcode_atts(
			array(
				'width'		=> '',
				'country'	=> '',
				'widget'	=> false
			),
			$atts,
			'mml_leadform'
		);

		$is_forced;  // Tracks if user wants a certain country form

        if($opts['country'] === '') {
            $opts['country'] = $user_country;
            $is_forced = false;
        } else {
            $opts['country'] = strtolower($opts['country']);
            $is_forced = true;
        }

        switch ($preferredLanguage) {
            case 'en': $this->language = 'en'; break;
            case 'de': $this->language = 'de'; break;
            case 'fr': $this->language = 'fr'; break;
            case 'es': $this->language = 'es'; break;
            default:
                $this->language = 'en'; break;
        }

        switch ($opts['country']) {
            case 'usa': $this->form_abr = 'us'; break;
            case 'us': $this->form_abr = 'us'; break;
            case 'uk': $this->form_abr = 'uk'; break;
            case 'gb': $this->form_abr = 'uk'; break;
            case 'ca': $this->form_abr = 'ca'; break;
            case 'int':$this->form_abr = 'int';break;
            case 'au': $this->form_abr = 'au'; break;
            case 'nz': $this->form_abr = 'nz'; break;
            case 'de': $this->form_abr = 'de'; break;
            default:
                $this->form_abr = 'int'; break;
        }

        if (file_exists(MML_LEADFORM_DIR . 'includes/templates/' . $this->language . '/mml_leadform_' . $this->form_abr . '.html.php')) {
            $form_body = MML_LEADFORM_DIR . 'includes/templates/' . $this->language . '/mml_leadform_' . $this->form_abr . '.html.php';
        }
        elseif (file_exists(MML_LEADFORM_DIR . 'includes/templates/en/mml_leadform_' . $this->form_abr . '.html.php')){
            $form_body = MML_LEADFORM_DIR . 'includes/templates/en/mml_leadform_' . $this->form_abr . '.html.php';
        }
        else {
            $form_body = MML_LEADFORM_DIR . 'includes/templates/en/mml_leadform_int.html.php';
        }

        $form_width_style = ( intval($opts['width']) > 0 ? 'width:'.$opts['width'] : '' );
		$output = '';

		$current_user = wp_get_current_user();
		if ( is_user_logged_in() && (user_can( $current_user, 'administrator' ))) {
			if ($this->hasapi == '0') {
				$output .= '
					<div class="mml_adminwarning demo_warning">
						You need a MoveAdvisor API key to have the form working on this website. To get one, please <a href="https://moveadvisor.com/about" target="_blank">contact MoveAdvisor</a>.
					</div>';	
			} else {
				if (get_option('mml_leadform_option_devadminbox') == 1) {
					$dev_show = 'mml_hide';
				} else {
					$dev_show = 'mml_show';
				}
				$output .= '
					<div class="mml_admin_block '.$dev_show.'">
						<div class="mml_close mml_closeX">x</div>
						<div class="mml_innertext">
							<a href="https://portal.moveadvisor.com/providers/">
								Go to your MoveAdvisor account. (message seen only by you).
							</a>
						</div>
					</div>';
			}
			$is_admin = 'mml_isadmin ';
		} else {
			$is_admin = '';
		}

		if (get_option('mml_leadform_option_buttoncolor') != '#f27208') {
			$submit_custom_colour_class = 'mml_customsubmit';
			$submit_custom_colour_option = 'style="background:'.get_option('mml_leadform_option_buttoncolor').';"';
		} else {
			$submit_custom_colour_class = 'mml_defaultsubmit';
			$submit_custom_colour_option = '';
		}

		if ( get_option('mml_leadform_option_formbg') == 1 ) {
			$form_bg_class = 'mml_formbackground';
			$form_bg_color = 'background:'.get_option('mml_leadform_option_formbgcolor').';"';
		} else {
			$form_bg_class = '';
			$form_bg_color = '';
		}

        $mml_lang = MML_WPLANG;

        if (get_option('mml_leadform_option_thankyou') != '') {
            $thankyou_text = get_option('mml_leadform_option_thankyou');
        } else {
            $thankyou_text = __('Thank you. You will hear from us soon.', $mml_lang);
        }

        if (get_option('mml_leadform_option_buttontext') != '') {
            $from_options_button_text = htmlspecialchars(get_option('mml_leadform_option_buttontext'));
        } else {
            $from_options_button_text = __('Get My Free Quote', $mml_lang);
        }

        $secured_text = __('Your information is 100% Secure.',  $mml_lang);

		$output .= '
		<div class="mml_leadform_wrapper '.$form_bg_class.' mml_leadform_'.$this->form_abr.'" style="'.$form_width_style.$form_bg_color.'">';

		if (!$opts['widget']) {
			if ( get_option('mml_leadform_option_titlealignment') != null) {
				$title_alignment = 'text-align: '.get_option('mml_leadform_option_titlealignment');		
			} else {
				$title_alignment = 'text-align: center';
			}

			$output .= '<div class="mml_formtitle" style="'.$title_alignment.'">'.htmlspecialchars(get_option('mml_leadform_option_formtitle')).'</div>';
		}
		$output .= '
			<form action="" autocomplete="off" method="POST"
				class="'.$is_admin.'mml_form mml_form_' . $this->form_abr . '"
				data-abr="'.$this->form_abr.'" data-post="'.get_the_ID().'">

				<div class="mml_error clearfix"></div>
				<div class="mml_row clearfix">
					<div class="mml_body-wrap">
		';

		ob_start();
		include ($form_body);
		$output.= ob_get_clean();

		$output.= '
					</div>
					<input type="email" class="mml_leadform_3198637" />
					<div class="mml_hidden-xs-12">
						<div class="mml_row">
							<input type="hidden" name="mml_dateiso" />
							<input type="hidden" name="failed" />
						</div>
					</div>
					<div class="mml_col-xs-12 mml_submit_wrap">
						<input type="submit"
							value="'.$from_options_button_text.'"
							class="
								mml_btn mml_col-xs-12 mml_submit-btn mml_shine '
								.$submit_custom_colour_class.'" '
								.$submit_custom_colour_option.' />
					</div>
					<div class="mml_privacy-secure"><img class="mml_secureicon" src="'.MML_LEADFORM_URL .'assets/img/privacy-icon.png" /> '.$secured_text.'</div>
				</div>
			</form>';


		$output.= '<div class="mml_leadform_success">'.$thankyou_text.'</div>'; // Thank you div

		$output.= '<div class="mml_leadform_phonecall_success">
						<div class="mml_leadform_success_phone">
							<div class="mml_leadform_success_phone_wait">'. __('Wait!',  $mml_lang) .'</div>
							<p>'. __('an agent is calling you now from',  $mml_lang) .' </p>
							<div id="call-transfer-number-success" class="mml_leadform_success_phone_phone_num">+1 (800) 680-6439</div>
							<ul>
								<li></li>
								<li></li>
								<li></li>
							</ul>
							<p>'. __('Please pick up your phone to be connected with an agent to give you a personalized quote based on your move details.',  $mml_lang) .'</p>
							<img class="mml_leadform_success_phone_icon " src="https://www.mymovingreviews.com/img/miscellaneous/phone.svg"/>
						</div>
					</div>';

        $output.= '</div>'; // Close the main shortcode wrap

		return $output;
	}


	/* AJAX REQUEST
	 * This is the function that executes when you post the form.
	 * This connects to the MoveAdvisor API.
	 */
	function mml_ajax_action()
	{
		check_ajax_referer('mml-ajax-nonce','security', true);	// nonce

		if ($this->hasapi === '2')
		{
			// Demo Key Test
			echo json_encode(array('status' => 'success', 'text' => 'Demo Test Successful.'));

		} else if($this->hasapi === '1') {

			if (isset($_POST)) {
				$value_arr = array();
				foreach ($_POST['form_data'] as $key => $value) {
					$value_arr[$key] = $value;
				}

				$error_arr = array();

				// Valid name
				if (isset($value_arr['customer_name'])) {
					$customer_name = filter_var(trim($value_arr['customer_name']), FILTER_SANITIZE_STRIPPED);
				} else {
					$error_arr[] = "Please enter name";
				}

				// Valid Phone
				if (isset($value_arr['customer_phone'])) {
					$customer_phone = filter_var(trim($value_arr['customer_phone']), FILTER_SANITIZE_NUMBER_INT);
				} else {
					$error_arr[] = "Please enter phone";
				}

				// Valid Email
				if (isset($value_arr['customer_email']) && filter_var($value_arr['customer_email'], FILTER_VALIDATE_EMAIL)) {
					$customer_email = filter_var(trim($value_arr['customer_email']), FILTER_SANITIZE_EMAIL);
				} else {
					$error_arr[] = "Please enter email";
				}

				// Valid Country
				if (isset($value_arr['from_country'])) {
					$from_country = filter_var(trim($value_arr['from_country']), FILTER_SANITIZE_STRIPPED);
				} else {
					$error_arr[] = "Please select from country";
				}

				// Valid Postcode
				if (isset($value_arr['from_postal_code'])) {
					$from_postal_code = filter_var(trim($value_arr['from_postal_code']), FILTER_SANITIZE_STRIPPED);
				} else {
					$from_postal_code = '';
				}

				// Valid State
				if (isset($value_arr['from_state'])) {
					$from_admin1_code = filter_var(trim($value_arr['from_state']), FILTER_SANITIZE_STRIPPED);
				} else {
					$from_admin1_code = '';
				}

				// Valid Place
				if (isset($value_arr['from_place'])) {
					$from_place = filter_var(trim($value_arr['from_place']), FILTER_SANITIZE_STRIPPED);
				} else {
					$from_place = '';
				}

				// Valid Country
				if (isset($value_arr['to_country'])) {
					$to_country = filter_var(trim($value_arr['to_country']), FILTER_SANITIZE_STRIPPED);
				} else {
					$error_arr[] = "Please enter to country";
				}
				if (isset($value_arr['to_postal_code'])) {
					$to_postal_code = filter_var(trim($value_arr['to_postal_code']), FILTER_SANITIZE_STRIPPED);
				} else {
					$to_postal_code = '';
				}
				if (isset($value_arr['to_state'])) {
					$to_admin1_code = filter_var(trim($value_arr['to_state']), FILTER_SANITIZE_STRIPPED);
				} else {
					$to_admin1_code = '';
				}
				if (isset($value_arr['to_place'])) {
					$to_place = filter_var(trim($value_arr['to_place']), FILTER_SANITIZE_STRIPPED);
				} else {
					$to_place = '';
				}


				if ( isset($value_arr['size']) && filter_var($value_arr['size'], FILTER_VALIDATE_INT, array("options" => array("min_range" => 1, "max_range" => 7))) ) {
					$size = trim($value_arr['size']);
				} else {
					$error_arr[] = "Please enter size";
				}
				if (isset($value_arr['moving_date'])) {
					$moving_date = filter_var(trim($value_arr['moving_date']), FILTER_SANITIZE_STRIPPED);
				} else {
					$error_arr[] = "Please enter moving date";
				}

				if (isset($value_arr['parent_webpage'])) {
					$page_ref = filter_var(trim($value_arr['parent_webpage']), FILTER_SANITIZE_URL);
				} else {
					$page_ref = '';
				}

				if (isset($value_arr['failed'])) {
					$failed = filter_var(trim($value_arr['failed']), FILTER_SANITIZE_STRIPPED);
				} else {
					$failed = '';
				}

				$post_id = (int)$value_arr['post_id'];
				$custom_fields = get_post_custom($post_id);
				$key = $custom_fields['mml_api'][0];

				if ($key === NULL || strlen($key) !== 40) {
					$key = get_option('mml_leadform_option_apikey');
				}

				if (!empty($error_arr)) {
					echo json_encode(array('status' => 'error', 'errors' => $error_arr));
				} else {

					// POST INFORMATION

					$data = array(
						'key' => $key,
						'ic'  => 1,
						'customer_name' => $customer_name,
						'customer_phone' => $customer_phone,
						'customer_email' => $customer_email,
						'ip' => $this->getRealIP(),
						'from_country' => $from_country,
						'to_country' => $to_country,
						'to_postal_code' => $to_postal_code,
						'to_admin1_code' => $to_admin1_code,
						'to_place' => $to_place,
						'size' => $size,
						'moving_date' => $moving_date,
						'user_agent' => $_SERVER['HTTP_USER_AGENT'],
						'parent_webpage' => $page_ref,
						'failed' => $failed,
						'version' => 'Wordpress Plugin Ver. '.MML_VERSION
					);

					// Append FROM Postal code or location
					if ($from_postal_code !== '') {
						$data['from_postal_code'] = $from_postal_code;
					} else {
						$data['from_admin1_code'] = $from_admin1_code;
						$data['from_place'] =  $from_place;
					}

					$mml_leads_url = 'https://portal.moveadvisor.com/services/leads/post';
//					$mml_leads_url = 'https://portal.moveadvisor.com/services/leads/tests/post';  // API test link
										
					$response = wp_remote_post(
						$mml_leads_url,
						array(
							'method' => 'POST',
							'body' => $data,
							'timeout' => 60			// 60 seconds (1 minutes)
						)
					);

                    if (is_wp_error( $response ))
                    {
                        $error_message = $response->get_error_message();
                        echo json_encode(array('status' => 'error', 'errors' => array($error_message)));
                    } else {
                        echo $response['body'];
                    }

				}
			} else {
				echo json_encode(array('status' => 'error', 'errors' => array('POST error')));
			}
		
		} else {
			// An empty key was provided
			echo json_encode(array('status' => 'error', 'errors' => array('No API Key')));
		}

		die();
	}

	/* ADD ASYNC/DEFER ATTRIBUTES ONLY TO GOOGLE MAPS PLACES
	 * Scripts that are called only if the shortcode is called.
	 */
	function add_async_defer_attribute($tag, $handle) {
		if ( 'mml_googlemaps-places' !== $handle ){
			return $tag;
		} else {
			return str_replace( ' src', ' async defer src', $tag );
		}
	}

	/* REGISTER SCRIPTS
	 * Scripts that are called only if the shortcode is called.
	 */

	function mml_leadform_register_scripts( $atts )
	{
		// Options for the theme
		switch ( get_option('mml_leadform_option_selecttheme') ) {
			case 'default':
				wp_enqueue_style( 'mml_leadform_style', MML_LEADFORM_URL.'assets/css/mml_style.css' );
				break;
			case 'theme01':
				wp_enqueue_style( 'mml_leadform_style', MML_LEADFORM_URL.'assets/css/mml_style.css' );
				wp_enqueue_style( 'mml_leadform_style_theme', MML_LEADFORM_URL.'assets/css/mml_theme01.css' );
				break;
			case 'theme02':
				wp_enqueue_style( 'mml_leadform_style', MML_LEADFORM_URL.'assets/css/mml_style.css' );
				wp_enqueue_style( 'mml_leadform_style_theme', MML_LEADFORM_URL.'assets/css/mml_theme02.css' );
				break;
			case 'custom':
				$themdirectory = get_template_directory_uri() . '/mml_style.css';
				if ( file_exists($themdirectory) ) {
					wp_enqueue_style( 'mml_leadform_style', $themdirectory );
				} else {
					wp_enqueue_style( 'mml_leadform_style', MML_LEADFORM_URL.'assets/css/mml_style.css' );
				}
				break;
			default:
				wp_enqueue_style( 'mml_leadform_style', MML_LEADFORM_URL.'assets/css/mml_style.css' );
				break;
		}

		// Parameters array to give to JS
		$scripts_params = array();
		$scripts_params['plugin'] = MML_LEADFORM_URL;
		$scripts_params['version'] = MML_VERSION;
		$scripts_params['hasapi'] = $this->hasapi;
		$scripts_params['googleAPI'] = '';
		$scripts_params['totrack'] = get_option('mml_leadform_option_trackform');
		$scripts_params['user_country'] = $this->getUserCountry();
		$scripts_params['ref_page'] = get_permalink();
		$scripts_params['default_privacy'] = get_option('mml_leadform_option_privacy');
		$scripts_params['nonsense'] = wp_create_nonce( 'mml-ajax-nonce' );
        $scripts_params['language'] = get_option('mml_leadform_option_selectlang');

		wp_enqueue_style( 'jquery-ui',  MML_LEADFORM_URL.'assets/css/jquery-ui.css', '1.12.1' );
		wp_enqueue_style( 'open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans');

		wp_enqueue_script(
			'mml_leadform_scripts',
			MML_LEADFORM_URL.'assets/js/mml_functions.min.js',
			array( 'jquery' )
		);
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-effects-shake' );
		wp_enqueue_script( 'typeahead', MML_LEADFORM_URL.'assets/js/mml_typeahead.min.js', array( 'jquery' ) );

		if ($this->google_key) {
			$scripts_params['googleAPI'] = $this->google_key;
			wp_enqueue_script(
				'mml_googlemaps-places',
				'https://maps.google.com/maps/api/js?key='. $this->google_key.'&libraries=places&language=en&callback=testApiCredentials',
				array( 'jquery' )
			);
		}

		wp_localize_script( 'mml_leadform_scripts', 'mml_ajax_url', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ));
		wp_localize_script( 'mml_leadform_scripts', 'mml_object', $scripts_params );

		/* This plugin does not ADD cookies.
		 * It looks for only mml_name, mml_phone, mml_email
		 * to prefill the form.
		 */
		wp_enqueue_script( 'jquery-cookie', MML_LEADFORM_URL.'assets/js/jquery.cookie.js', array( 'jquery' ) );
	}

}

$mml_shortcode = new MML_LeadForm_Shortcodes();

?>
