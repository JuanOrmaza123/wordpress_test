<?php
namespace Company\OrderMeta\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class Admin_Display {
    public function __construct() {
        add_action('woocommerce_admin_order_data_after_billing_address', [$this, 'display_reference_code'], 10, 1);
    }

    public function display_reference_code($order) {
        $reference_code = $order->get_meta('_company_reference_code');
        if (!$reference_code) {
            return;
        }
        ?>
        <div class="metadata-code">
            <strong><?php _e('Metadata code:', 'company-order-metadata'); ?></strong>
            <span><?php echo esc_html($reference_code); ?></span>
        </div>
        <style>
            .metadata-code { background: #f8f9fa; padding: 10px; margin: 10px 0; border-left: 4px solid #0073aa; }
        </style>
        <?php
    }
}