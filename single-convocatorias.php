<?php
if (!defined('ABSPATH')) {
    exit;
}

$target_page = get_page_by_path('convocatoria');
$redirect    = $target_page ? get_permalink($target_page) : home_url('/convocatoria/');
$current_id  = get_queried_object_id();

if ($current_id) {
    $redirect = add_query_arg('convocatoria', $current_id, $redirect);
}

wp_safe_redirect($redirect);
exit;
