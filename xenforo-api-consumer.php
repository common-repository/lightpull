<?php
/*
 Plugin Name: Lightpull
 Plugin URI: http://lightpull.org/threads/welcome-to-lightpull.3/
 Description: LightPull is the world's first Blog hub with two-way sync. Unlike other social networks, when you share your blog content on LightPull, any comments threads are synchronised with your blog, so that comments here, and on your site, form one big conversation rather than lots of fragmented ones. And you get full moderator rights in both places.

We have thread up- and downvoting, and the best  and most popular content will be shown on our homepage. We also give you extensive social sharing options to help you get there.

Full instructions on how to get started are [here](http://lightpull.org/threads/welcome-to-lightpull.3/).
 Version: 1.0.0
 Author: Libpar
 Author URI: http://lightpull.org
 Text Domain: LightPull
 Domain Path: /lang
 */

// Exit if accessed directly
if (!defined('ABSPATH'))
{
	exit();
}

define('XFAC_API_SCOPE', 'read post conversate');
define('XFAC_PLUGIN_PATH', WP_PLUGIN_DIR . '/lightpull');
define('XFAC_PLUGIN_URL', WP_PLUGIN_URL . '/lightpull');

define('XFAC_CACHE_RECORDS_BY_USER_ID', 'xfacCRBUI');
define('XFAC_CACHE_RECORDS_BY_USER_ID_TTL', 3600);

function xfac_activate()
{
	if (!function_exists('is_multisite'))
	{
		// requires WordPress v3.0+
		deactivate_plugins(basename(dirname(__FILE__)) . '/' . basename(__FILE__));
		wp_die(__("Lightpull plugin requires WordPress 3.0 or newer.", 'xenforo-api-consumer'));
	}

	xfac_install();

	do_action('xfac_activate');
}

register_activation_hook(__FILE__, 'xfac_activate');

function xfac_init()
{
	$loaded = load_plugin_textdomain('xenforo-api-consumer', false, 'xenforo-api-consumer/lang/');
}

add_action('init', 'xfac_init');

require_once (dirname(__FILE__) . '/includes/helper/api.php');
require_once (dirname(__FILE__) . '/includes/helper/dashboard.php');
require_once (dirname(__FILE__) . '/includes/helper/installer.php');
require_once (dirname(__FILE__) . '/includes/helper/option.php');
require_once (dirname(__FILE__) . '/includes/helper/template.php');
require_once (dirname(__FILE__) . '/includes/helper/user.php');

if (is_admin())
{
	require_once (dirname(__FILE__) . '/includes/dashboard/options.php');
	require_once (dirname(__FILE__) . '/includes/dashboard/profile.php');
}
else
{
	require_once (dirname(__FILE__) . '/includes/ui/login.php');
	require_once (dirname(__FILE__) . '/includes/ui/top_bar.php');
	require_once (dirname(__FILE__) . '/includes/sync/login.php');
}

require_once (dirname(__FILE__) . '/includes/helper/sync.php');
require_once (dirname(__FILE__) . '/includes/sync/avatar.php');
require_once (dirname(__FILE__) . '/includes/sync/post.php');
require_once (dirname(__FILE__) . '/includes/sync/comment.php');

require_once (dirname(__FILE__) . '/includes/widget/threads.php');

require_once (dirname(__FILE__) . '/lightpull.php');
