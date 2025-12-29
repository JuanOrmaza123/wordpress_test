=== Company Order Metadata ===
Contributors: Juan Sebastian Ormaza
Tags: woocommerce, orders, metadata, admin
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Generates an internal reference code for WooCommerce orders and displays it in the administration panel.

== Description ==

This plugin adds an internal reference code to each WooCommerce order when a new order is created.

The code is generated in the format:

CMP-{ORDER_ID}-{YYYY}

and is saved as order metadata. It is then displayed on the order editing screen in the WooCommerce administration panel as a clearly labeled read-only field.

== Features ==

* Automatic internal code generation when creating the order.
* Format: CMP-{ORDER_ID}-{YYYY}.
* Saved in the order metadata (`_company_reference_code`).
* Displayed on the order editing screen in the WooCommerce admin.
* Filter hook to modify the generated code from other plugins.

== Installation ==

1. Make sure WooCommerce is installed and active.
2. Upload the `company-order-metadata` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin from the **Plugins** menu in WordPress.
4. Create a new order from the store.
5. Review the order in **WooCommerce → Orders → Edit** to see the internal code.
