# Company Order Metadata

Plugin para WooCommerce que genera automáticamente un código interno de referencia para cada pedido nuevo.  
No modifica archivos del core ni del tema, por lo que es seguro, ligero y fácil de mantener.

---

## Arquitectura

La estructura del plugin se organiza en tres capas principales:

### 1. Bootstrap (archivo principal)

- **Archivo:** `company-order-metadata.php`
- Registra el plugin, carga las clases de `includes/` y `includes/admin/`, e inicia todo con un `init` sencillo.
- Desde aquí se instancian las clases que manejan la lógica de negocio y la parte visual del administrador.

### 2. Lógica de negocio (dominio)

- **Clase:** `Company\OrderMeta\Company_Order_Meta`  
  (archivo: `includes/class-company-order-meta.php`)
- Contiene la funcionalidad principal:
    - Escucha la creación de nuevos pedidos.
    - Genera un código con el formato `CMP-{ORDER_ID}-{YYYY}`.
    - Evita que se genere más de una vez por pedido.
    - Expone el filtro `company_order_reference_code` para que otros plugins puedan personalizar el código antes de guardarlo.
- El código se guarda como metadato del pedido (`_company_reference_code`) utilizando las funciones nativas de WooCommerce.

### 3. Interfaz de administración (presentación)

- **Clase:** `Company\OrderMeta\Admin\Admin_Display`  
  (archivo: `includes/admin/class-admin-display.php`)
- Se encarga únicamente de mostrar el código interno en la pantalla de detalle del pedido:
    - Usa un hook de WooCommerce correspondiente al área de administración.
    - Muestra el valor como un campo de solo lectura, claro y bien identificado.

**Ventajas de esta arquitectura:**

- Lógica desacoplada del entorno visual.
- Fácil de extender en el futuro (otros puntos de visualización, nuevos formatos, etc.).
- Mayor facilidad para realizar tests unitarios.

---

## Hooks principales y razones de uso

### `woocommerce_new_order` (action)

Se ejecuta al crear un pedido nuevo.  
Ideal para generar el código en ese momento.

**Por qué usarlo:**

- Es un hook específico de creación de pedidos.
- Entrega directamente el `order_id`.
- Evita sobrecarga porque solo se ejecuta cuando corresponde.
- Facilita verificar si el código ya existía antes de generarlo.

---

### `woocommerce_admin_order_data_after_billing_address` (action)

Se usa para mostrar el código dentro del panel de administración en la vista del pedido.

**Ventajas:**

- Está pensado justamente para extender esa zona del admin.
- Permite acceder al objeto `WC_Order` sin consultas adicionales.
- No genera conflictos visuales ni funcionales.

---

### `company_order_reference_code` (filter)

Filtro propio del plugin, pensado para modificar el código antes de guardarlo.

**Para qué sirve:**

- Da libertad a otros desarrolladores para cambiar el formato o agregar lógica propia.
- Permite añadir prefijos, sufijos o condiciones personalizadas (por país, método de pago, etc.).
- Sigue el estándar de WordPress (`apply_filters()`).

---

## Cómo probarlo antes de producción

Aunque el plugin es ligero, maneja datos importantes.  
Por eso es recomendable probarlo bien antes del despliegue final.

### Pruebas en staging o preproducción

1. **Clonar entorno.**  
   Usa una copia reciente de base de datos y archivos del sitio en un staging idéntico al de producción.

2. **Instalación.**  
   Sube la carpeta `company-order-metadata` a `wp-content/plugins/` y actívalo desde el panel.

3. **Pruebas básicas.**
    - Crea varios pedidos de prueba con distintos métodos de pago.
    - Verifica que:
        - Se genere el metadato `_company_reference_code`.
        - El código aparezca en la pantalla de detalle del pedido.
        - El formato sea `CMP-{ORDER_ID}-{YYYY}`, salvo que un filtro lo modifique.

4. **Pedidos existentes.**
    - Los pedidos previos no tendrán código (por diseño).
    - Asegúrate de que no presenten errores ni alteraciones.

5. **Flujo completo.**
    - Haz un checkout completo desde el frontend para confirmar que no interfiere con emails, estados o notificaciones.

---

### Despliegue en producción

1. **Backup completo.**  
   Realiza una copia de seguridad de base de datos y archivos.

## Mejora

Si dispusiera de más tiempo, implementaría la configuración para el plugin dentro del área de administración de WooCommerce. Desde esa pantalla se podría:

- Personalizar el formato del código interno (por ejemplo, definir plantillas como `CMP-{ORDER_ID}-{Ymd}` o agregar prefijos por tienda o canal).
- Activar o desactivar la generación del código para ciertos tipos de pedidos, métodos de pago o estados específicos.
- Lanzar una tarea de migración que permita generar códigos para pedidos antiguos en lotes, evitando impactar el rendimiento en tiendas con alto volumen.

Esta mejora mantendría la lógica central desacoplada, pero daría mucha más flexibilidad a usuarios avanzados y equipos de soporte sin necesidad de editar código.

## Comportamiento en tiendas de alto volumen

El plugin está diseñado para tener un impacto mínimo incluso en tiendas con un alto volumen de pedidos:

- La lógica principal se ejecuta únicamente cuando se crea un pedido nuevo, utilizando el hook `woocommerce_new_order`, por lo que no añade carga extra a otras páginas del front ni del admin.
- Para cada pedido solo realiza:
    - Una comprobación de metadatos para evitar generar el código más de una vez.
    - Una actualización de metadatos (`update_meta_data` + `save`) para almacenar el código.
- No crea tablas adicionales ni ejecuta consultas complejas sobre conjuntos grandes de datos; se apoya en la API de metadatos de WooCommerce/WordPress, que está optimizada para este tipo de operaciones.

En tiendas con miles de pedidos diarios, el coste por pedido sigue siendo muy bajo. Aun así, si se detectara algún cuello de botella en escenarios extremos, una posible optimización futura sería delegar la generación y guardado del código a una cola de procesos asíncrona (por ejemplo, usando Action Scheduler), reduciendo aún más el trabajo realizado durante el flujo de checkout.
