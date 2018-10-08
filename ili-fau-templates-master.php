<?php

/**
 * Plugin Name:     ILI FAU Templates
 * Plugin URI:      https://ili.fau.de
 * Description:     Erweiterung der FAU-Website um zusätzliche Templates
 * Version:         0.0.1
 * Author:          Sebastian Honert
 * Author URI:      https://www.ili.fau.de/team/sebastian-honert/
 * License:         GNU General Public License v2
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path:     /languages
 * Text Domain:     ili-fau-templates
 */

namespace ILI\FAUTemplates;

use ILI\FAUTemplates\Main;

defined('ABSPATH') || exit;

const RRZE_PHP_VERSION = '7.1';
const RRZE_WP_VERSION = '4.9';

register_activation_hook(__FILE__, 'ILI\FAUTemplates\activation');
register_deactivation_hook(__FILE__, 'ILI\FAUTemplates\deactivation');

add_action('plugins_loaded', 'ILI\FAUTemplates\loaded');

// CSS und JS einbinden.
add_action('wp_enqueue_scripts', 'ILI\FAUTemplates\register_scripts_and_styles', 99, 1);
add_action('admin_enqueue_scripts', 'ILI\FAUTemplates\register_admin_scripts_and_styles', 99, 1);
add_action('init', 'ILI\FAUTemplates\add_image_sizes', 99, 1);

/*
 * Einbindung der Sprachdateien.
 * @return void
 */
function load_textdomain() {
    load_plugin_textdomain('ili-fau-templates', FALSE, sprintf('%s/languages/', dirname(plugin_basename(__FILE__))));
}

/*
 * Bildgrößen definieren
 * @return void
 */
function add_image_sizes() {
    add_image_size( 'ilifautpl-slide', 1920, 1080, true );
    add_image_size( 'ilifautpl-topic-box', 800, 450, true);
}

/*
 * Wird durchgeführt, nachdem das Plugin aktiviert wurde.
 * @return void
 */
function activation() {
    // Sprachdateien werden eingebunden.
    load_textdomain();
    
    // Überprüft die minimal erforderliche PHP- u. WP-Version.
    system_requirements();

    // Ab hier können die Funktionen hinzugefügt werden, 
    // die bei der Aktivierung des Plugins aufgerufen werden müssen.
    // Bspw. wp_schedule_event, flush_rewrite_rules, etc.    
}

/*
 * Wird durchgeführt, nachdem das Plugin deaktiviert wurde.
 * @return void
 */
function deactivation() {
    // Hier können die Funktionen hinzugefügt werden, die
    // bei der Deaktivierung des Plugins aufgerufen werden müssen.
    // Bspw. wp_clear_scheduled_hook, flush_rewrite_rules, etc.
    
    // Bildgröße für Slides entfernen
    remove_image_size( 'ilifautpl-slide' );
    remove_image_size( 'ilifautpl-topic-box' );
}

/*
 * Überprüft die minimal erforderliche PHP- u. WP-Version.
 * @return void
 */
function system_requirements() {
    $error = '';

    if (version_compare(PHP_VERSION, RRZE_PHP_VERSION, '<')) {
        $error = sprintf(__('Your server is running PHP version %s. Please upgrade at least to PHP version %s.', 'ili-fau-templates'), PHP_VERSION, RRZE_PHP_VERSION);
    }

    if (version_compare($GLOBALS['wp_version'], RRZE_WP_VERSION, '<')) {
        $error = sprintf(__('Your Wordpress version is %s. Please upgrade at least to Wordpress version %s.', 'ili-fau-templates'), $GLOBALS['wp_version'], RRZE_WP_VERSION);
    }

    // Wenn die Überprüfung fehlschlägt, dann wird das Plugin automatisch deaktiviert.
    if (!empty($error)) {
        deactivate_plugins(plugin_basename(__FILE__), FALSE, TRUE);
        wp_die($error);
    }
}

/*
 * Wird durchgeführt, nachdem das WP-Grundsystem hochgefahren
 * und alle Plugins eingebunden wurden.
 * @return void
 */
function loaded() {
    // Sprachdateien werden eingebunden.
    load_textdomain();
    
    // Automatische Laden von Klassen.
    autoload();
}

/*
 * Automatische Laden von Klassen.
 * @return void
 */
function autoload() {
    require 'autoload.php';
    return new Main(plugin_basename(__FILE__));
}

/*
 * Laden von CSS und JS
 * @return void
 */
