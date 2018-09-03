<?php

if( ! defined('WP_UNINSTALL_PLUGIN') ) {
    die;
}

global $wpdb;

// Delete options
$option_name = 'ili_fau_templates';

delete_option($option_name);
delete_site_option($option_name); // Multisite

// Delete topic boxes
$args = array(
  'post_type' => 'ilifautpl_topic_box',
  'nopaging' => true,
);

$query = new WP_Query ($args);

while( $query->have_posts () ) {
  $query->the_post ();
  $id = get_the_ID ();
  wp_delete_post ($id, true);
}

// Todo: delete meta data
