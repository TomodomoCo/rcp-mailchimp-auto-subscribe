<?php
/*
Plugin Name: Restrict Content Pro Auto MailChimp Subscribe
Plugin URI: http://www.vanpattenmedia.com/
Description: Automatically add new members to other MailChimp lists upon signup
Version: 1.0.1
Author: Van Patten Media Inc.
Author URI: https://www.vanpattenmedia.com/
Contributors: chrisvanpatten, mcfarlan
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/includes/utils.php';

/**
 * Sample usage of using a custom MailChimp `list_id`
 *
 * - the 'list_id' is not the same as the id in a Mailchimp URL
 * - use `rcp_get_mailchimp_lists()` on some template side code or using `error_log`
 * - by default the first item in the list is the fallback if no override is present
 * - below is a sample of using a that has been fetched manually `list_id`
 */
// add_filter( 'rcp_auto_mc_signup_mailchimp_list_id', function() { return '58362286b4'; } );

/**
 * Checks if RCP and RCP MailChimp are installed and active; self-deactivates if `false`
 *
 * @return bool
 */
function rcp_auto_mc_are_required_plugins_active() {
	$self_deactivate = false;

	if ( ! is_plugin_active( 'restrict-content-pro/restrict-content-pro.php' ) || ! class_exists( 'RCP_Member' ) ) {
		$self_deactivate = true;
		add_action( 'admin_notices', 'rcp_auto_mc_notice_activate_rcp' );
	}

	if ( ! is_plugin_active( 'rcp-mailchimp/rcp-mailchimp.php' ) || ! function_exists( 'rcp_mailchimp_settings_menu' ) ) {
		$self_deactivate = true;
		add_action( 'admin_notices', 'rcp_auto_mc_notice_activate_rcp_mailchimp' );
	}

	if ( $self_deactivate === false ) {
		return true;
	}

	deactivate_plugins( plugin_basename( __FILE__), true );

	return false;
}
add_action( 'admin_init', 'rcp_auto_mc_are_required_plugins_active' );

/**
 * Fetch the list id to subscribe to automagically; defaults to first list in MC admin
 *
 * @return string $list_id
 */
function rcp_auto_mc_set_mailchimp_list_id() {
	$lists   = rcp_get_mailchimp_lists();
	$list_id = apply_filters( 'rcp_auto_mc_signup_mailchimp_list_id', $lists[0]['id'] );

	return $list_id;
}

/**
 * Optional filter to only auto-sub members for specific subscriptions (listed as an array of IDs); defaults to all subscription levels
 *
 * @return bool
 */
function rcp_auto_mc_applicable_subscription_levels() {
	$subscription_levels_to_check = apply_filters( 'rcp_auto_mc_subscription_levels_for_auto_signup', function() {
		return NULL;
	} );

	if ( ! in_array( $member->get_subscription_id(), $subscription_levels_to_check ) || ! is_null( $subscription_levels_to_check ) ) {
		return false;
	}

	return true;
}
// add_filter( 'rcp_auto_mc_subscription_levels_for_auto_signup', function() { return array( 1, 2, 3 ); } );

/**
 * Automatically subscribes a user to a secondary list upon account activation
 *
 * @param RCP_Member $member
 *
 * @return void
 */
function rcp_auto_mc_after_registration( $member ) {

	if ( ! class_exists( 'MCAPI' ) ) {
		require trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/MailChimpApi.class.php';
	}

	$list_id   = rcp_auto_mc_set_mailchimp_list_id();
	$creds     = get_option( 'rcp_mailchimp_settings' );
	$mailchimp = new MCAPI( $creds['mailchimp_api'] );

	try {
		$response = $mailchimp->listSubscribe( $list_id, $member->user_email, null, 'html', false );

	} catch ( Exception $exception ) {
		error_log( $exception );
		$response = false;
	}

	if ( $response === false || ! isset( $response ) ) {
		error_log( 'email could not be subscribed: ' . $member->user_email );
	}
}
add_action( 'rcp_successful_registration', 'rcp_auto_mc_after_registration' );