<?php

namespace Company\OrderMeta\Tests;

use Company\OrderMeta\Company_Order_Meta;
use WC_Order;

class TestCompanyOrderMeta extends \PHPUnit\Framework\TestCase{
    public function test_reference_code_format() {
        $order = new WC_Order();
        $order->set_id(123);
        $order->set_date_created('2025-01-01 12:00:00');

        $instance = new Company_Order_Meta();
        $code = $instance->default_reference_code(null, $order);

        $this->assertMatchesRegularExpression('/^CMP-123-2025$/', $code);
    }

    public function test_no_regenerate_existing() {
        $order_id = 999;
        update_post_meta($order_id, '_company_reference_code', 'EXISTING');

        $instance = new Company_Order_Meta();
        $instance->generate_reference_code($order_id);

        $this->assertEquals('EXISTING', get_post_meta($order_id, '_company_reference_code', true));
    }
}
