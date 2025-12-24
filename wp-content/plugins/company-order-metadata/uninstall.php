<?php

/**
 * Uninstall script for Company Order Metadata
 *
 * Se ejecuta solo cuando el plugin se elimina desde el panel de WordPress.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}
global $wpdb;

$meta_key = '_company_reference_code';

$wpdb->query(
    $wpdb->prepare(
        "DELETE FROM {$wpdb->postmeta} WHERE meta_key = %s",
        $meta_key
    )
);
