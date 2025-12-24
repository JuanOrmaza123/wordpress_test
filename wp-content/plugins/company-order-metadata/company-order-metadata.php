<?php

/**
 * Plugin Name: Company Order Metadata
 * Description: Genera códigos de referencia para pedidos WooCommerce.
 * Version: 1.0.0
 * Author: Juan Sebastian Ormaza
 * Requires at least: 6.0
 * Tested up to: 6.6
 * WC requires at least: 8.0
 * WC tested up to: 9.0
 * Text Domain: company-order-metadata
 */

use Company\OrderMeta\Company_Order_Meta;

if (!defined('ABSPATH')) {
    exit;
}

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    return;
}

const COMPANY_ORDER_META_VERSION = '1.0.0';
define("COMPANY_ORDER_META_PATH", plugin_dir_path(__FILE__));

require_once COMPANY_ORDER_META_PATH . 'includes/class-company-order-meta.php';

function company_order_metadata_init() {
    new Company_Order_Meta();
}
add_action('plugins_loaded', 'company_order_metadata_init');