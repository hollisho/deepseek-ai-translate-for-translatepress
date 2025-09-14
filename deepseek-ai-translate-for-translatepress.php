<?php
/**
 * Plugin Name: Hollisho Integration with DeepSeek for TranslatePress
 * Description: A lightweight PHP tool to supercharge TranslatePress (WordPress multilingual plugin) with DeepSeek AI's advanced translation API
 * Version: 1.0.1
 * Author: Hollis Ho
 * Author URI: https://github.com/hollisho
 * Text Domain: hollisho-integration-deepseek-for-translatepress
 * Domain Path: /languages
 * Requires PHP: 7.2
 * Requires at least: 6.0
 * Tested up to: 6.8
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


use hollisho\translatepress\translate\deepseek\inc\Base\Activate;
use hollisho\translatepress\translate\deepseek\inc\Base\Deactivate;
use hollisho\translatepress\translate\deepseek\inc\Init;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( PHP_VERSION_ID < 70200 ) { 
	// show warning message
	if ( is_admin() ) {
		add_action( 'admin_notices', function ()
		{
			// translators: %1$s is the minimum PHP version required, %2$s is the current PHP version.
			$text = __( 'DeepSeek For TranslatePress need PHP %1$s. Your current PHP version is %2$s. Please upgrade to PHP to %1$s or a newer version, otherwise the plugin will have no effect.',
			'hollisho-integration-deepseek-for-translatepress' );
			$formatted_text = sprintf( esc_html( $text ), esc_html( '7.2.0' ), esc_html( PHP_VERSION ) );
			printf( '<div class="error"><p>%s</p></div>', esc_html( $formatted_text )  );
		} );
	}

	return;
}


//check translatepress is active
if (!in_array('translatepress-multilingual/index.php', get_option('active_plugins'))) {
    return ;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}


if (class_exists(Init::class)) {
    add_action( 'plugins_loaded', function () {
		load_plugin_textdomain( 'hollisho-integration-deepseek-for-translatepress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		Init::registerService();
	}, -999 );
}

register_activation_hook(__FILE__, function () {
    Activate::handler();
});

register_deactivation_hook(__FILE__, function () {
    Deactivate::handler();
});