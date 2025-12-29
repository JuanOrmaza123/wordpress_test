# Company Order Metadata

WooCommerce plugin that automatically generates an internal reference code for each new order.  
It does not modify core or theme files, making it safe, lightweight, and easy to maintain.

---

## Architecture

The plugin structure is organized into three main layers:

### 1. Bootstrap (Main File)

- **File:** `company-order-metadata.php`
- Registers the plugin, loads classes from `includes/` and `includes/admin/`, and initializes everything with a simple `init`.
- From here, it instantiates classes that handle business logic and the admin visual part.

### 2. Business Logic (Domain)

- **Class:** `Company\OrderMeta\Company_Order_Meta`  
  (file: `includes/class-company-order-meta.php`)
- Contains the main functionality:
    - Listens for new order creation.
    - Generates a code in the format `CMP-{ORDER_ID}-{YYYY}`.
    - Prevents generation more than once per order.
    - Exposes the `company_order_reference_code` filter for other plugins to customize the code before saving.
- The code is saved as order metadata (`_company_reference_code`) using native WooCommerce functions.

### 3. Admin Interface (Presentation)

- **Class:** `Company\OrderMeta\Admin\Admin_Display`  
  (file: `includes/admin/class-admin-display.php`)
- Handles only displaying the internal code on the order detail screen:
    - Uses a corresponding WooCommerce admin hook.
    - Displays the value as a read-only field, clear and well-identified.

**Advantages of this architecture:**

- Logic decoupled from the visual environment.
- Easy to extend in the future (other display points, new formats, etc.).
- Greater ease for unit testing.

---

## Main Hooks and Reasons for Use

### `woocommerce_new_order` (action)

It executes when a new order is created.  
Ideal for generating the code at that moment.

**Why use it:**

- It is a specific hook for order creation.
- Directly provides the `order_id`.
- Avoids overload as it only executes when appropriate.
- Facilitates checking if the code already existed before generating it.

---

### `woocommerce_admin_order_data_after_billing_address` (action)

Used to display the code within the admin panel on the order view.

**Advantages:**

- Designed specifically to extend that admin area.
- Allows access to the `WC_Order` object without additional queries.
- Does not generate visual or functional conflicts.

---

### `company_order_reference_code` (filter)

Own plugin filter, designed to modify the code before saving it.

**What it is for:**

- Gives freedom to other developers to change the format or add custom logic.
- Allows adding prefixes, suffixes, or custom conditions (by country, payment method, etc.).
- Follows the WordPress standard (`apply_filters()`).

---

## How to Test Before Production

Although the plugin is lightweight, it handles important data.  
For this reason, it is recommended to test it thoroughly before final deployment.

### Tests in Staging or Preproduction

1. **Clone Environment.**  
   Use a recent copy of the site’s database and files in a staging identical to production.

2. **Installation.**  
   Upload the `company-order-metadata` folder to `wp-content/plugins/` and activate it from the panel.

3. **Basic Tests.**
    - Create several test orders with different payment methods.
    - Verify that:
        - The `_company_reference_code` metadata is generated.
        - The code appears on the order detail screen.
        - The format is `CMP-{ORDER_ID}-{YYYY}`, unless a filter modifies it.

4. **Existing Orders.**
    - Previous orders will not have a code (by design).
    - Ensure they do not present errors or alterations.

5. **Complete Flow.**
    - Perform a complete checkout from the frontend to confirm it does not interfere with emails, statuses, or notifications.

---

### Production Deployment

1. **Full Backup.**  
   Perform a backup of the database and files.

## Improvement

If more time were available, a configuration for the plugin would be implemented within the WooCommerce admin area. From that screen, it would be possible to:

- Customize the internal code format (e.g., define templates like `CMP-{ORDER_ID}-{Ymd}` or add prefixes by store or channel).
- Enable or disable code generation for specific order types, payment methods, or statuses.
- Launch a migration task to generate codes for old orders in batches, avoiding performance impact in high-volume stores.

This improvement would keep the core logic decoupled but provide much more flexibility to advanced users and support teams without editing code.

## Behavior in High-Volume Stores

The plugin is designed to have minimal impact even in stores with high order volume:

- The main logic executes only when a new order is created, using the `woocommerce_new_order` hook, so it does not add extra load to other front or admin pages.
- For each order, it only performs:
    - A metadata check to avoid generating the code more than once.
    - A metadata update (`update_meta_data` + `save`) to store the code.
- It does not create additional tables or execute complex queries on large datasets; it relies on the WooCommerce/WordPress metadata API, optimized for this type of operation.

In stores with thousands of daily orders, the cost per order remains very low. Still, if any bottleneck were detected in extreme scenarios, a future optimization could be to delegate code generation and saving to an asynchronous process queue (e.g., using Action Scheduler), further reducing work during the checkout flow.
