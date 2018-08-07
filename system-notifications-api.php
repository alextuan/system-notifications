<?php
/*
Plugin Name: System Notifications with JSON REST API
Description: Manage the notifications and register REST API.
Version: 1.0.0
Plugin URI: https://github.com/alextuan/system-notifications-api
Author: Nguyen Cong Tuan
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'SYS_NOTIFICATIONS_PATH', dirname(__FILE__) );
define( 'SYS_NOTIFICATIONS_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );

define( 'SYS_NOTIFICATIONS_VERSION', '1.0.0' );

include( 'includes/notifications-post-types.php' );

if ( is_admin() ) {
	include( 'includes/notifications-data-metabox.php' );
}

include( 'includes/rest-api/notifications-api.php' );

?>