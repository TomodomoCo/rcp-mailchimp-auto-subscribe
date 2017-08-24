# Restrict Content Pro Auto MailChimp Subscribe

Automatically subscribe users to MailChimp lists (without explicit opt-in). Requires [Restrict Content Pro MailChimp](https://restrictcontentpro.com/addons/mailchimp) and [Restrict Content Pro](https://restrictcontentpro.com).

## Installation

1. Download the [latest release](https://github.com/TomodomoCo/rcp-mailchimp-auto-subscribe/releases/latest).
2. Upload to your plugins folder of your WordPress installation
3. Activate the plugin, then see usage instructions for configuration.

## Usage

Once the plugin is active, you can define a secondary MailChimp list by its `list_id` through a filter. This `list_id` is not the same as the ID or integer in MailChimp URLs.

To retrieve the appropriate MailChimp `list_id`, make use of Restrict Content Pro MailChimp function `rcp_get_mailchimp_lists` on some temporary template side code or via `error_log`.

Example of finding your `list_id`:

```
<pre>
<?php print_r( rcp_get_mailchimp_lists() ); ?>
</pre>
```

Find the appropriate `list_id` in this temporary output and make use of the `rcp_auto_mc_signup_mailchimp_list_id` filter.

Example filter usage:

```
function rcp_auto_mc_override_list_id() {
	return '123123abcabc';
}
add_filter( 'rcp_auto_mc_signup_mailchimp_list_id', 'rcp_auto_mc_override_list_id' );
```

## License & Conduct

This project is licensed under the terms of the MIT License, included in `LICENSE.md`.

All Van Patten Media Inc. open source projects follow a strict code of conduct, included in `CODEOFCONDUCT.md`. We ask that all contributors adhere to the standards and guidelines in that document.

Thank you!