<?php
/*
	Plugin Name: MoveAdvisor Leads Form
	Plugin URI: https://moveadvisor.com/biz/wp-lead-form
	Description: The plug and play moving leads form plugin. Add MoveAdvisor's form to a page, post or sidebar of your site to generate affiliate leads.
	Version: 3.0.2
	Author: MoveAdvisor Team
	Author URI: https://moveadvisor.com
	License: #GNUGPLv3
	Licence URI: https://www.gnu.org/licenses/lgpl.html
	Text Domain: mml-leadform
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('MML_LEADFORM_DIR', plugin_dir_path(__FILE__));
define('MML_LEADFORM_URL', plugin_dir_url(__FILE__));
define('MML_BASENAME', plugin_basename( __FILE__ ));
define('MML_DOMAIN', plugin_basename( __FILE__ ));
define('MML_VERSION', '3.0.2');



switch (get_locale()) {
    case 'us_US':
        define('MML_WPLANG', 'us');
        break;
    case 'de_DE':
        define('MML_WPLANG', 'de');
        break;
    default:
        define('MML_WPLANG', 'us');
        break;
}

load_plugin_textdomain(MML_WPLANG, false, dirname(MML_BASENAME) .'/includes/languages');

function mml_leadform_load()
{
		
	if(is_admin()) //load admin files only in admin
		require_once(MML_LEADFORM_DIR.'includes/admin.php');

	require_once(MML_LEADFORM_DIR.'includes/core.php');
}
mml_leadform_load();

/*
	PLUGIN ACTIVATION FUNCTION
	This is what happens when you activate the plugin.
*/
register_activation_hook(__FILE__, 'mml_leadform_activation');

function mml_leadform_activation()
{

	/**
	 * Install IP Detection database.
	 * The database is used for the auto detection of the person's country,
	 * so we can provide the correct form. On deletion of the plugin, the
	 * database will be deleted as well.
	 */

	global $wpdb;
	global $wp_version;

	$table_nation = 'mml_countries';
	$table_country = 'mml_countriesNames';
	$data_version = '1.0.0';

	$installed_nation = ($wpdb->get_var("SHOW TABLES LIKE '$table_nation'") == $table_nation);
	$installed_countries = ($wpdb->get_var("SHOW TABLES LIKE '$table_country'") == $table_country);
	
	$installed = ($installed_nation == true && $installed_countries == true);

	if(!$installed){

		// CREATE THE DATABASES
		$sql = file_get_contents(MML_LEADFORM_DIR . 'assets/detectiondb/mml_struct.sql');
		// In order to have access to dbDelta
		if ($wp_version >= 2.3) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		} else{
			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		}

		dbDelta($sql);

		// POPULATE THE DATABASES
		$data = fopen(MML_LEADFORM_DIR . 'assets/detectiondb/mml_data.sql', 'r');

		if ($data) {

			// If DB doesn't support Transactions
			try
			{
				$wpdb->query('START TRANSACTION');				
			} catch (Exception $e) {
				// See no evil
			}

			// Go line by line in the data file
			while (($line = fgets($data)) !== false) {
				$sql = rtrim($line, ';');
				$wpdb->query($sql);
			}

			// If DB doesn't support Transactions
			try
			{
				$wpdb->query('COMMIT');
			} catch (Exception $e) {
				// Hear no evil
			}

			fclose($data); // Close the file

			add_option( 'mml_detectiondb_version', $data_version );

		} else {
			// error opening the file.
		}
	}

	return $installed;	
}

/*
	PLUGIN DEACTIVATION FUNCTION
	This is what happpens when you deactivate the plugin.
*/
register_deactivation_hook(__FILE__, 'mml_leadform_deactivation');

function mml_leadform_deactivation()
{
	// When the plugin is deactivated
}

/*
	PLUGIN DELETE FUNCTION
	This is what happpens when you delete the plugin.
*/
register_uninstall_hook(__FILE__, 'mml_leadform_delete');

function mml_leadform_delete()
{

	global $wpdb;

	// Drop the databases
	$wpdb->query('DROP TABLE IF EXISTS mml_countries');
	$wpdb->query('DROP TABLE IF EXISTS mml_countriesNames');

	// Delete the options as well
	delete_option( 'mml_leadform_option_apikey' );
	delete_option( 'mml_leadform_option_hasapi' );
	delete_option( 'mml_leadform_option_googlekey' );
	delete_option( 'mml_leadform_option_selecttheme' );
	delete_option( 'mml_leadform_option_formtitle' );
	delete_option( 'mml_leadform_option_titlealignment' );
	delete_option( 'mml_leadform_option_buttontext');
	delete_option( 'mml_leadform_option_thankyou');
	delete_option( 'mml_leadform_option_trackform' );
	delete_option( 'mml_leadform_option_privacy' );
	delete_option( 'mml_detectiondb_version' );
	delete_option( 'mml_leadform_option_buttoncolor' );
	delete_option( 'mml_leadform_option_devadminbox' );
	delete_option( 'mml_leadform_option_formbgcolor' );
}

?>
