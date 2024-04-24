<?php
/*
Plugin Name: Custom Features
Description: Añade productos al sitio.
Version: 1.0
Author: Cristian Alessndro Verdin Mata
*/

if (!defined('ABSPATH')) {
    exit; // Salir si se accede directamente al archivo
}

// Registrar Custom Post Type "Productos"
function custom_features_register_productos_post_type() {
    $labels = array(
        'name'                  => _x('Productos', 'Nombre general del tipo de publicación', 'custom-features'),
        'singular_name'         => _x('Producto', 'Nombre singular del tipo de publicación', 'custom-features'),
        'menu_name'             => __('Productos', 'custom-features'),
        'name_admin_bar'        => __('Producto', 'custom-features'),
        'archives'              => __('Archivo de Productos', 'custom-features'),
        'attributes'            => __('Atributos del Producto', 'custom-features'),
        'parent_item_colon'     => __('Producto Padre:', 'custom-features'),
        'all_items'             => __('Todos los Productos', 'custom-features'),
        'add_new_item'          => __('Agregar Nuevo Producto', 'custom-features'),
        'add_new'               => __('Agregar Nuevo', 'custom-features'),
        'new_item'              => __('Nuevo Producto', 'custom-features'),
        'edit_item'             => __('Editar Producto', 'custom-features'),
        'update_item'           => __('Actualizar Producto', 'custom-features'),
        'view_item'             => __('Ver Producto', 'custom-features'),
        'view_items'            => __('Ver Productos', 'custom-features'),
        'search_items'          => __('Buscar Productos', 'custom-features'),
        'not_found'             => __('No se encontraron productos', 'custom-features'),
        'not_found_in_trash'    => __('No se encontraron productos en la papelera', 'custom-features'),
        'featured_image'        => __('Imagen Destacada del Producto', 'custom-features'),
        'set_featured_image'    => __('Guardar Imagen Destacada del Producto', 'custom-features'),
        'remove_featured_image' => __('Eliminar Imagen Destacada del Producto', 'custom-features'),
        'use_featured_image'    => __('Usar como Imagen Destacada del Producto', 'custom-features'),
        'insert_into_item'      => __('Insertar en Producto', 'custom-features'),
        'uploaded_to_this_item' => __('Subido a este Producto', 'custom-features'),
        'items_list'            => __('Lista de Productos', 'custom-features'),
        'items_list_navigation' => __('Navegación de Productos', 'custom-features'),
        'filter_items_list'     => __('Filtrar Lista de Productos', 'custom-features'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array('slug' => 'producto'),
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 6,
        'supports'              => array('title', 'editor', 'thumbnail'),
        'menu_icon'             => 'dashicons-cart', // Icono de carro de compras
    );

    register_post_type('producto', $args);
}

add_action('init', 'custom_features_register_productos_post_type', 0);

// Mostrar precio en la página de edición de productos
add_action('add_meta_boxes', 'custom_features_add_price_metabox');

function custom_features_add_price_metabox() {
    add_meta_box(
        'custom_features_price_metabox',
        __('Precio', 'custom-features'),
        'custom_features_price_metabox_callback',
        'producto',
        'normal',
        'default'
    );
}

function custom_features_price_metabox_callback($post) {
    // Obtener el precio actual del producto
    $precio = get_post_meta($post->ID, '_precio', true);
    ?>
    <label for="custom_features_price"><?php _e('Precio:', 'custom-features'); ?></label>
    <input type="number" id="custom_features_price" name="custom_features_price" value="<?php echo esc_attr($precio); ?>" step="0.01" min="0">
    <?php
}

// Guardar el precio del producto al actualizar el producto
add_action('save_post', 'custom_features_save_price_meta', 10, 2);

function custom_features_save_price_meta($post_id, $post) {
    // Verificar si el usuario actual tiene permisos para editar el producto
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Verificar si el campo de precio está establecido
    if (isset($_POST['custom_features_price'])) {
        // Limpiar y guardar el valor del precio
        $precio = sanitize_text_field($_POST['custom_features_price']);
        update_post_meta($post_id, '_precio', $precio);
    }
}
?>
