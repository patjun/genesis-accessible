<?php
/**
* This plugin fixes some accessibility issues with the Genesis Framework
*
* @package Genesis
* @author Rian Rietveld
*
* Plugin Name: Genesis Accessible
* Plugin URI: http://genesis-accessible.org/
* Description: This plugin fixes some accessibility issues with the Genesis Framework.
* Author: Rian Rietveld
* Version: 1.1.1
* Author URI: http://www.rrwd.nl/
* License: GPLv2
* Text Domain: genesis-accessible
* Domain Path: /languages/
* Function prefix genwpacc_
*/

/**
* Defining Genesis Accessible constants
 *
 * @since 1.0.0
 */

define( 'GENWPACC_VERSION','1.1.0' );

if ( ! defined( 'GENWPACC_BASE_FILE' ) )
    define( 'GENWPACC_BASE_FILE', __FILE__ );
if ( ! defined( 'GENWPACC_BASE_DIR' ) )
    define( 'GENWPACC_BASE_DIR', dirname( GENWPACC_BASE_FILE ) );
if ( ! defined( 'GENWPACC_PLUGIN_URL' ) )
    define( 'GENWPACC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
if ( ! defined( 'GENWPACC_PLUGIN_PATH' ) )
    define( 'GENWPACC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'GENWPACC_SETTINGS_FIELD', 'genwpacc-settings' );

/**
 * The text domain for the plugin
 *
 * @since 1.0.0
 */
define( 'GENWPACC_DOMAIN' , 'genesis-accessible' );

/**
 * Load the text domain for translation of the plugin
 *
 * @since 1.0.0
 */
load_plugin_textdomain( 'genesis-accessible', false, 'genesis-accessible/languages' );


register_activation_hook( __FILE__, 'genwpacc_activation_check' );

/**
 * Checks for activated Genesis Framework and its minimum version before allowing plugin to activate
 *
 * @author Nathan Rice, Remkus de Vries. adjusted by Rian Rietveld for this plugin
 * @uses accessible_activation_check()
 * @since 1.0
 */
function genwpacc_activation_check() {

	// Find Genesis Theme Data
    $theme = wp_get_theme( 'genesis' );

    // Get the version
    $version = $theme->get( 'Version' );

    // Set what we consider the minimum Genesis version
    $minimum_genesis_version = '2.0';

	// Restrict activation to only when the Genesis Framework is activated
	if ( basename( get_template_directory() ) != 'genesis' ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );  // Deactivate ourself
		wp_die( sprintf( __( 'Whoa.. the Genesis Accessible plugin only works, really, when you have installed the %1$sGenesis Framework%2$s', GENWPACC_DOMAIN ), '<a href="http://www.shareasale.com/r.cfm?b=346198&u=629895&m=28169&urllink=&afftrack=">Genesis Framework</a>', '</a>' ) );
	}

	// Set a minimum version of the Genesis Framework to be activated on
    if ( version_compare( $version, $minimum_genesis_version, '<' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );  // Deactivate ourself
		wp_die( sprintf( __( 'Uhm, the thing of it is, you kinda need the %1$sGenesis Framework %2$s%3$s or greater for these plugin to make any sense.', GENWPACC_DOMAIN ), '<a href="http://www.shareasale.com/r.cfm?b=346198&u=629895&m=28169&urllink=&afftrack=">Genesis Framework</a>', $latest, '</a>' ) );
	}

}

/**
 * Include all files in dir includes/
 *
 * @since 1.0.0
 */

//* Include plugin admin files and files per option
add_action( 'genesis_init', 'genwpacc_genesis_init', 12 );
function genwpacc_genesis_init() {

	if ( is_admin() ) {
		require_once( GENWPACC_PLUGIN_PATH . 'admin/accessible-theme-settings.php' );

		if ( genesis_get_option( 'genwpacc_tinymce', 'genwpacc-settings' ) == 1 )
			require_once( GENWPACC_PLUGIN_PATH . 'admin/admin.php' );

	}

	require_once( GENWPACC_PLUGIN_PATH . 'includes/forms.php' );
	require_once( GENWPACC_PLUGIN_PATH . 'includes/wp-modification.php' );

	if ( genesis_get_option( 'genwpacc_skiplinks', 'genwpacc-settings' ) == 1 || genesis_get_option( 'genwpacc_skiplinks_css', 'genwpacc-settings' ) == 1 )
		require_once( GENWPACC_PLUGIN_PATH . 'includes/skip-links.php' );

	if ( genesis_get_option( 'genwpacc_widget_headings', 'genwpacc-settings' ) == 1 )
		require_once( GENWPACC_PLUGIN_PATH . 'includes/headings.php' );

	if ( genesis_get_option( 'genwpacc_no_title_attr', 'genwpacc-settings' ) == 1 )
		require_once( GENWPACC_PLUGIN_PATH . 'includes/attributes.php' );

	if ( genesis_get_option( 'genwpacc_dropdown', 'genwpacc-settings' ) == 1 )
		require_once( GENWPACC_PLUGIN_PATH . 'includes/dropdown.php' );

	if ( genesis_get_option( 'genwpacc_remove_genesis_widgets', 'genwpacc-settings' ) == 1 )
		require_once( GENWPACC_PLUGIN_PATH . 'includes/widgets.php' );


}

/**
 * Redirect 404 and archive templates to accessible templates
 *
 * @since 1.0.0
 */

add_action( 'template_redirect', 'genwpacc_template_redirect' );
function genwpacc_template_redirect() {
	if ( get_page_template_slug() == 'page_archive.php' ) {
		include ( GENWPACC_PLUGIN_PATH .'/templates/sitemap.php' );
		exit();
	}
	if ( is_404() ) {
		include ( GENWPACC_PLUGIN_PATH .'/templates/404.php' );
		exit();
	}

}