function register_scripts_and_styles() {
    $is_ilifautpl_landing_page = ilifautpl_is_landing_page('frontend');
    $has_ilifautpl_topic_box_shortcode = has_shortcode(get_post_field('post_content', get_the_ID()), 'ilifautpl_topic_boxes');

    if( ! $is_ilifautpl_landing_page && ! $has_ilifautpl_topic_box_shortcode )
        return;

    wp_register_style( 'ili-fau-templates', plugins_url('assets/css/main.css', __FILE__ ) );
    wp_enqueue_style( 'ili-fau-templates' );
    
    wp_register_script( 'ili-fau-templates-main', plugins_url('assets/js/main.js', __FILE__), array('jquery'), '0.0.1', true );
    wp_enqueue_script( 'ili-fau-templates-main' );

    // Localize scripts
    $ilifautpl_slider_has_dots = get_post_meta( get_the_ID(), '_ilifautpl_slider_has_dots', true) ?: true;
    $ilifautpl_slider_has_arrows = get_post_meta( get_the_ID(), '_ilifautpl_slider_has_arrows', true) ?: true;
    $ilifautpl_slider_fade = get_post_meta( get_the_ID(), '_ilifautpl_slider_fade', true) ?: true;
    $ilifautpl_slider_skew = get_post_meta( get_the_ID(), '_ilifautpl_slider_skew', true) ?: true;

    wp_localize_script( 'ili-fau-templates-main', 'ilifautpl_slider_has_dots', array( $ilifautpl_slider_has_dots ) );
    wp_localize_script( 'ili-fau-templates-main', 'ilifautpl_slider_has_arrows', array( $ilifautpl_slider_has_arrows ) );
    wp_localize_script( 'ili-fau-templates-main', 'ilifautpl_slider_fade', array( $ilifautpl_slider_fade ) );
    wp_localize_script( 'ili-fau-templates-main', 'ilifautpl_slider_skew', array( $ilifautpl_slider_skew ) );

    // Nur Landing Page (Slick Slider)
    if( $is_ilifautpl_landing_page ) {
        wp_register_style( 'ili-fau-templates-slick', plugins_url('inc/slick/slick.css', __FILE__ ) );
        wp_enqueue_style( 'ili-fau-templates-slick' );
        
        wp_register_style( 'ili-fau-templates-slick-theme', plugins_url('inc/slick/slick-theme.css', __FILE__ ) );
        wp_enqueue_style( 'ili-fau-templates-slick-theme' );
        
        wp_register_script( 'ili-fau-templates-slick', plugins_url('inc/slick/slick.js', __FILE__), array('jquery'), '1.8.0', true );
        wp_enqueue_script( 'ili-fau-templates-slick' );
    }
}

/*
 * Laden von CSS und JS für Admin-Bereich
 * @return void
 */
function register_admin_scripts_and_styles()
{
    if( ! ilifautpl_is_landing_page('admin') )
        return;

    $options = get_option('ili_fau_templates');
    $max_num_slides = $options['ili_fau_templates_max_num_slides'] ? $options['ili_fau_templates_max_num_slides'] : 3;
    
    wp_register_script( 'ili-fau-templates-admin', plugins_url('assets/js/admin.js', __FILE__), array('jquery'), '0.0.1', true );
    wp_enqueue_script( 'ili-fau-templates-admin' );
    
    wp_register_style( 'ili-fau-templates-admin', plugins_url('assets/css/admin.css', __FILE__ ) );
    wp_localize_script( 'ili-fau-templates-admin', 'ilifautpl_options_admin', array(
        'max_num_slides' => $max_num_slides
    ) );
    wp_enqueue_style( 'ili-fau-templates-admin' );
    
    // Multi Select
    wp_register_script( 'ili-fau-templates-multiselect', plugins_url('inc/lou-multi-select-e052211/js/jquery.multi-select.js', __FILE__), array('jquery'), '0.9.12', true );
    wp_enqueue_script( 'ili-fau-templates-multiselect' );
    
    wp_register_style( 'ili-fau-templates-multiselect', plugins_url('inc/lou-multi-select-e052211/css/multi-select.dist.css', __FILE__ ) );
    wp_enqueue_style( 'ili-fau-templates-multiselect' );
}

/*
 * Prüft den Seitenkontext
 * @return bool
 */
function ilifautpl_is_landing_page( $context = 'frontend' ) {

    $post_id = get_the_ID();

    // Frontend
    $allowed_templates = array('templates/template-landing-page.php');

    if( $context === 'frontend' )
        return in_array( get_page_template_slug( $post_id ), $allowed_templates ); 

    // Backend
    $screen = function_exists('get_current_screen') ? get_current_screen( $post_id ) : '';
    $allowed_post_types = array('post', 'page');
    $allowed_templates = array('templates/template-landing-page.php');

    $is_ili_fau_template = in_array( $screen->post_type, $allowed_post_types ) && ( in_array( get_page_template_slug( $post_id ), $allowed_templates ) );
    $is_ili_fau_settings = $screen->base === 'settings_page_ili-fau-templates';

    $ilifautpl_is_landing_page = $is_ili_fau_template || $is_ili_fau_settings;
    
    return $ilifautpl_is_landing_page;
}
