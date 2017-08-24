<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Used to fetch and consolidate reusable plugin data
 *
 * @param string|array $field
 *
 * @return array|string $data|$results
 */
function rcp_tweaks_get_plugin_data( $field = 'all' ) {
	$data = array(
		'slug'    => 'rcp-auto-subscribe',
		'prefix'  => 'rcp_auto',
		'name'    => 'Restrict Content Pro Auto MailChimp Subscribe',
		'version' => '1.0.0'
	);

	if ( $field !== 'all' ) {

		// Single value returns
		if ( is_string( $field ) ) {
			return $data[ $field ];

			// Fetch multiple values but not all
		} else if ( is_array( $field ) ) {

			$results = array();
			foreach ( $field as $field_name ) {
				array_push( $results, $data[ $field_name ] );
			}

			return $results;
		}
	}

	return $data;
}

/**
 * Builds and prints notice for use in wp-admin dashboard
 *
 * @param string    $message
 * @param string    $type
 */
function rcp_tweaks_build_admin_notice( $message, $type = 'error' ) {

	// @todo: remove is-dismissible or finish relevant work on dismissed state
	$wrap = '<div class="%2$s notice is-dismissible"><p>%1$s</p></div>';

	_e( sprintf( $wrap, $message, $type ), 'rcp-donations' );
}

/**
 * Display notice to install and activate Gravity Forms
 */
function rcp_tweaks_notice_activate_rcp_mailchimp(){
	$message = '<strong>Warning!</strong> Restrict Content Pro MailChimp needs to be installed and activated for ' . rcp_tweaks_get_plugin_data( 'name' ) . '.';

	rcp_tweaks_build_admin_notice( $message, 'error' );
}

/**
 * Display notice to install and activate RCP
 */
function rcp_tweaks_notice_activate_rcp(){
	$message = '<strong>Warning!</strong> Restrict Content Pro needs to be installed and activated for ' . rcp_tweaks_get_plugin_data( 'name' ) . '.';

	rcp_tweaks_build_admin_notice( $message, 'error' );
}