# Restrict Content Pro Auto MailChimp Subscribe

This WordPress plugin enables members to be subscribed to a second MailChimp list when using [Restrict Content Pro](https://restrictcontentpro.com) and Restrict Content MailChimp. This secondary email sign-up does not require explicit opt-in from the user and is added automatically.

## Requirements

- WordPress v4.2+
- Restrict Content Pro v2.9.1+
- Restrict Content Pro MailChimp v1.3+
- MailChimp Account

## Installation

1. Download the [latest release](https://github.com/TomodomoCo/rcp-mailchimp-auto-subscribe/releases/latest).
2. Upload to your WordPress plugin directory (the default is `/wp-content/plugins/`). Alternatively, you can upload via the WordPress admin under `Plugins > Add New`. Click on the `Upload Plugin` button beside the page title.
3. Activate the `Restrict Content Pro` Auto Sub plugin from the WordPress admin.
4. Ensure you have a valid API key for Mail Chimp in the WordPress admin under `Restrict > MailChimp`.

## Usage

Once the plugin is active, you can define a secondary MailChimp list by its `list_id` through a filter. This `list_id` is not the same as the ID or integer in MailChimp URLs.

To retrieve the appropriate MailChimp `list_id`, make use of Restrict Content Pro MailChimp function `rcp_get_mailchimp_lists` on some temporary template side code or via `error_log`.

Example of finding your `list_id`:

```
<pre>
<?php print_r( rcp_get_mailchimp_lists() ); ?>
</pre>
```

Once you have the appropriate `list_id` for your second list, and before moving further, it's a good idea to remove this template side code (you don't want to have that in the public eye). You can now implement a filter to pass the appropriate `list_id` of the secondary list. You can place this filter in your theme `functions.php` file or in a separate plugin file.

```
function rcp_auto_mc_override_list_id() {
	return '123123abcabc';
}
add_filter( 'rcp_auto_mc_signup_mailchimp_list_id', 'rcp_auto_mc_override_list_id' );
```

## About Tomodomo

Tomodomo is a creative agency for communities. We focus on unique design and technical solutions to grow community activity and increase customer retention for online networking forums and customer service communities.

Learn more at [tomodomo.co](https://tomodomo.co) or email us: [hello@tomodomo.co](mailto:hello@tomodomo.co)

## License & Conduct

This project is licensed under the terms of the MIT License, included in [`LICENSE.md`](LICENSE.md).

All open source Tomodomo projects follow a strict code of conduct, included in [`CODEOFCONDUCT.md`](CODEOFCONDUCT.md). We ask that all contributors adhere to the standards and guidelines in that document.

Thank you!