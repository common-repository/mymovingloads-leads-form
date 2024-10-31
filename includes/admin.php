<?php
/* This is where all admin panel functions gather */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once(MML_LEADFORM_DIR . 'includes/options/leadform_settings.php');

function mml_leadform_settings_link( $links )
{
	array_push( $links, '<a href="'. esc_url( admin_url( 'admin.php?page=mml_leadform_dashboard' ) ) .'">'.__( 'Settings', MML_DOMAIN ).'</a>' );
	return $links;
}
add_filter( 'plugin_action_links_' . MML_BASENAME, 'mml_leadform_settings_link' );

function mml_leadform_options_options_css ( $hook )
{
	if ( $hook !== 'toplevel_page_mml_leadform_dashboard' )
	{
		return;
	}
	wp_enqueue_style( 'custom_wp_admin_css', MML_LEADFORM_URL.'assets/css/mml_settings.css' );
}
add_action( 'admin_enqueue_scripts', 'mml_leadform_options_options_css' );

?>