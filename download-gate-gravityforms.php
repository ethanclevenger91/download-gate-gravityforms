<?php
/**
 * Plugin Name:     Download Gate for Gravity Forms
 * Plugin URI:      https://github.com/ethanclevenger91/download-gate-gravityforms
 * Description:     Works with Gravity Forms to allow visitors to download files after submitting a gravity form
 * Version:         1.0.2
 * Author:          Ethan Clevenger
 * Author URI:      https://sternerstuffdesign.com
 * License:         GPL-2.0+
 * Text Domain:     lc-gforms_dg
 *
 * @package download-gate-gravityforms
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Verifies CMB2 is installed and activated
 *
 * @since     1.0.0
 */
add_action( 'admin_init', 'cmb2_is_installed_and_active' );

function cmb2_is_installed_and_active() {
    if ( is_admin() && !is_plugin_active( 'cmb2/init.php' ) ) {
        add_action( 'admin_notices', 'cmb2_required_notice' );
    }

    if ( is_admin() && !class_exists( 'CPT_Core' ) ) {
    	add_action( 'admin_notices', 'cpt_core_required_notice' );
    }
}

/**
 * Admin notice if CMB2 is not available
 *
 * @since     1.0.0
 */
function cmb2_required_notice(){
    ?><div class="error"><p>CMB2 must be installed and active for Download Gate Gravity Forms to work. Please <a href="<?php echo get_admin_url(null, 'plugin-install.php?s=cmb2&tab=search&type=term'); ?>">install</a> and activate CMB2 for proper functionality.</p></div><?php
}

/**
 * Admin notice if CMB2 is not available
 *
 * @since     1.0.0
 */
function cpt_core_required_notice(){
    ?><div class="error"><p>WDS CPT Core must be installed and active for Download Gate Gravity Forms to work. Please <a target="_blank" href="https://github.com/WebDevStudios/CPT_Core">install</a> and activate WDS CPT Core for proper functionality.</p></div><?php
}

add_action( 'gform_loaded', 'lc_gforms_dg_register_gform_addon' );
/**
 * Get the Gravity Forms AddOn stuff going
 *
 * @since    0.1.0
 */
function lc_gforms_dg_register_gform_addon() {

	if ( ! method_exists( 'GFForms', 'include_addon_framework' ) )
		return;

	require_once( plugin_dir_path( __FILE__ ) . '/includes/GForm_AddOn.php' );

	GFAddOn::register( '\LC_Gforms_Download_Gate\GF_AddOn\AddOn' );

}

/**
 * Putting this here for now to be able to access it more easily
 * throughout the plugin
 *
 * @since     0.1.0
 */
function lc_gforms_dg_form_id() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/GForm_AddOn.php';
	return \LC_Gforms_Download_Gate\GF_AddOn\AddOn::get_instance()->get_plugin_setting( 'lc_gforms_dg_settings_download_form' );
}

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require plugin_dir_path( __FILE__ ) . 'admin/Admin.php';

	/**
	 * Function to execute admin area for our plugin
	 *
	 * @since    0.1.0
	 */
	function run_gravityforms_download_gate_admin() {

		// Don't do anything if Gravity Forms isn't activated.
		if ( ! class_exists( 'GFForms' ) )
		  return;

		$plugin_admin = new LC_Gforms_Download_Gate\Admin\Admin();
		$plugin_admin->run();

	}

	run_gravityforms_download_gate_admin();

}

/**
 * Get the main classes for the plugin. Where everything is pulled together for
 * global, front-end, and admin elements of the plugin
 */
require plugin_dir_path( __FILE__ ) . 'includes/Main.php';

/**
 * Begins execution of the plugin.
 *
 * @since    0.1.0
 */
function run_gravityforms_download_gate() {

	// Don't do anything if Gravity Forms isn't activated.
	if ( ! class_exists( 'GFForms' ) )
		return;

	$plugin = new LC_Gforms_Download_Gate\Main();
	$plugin->run();

}

run_gravityforms_download_gate();
