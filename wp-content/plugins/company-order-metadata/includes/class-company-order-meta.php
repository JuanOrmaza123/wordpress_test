<?php
namespace Company\OrderMeta;

class Company_Order_Meta {
    public function __construct() {
        add_action('woocommerce_new_order', [$this, 'generate_reference_code'], 10, 1);
        add_filter('company_order_reference_code', [$this, 'default_reference_code'], 10, 2);
    }

    public function generate_reference_code($order_id) {
        $order = wc_get_order($order_id);
        if (!$order || metadata_exists('post', $order_id, '_company_reference_code')) {
            return;
        }

        $reference_code = apply_filters(
            'company_order_reference_code',
            $this->default_reference_code(null, $order),
            $order
        );

        $order->update_meta_data('_company_reference_code', $reference_code);
        $order->save();
    }

    public function default_reference_code($reference_code, $order) {
        if ($reference_code) {
            return $reference_code;
        }
        $year = date('Y', $order->get_date_created()->getTimestamp());
        return sprintf('CMP-%s-%s', $order->get_id(), $year);
    }
}
