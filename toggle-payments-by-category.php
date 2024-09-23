<?php

/**
 * Plugin Name:       Toggle Payment By Category
 * Plugin URI:        https://woocopilot.com/plugins/toggle-payment-by-category/
 * Description:       "Toggle Payment by Category" is a plugin that allows administrators to enable or disable specific payment methods based on product categories in an online store. It provides greater flexibility by ensuring that certain payment options are available or restricted for different product types, enhancing the checkout experience for both the store owner and customers.
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      7.2
 * Author:            WooCopilot
 * Author URI:        https://woocopilot.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       toggle-payment-by-category
 * Domain Path:       /languages
 */


/**
    Toggle Payment By Category With Active Variation Price is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    any later version.

    Toggle Payment By Category With Active Variation Price is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Toggle Payment By Category With Active Variation Price. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

defined("ABSPATH") || exit;

require_once __DIR__ . '/includes/class-admin-toggle-payments-by-category.php';
require_once __DIR__ . '/includes/class-toggle-payments-by-category.php';

/**
 * Initializing plugin.
 *
 * @since 1.0.0
 * @return object Plugin object.
 */

function Toggle_Payments_by_Category() {
    return new Toggle_Payments_By_Category( __FILE__, "1.0.0");
}

Toggle_Payments_by_Category();