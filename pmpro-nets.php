<?php
/*
Plugin Name: Paid Memberships Pro - N ets Gateway
Plugin URI: https://www.paidmembershipspro.com/add-ons/nets
Description: PMPro Gateway integration for Nets
Version: 0.5.1
Author: Paid Memberships Pro
Author URI: https://www.paidmembershipspro.com
Text Domain: pmpro-nets
Domain Path: /languages
*/

define( "PMPRO_NETS_DIR", dirname( __FILE__ ) );

/**
 * Loads rest of Nets gateway if PMPro is active.
 */
function pmpro_nets_load_gateway() {

	if ( class_exists( 'PMProGateway' ) ) {
		require_once( PMPRO_NETS_DIR . '/classes/class.pmprogateway_nets.php' );
		add_action( 'wp_ajax_nopriv_nets-webhook', 'pmpro_wp_ajax_nets_webhook' );
		add_action( 'wp_ajax_nets-webhook', 'pmpro_wp_ajax_nets_webhook' );
	}

}
add_action( 'plugins_loaded', 'pmpro_nets_load_gateway' );

/**
 * Callback for Nets Webhook
 */
function pmpro_wp_ajax_nets_webhook() {

	require_once( dirname(__FILE__) . "/webhook.php" );
	exit;
}
add_action( 'wp_ajax_nopriv_nets-webhook', 'pmpro_wp_ajax_nets_webhook' );
add_action( 'wp_ajax_nets-webhook', 'pmpro_wp_ajax_nets_webhook' );

/**
 * Runs only when the plugin is activated.
 *
 * @since 0.1.0
 */
function pmpro_nets_admin_notice_activation_hook() {
	// Create transient data.
	set_transient( 'pmpro-nets-admin-notice', true, 5 );
}
register_activation_hook( __FILE__, 'pmpro_nets_admin_notice_activation_hook' );

/**
 * Admin Notice on Activation.
 *
 * @since 0.1.0
 */
function pmpro_nets_admin_notice() {
	// Check transient, if available display notice.
	if ( get_transient( 'pmpro-nets-admin-notice' ) ) {
	?>
		<div class="updated notice is-dismissible">
			<p><?php printf( __( 'Thank you for activating the Paid Memberships Pro: Nets Add On. <a href="%s">Visit the payment settings page</a> to configure the Nets Payment Gateway.', 'pmpro-nets' ), esc_url( get_admin_url( null, 'admin.php?page=pmpro-paymentsettings' ) ) ); ?></p>
		</div>
		<?php
		// Delete transient, only display this notice once.
		delete_transient( 'pmpro-nets-admin-notice' );
	}
}
add_action( 'admin_notices', 'pmpro_nets_admin_notice' );

/**
 * Function to add links to the plugin action links
 *
 * @param array $links Array of links to be shown in plugin action links.
 */
function pmpro_nets_plugin_action_links( $links ) {
	if ( current_user_can( 'manage_options' ) ) {
		$new_links = array(
			'<a href="' . get_admin_url( null, 'admin.php?page=pmpro-paymentsettings' ) . '">' . __( 'Configure Nets', 'pmpro-nets' ) . '</a>',
		);
		$links  = array_merge( $links, $new_links );
	}
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pmpro_nets_plugin_action_links' );

/**
 * Function to add links to the plugin row meta
 *
 * @param array  $links Array of links to be shown in plugin meta.
 * @param string $file Filename of the plugin meta is being shown for.
 */
function pmpro_nets_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, 'pmpro-nets.php' ) !== false ) {
		$new_links = array(
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/add-ons/nets/' ) . '" title="' . esc_attr( __( 'View Documentation', 'pmpro-nets' ) ) . '">' . __( 'Docs', 'pmpro-nets' ) . '</a>',
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/support/' ) . '" title="' . esc_attr( __( 'Visit Customer Support Forum', 'pmpro-nets' ) ) . '">' . __( 'Support', 'pmpro-nets' ) . '</a>',
		);
		$links = array_merge( $links, $new_links );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'pmpro_nets_plugin_row_meta', 10, 2 );

/**
 * Load the languages folder for translations.
 */
function pmpronets_load_textdomain(){
	load_plugin_textdomain( 'pmpro-nets' );
}
add_action( 'plugins_loaded', 'pmpronets_load_textdomain' );
