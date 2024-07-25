<?php
/**
 * Plugin Name: Radio Buttons and Swatches for WooCommerce
 * Plugin URI:  https://wordpress.org/plugins/variations-radio-buttons-for-woocommerce/
 * Description: Increase conversions by displaying beautiful radio buttons and swatches.
 * Author:      OneTeamSoftware
 * Author URI:  https://1teamsoftware.com
 * Text Domain: variations-radio-buttons-for-woocommerce
 * Version:     1.1.19
 * Tested up to: 6.2
 * Requires PHP: 7.3
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Copyright: © 2023 FlexRC, 3-7170 Ash Cres, V6P 3K7, Canada. Voice 604 800-7879
 */

namespace OneTeamSoftware\WooCommerce\VariationsRadioButtons;

defined('ABSPATH') || exit;

require_once(__DIR__ . '/includes/autoloader.php');

if (class_exists(__NAMESPACE__ . '\\Plugin')) {
	(new Plugin(__FILE__, '1.1.19', __('Radio Buttons and Swatches for WooCommerce', 'variations-radio-buttons-for-woocommerce')))->register();
}
