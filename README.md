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
Ideal for generating the code at that
