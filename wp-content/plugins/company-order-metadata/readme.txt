=== Company Order Metadata ===
Contributors: Juan Sebastian Ormaza
Tags: woocommerce, orders, metadata, admin
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Genera un código interno de referencia para los pedidos de WooCommerce y lo muestra en el panel de administración.

== Description ==

Este plugin añade un código interno de referencia a cada pedido de WooCommerce cuando se crea un nuevo pedido.

El código se genera con el formato:

CMP-{ORDER_ID}-{YYYY}

y se guarda como metadato del pedido. Posteriormente se muestra en la pantalla de edición del pedido en el panel de administración de WooCommerce como un campo de solo lectura claramente etiquetado.

== Features ==

* Generación automática de código interno al crear el pedido.
* Formato: CMP-{ORDER_ID}-{YYYY}.
* Guardado en el metadato del pedido (`_company_reference_code`).
* Visualización en la pantalla de edición del pedido en el admin de WooCommerce.
* Hook de filtro para modificar el código generado desde otros plugins.

== Installation ==

1. Asegúrate de tener instalado y activo WooCommerce.
2. Sube la carpeta `company-order-metadata` al directorio `/wp-content/plugins/`.
3. Activa el plugin desde el menú **Plugins** de WordPress.
4. Crea un nuevo pedido desde la tienda.
5. Revisa el pedido en **WooCommerce → Pedidos → Editar** para ver el código interno.
