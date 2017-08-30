<?php
/*
Plugin Name: Restrict Content Pro Auto Subscribe
Plugin URI: http://www.vanpattenmedia.com/
Description: Automatically add new members to other MailChimp lists upon signup
Version: 1.0.0
Author: Van Patten Media Inc.
Author URI: https://www.vanpattenmedia.com/
Contributors: chrisvanpatten, mcfarlan
*/

/**
 * Sample usage of using a custom MailChimp `list_id`
 *
 * - the 'list_id' is not the same as the id in a Mailchimp URL
 * - use `rcp_get_mailchimp_lists()` on some template side code or using `error_log`
 * - by default the first item in the list is the fallback if no override is present
 * - below is a sample of using a that has been fetched manually `list_id`
 */
// add_filter( 'rcp_tweak_auto_signup_mailchimp_list_id', function() { return '58362286b4'; } );

/**
 * Fetch the list id to subscribe to automagically; defaults to first list in MC admin
 *
 * @return string $list_id
 */
function rcp_tweaks_set_mailchimp_list_id() {
	$lists   = rcp_get_mailchimp_lists();
	$list_id = apply_filters( 'rcp_tweak_auto_signup_mailchimp_list_id', $lists[0]['id'] );

	return $list_id;
}

/**
 * Optional filter to only auto-sub members for specific subscriptions (listed as an array of IDs); defaults to all subscription levels
 *
 * @return bool
 */
function rcp_tweaks_applicable_subscription_levels() {
	$subscription_levels_to_check = apply_filters( 'rcp_tweaks_subscription_levels_for_auto_signup', function() {
		return NULL;
	} );

	if ( ! in_array( $member->get_subscription_id(), $subscription_levels_to_check ) || ! is_null( $subscription_levels_to_check ) ) {
		return false;
	}

	return true;
}
// add_filter( 'rcp_tweaks_subscription_levels_for_auto_signup', function() { return array( 1, 2, 3 ); } );

/**
 * Automatically subscribes a user to a secondary list upon account activation
 *
 * @param RCP_Member $member
 *
 * @return void
 */
function rcp_tweaks_after_registration( $member ) {

	// Optional check on subscription level
	$apply_to_subscription = rcp_tweaks_applicable_subscription_levels();

	if ( $apply_to_subscription !== false ) {

		if ( ! class_exists( 'MCAPI' ) ) {
			require trailingslashit( dirname( plugin_dir_path(__FILE__) ) ) . 'rcp-mailchimp/mailchimp/MCAPI.class.php';
		}

		$list_id   = rcp_tweaks_set_mailchimp_list_id();
		$creds     = get_option( 'rcp_mailchimp_settings' );
		$mailchimp = new MCAPI( $creds['mailchimp_api'] );

		try {
			$response = $mailchimp->listSubscribe( $list_id, $member->user_email, NULL, 'html', false );
		} catch(Exception $exception) {
			error_log( $exception );
		}

		if ( $response === false ) {
			error_log( 'email could not be subscribed: ' . $member->user_email  );
		}
	}
}
add_action( 'rcp_successful_registration', 'rcp_tweaks_after_registration' );