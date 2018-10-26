<?php

namespace ILI\FAUTemplates;

defined('ABSPATH') || exit;

class CustomPostTypes {
    
    public function __construct() {
        add_action('init', array($this, 'create_cpt_ilifautpl_topic_box'));
        add_action('save_post', array($this, 'ilifautpl_save_topic_box_meta_boxes'));
    }

    public function create_cpt_ilifautpl_topic_box()
    {
        register_post_type( 'ilifautpl_topic_box',
            array(
                'labels' => array(
                'name' => __( 'Themenboxen', 'ilifautpl' ),
                'singular_name' => __( 'Themenbox', 'ilifautpl'),
                'add_new' => __( 'Hinzufügen' ),
                'add_new_item' => __( 'Hinzufügen' ),
                'edit_item' => __( 'Themenbox bearbeiten' ),
                'new_item' => __( 'Themenbox hinzufügen' ),
                'view_item' => __( 'Themenbox Ansehen' ),
                'search_items' => __( 'Themenbox Suchen' ),
                'not_found' => __( 'Keine Themenboxen gefunden' ),
                'not_found_in_trash' => __( 'Keine Themenboxen gefunden' ),
              ),
              'public' => true,
              'has_archive' => false,
              'menu_icon' => 'dashicons-sticky',
              'menu_position' => 20,
              'supports' => array( 'editor', 'title', 'thumbnail' ),
              'taxonomies' => array('post_tag', 'category'),
              'register_meta_box_cb' => array($this, 'add_topic_box_metaboxes'),
            )
        );
    }
    
    public function add_topic_box_metaboxes() {
        add_meta_box(
            'ilifautpl-topic-box-target-id',
            esc_html__( 'Vernküpfter Inhalt (Titel)', 'ilifautpl' ),
            array($this, 'ilifautpl_topic_box_target_id_callback')
        );
        
        add_meta_box(
            'ilifautpl-topic-box-target-url',
            esc_html__( 'Vernküpfte URL', 'ilifautpl' ),
            array($this, 'ilifautpl_topic_box_target_url_callback')
        );
    }
    
    function ilifautpl_topic_box_target_id_callback()
    {
        global $post;
        
        wp_nonce_field( 'ilifautpl_topic_box_meta_boxes_nonce', 'ilifautpl_topic_box_meta_boxes_nonce' );

        $post_id = get_post_meta($post->ID, '_ilifautpl_topic_box_target_id', true);
        $this_post = get_post( $post_id );
        $post_title = ! empty( $this_post ) ? $this_post->post_title : 'Inhalt nicht gefunden';
        
        if( ! $post_id )
            echo '<p>' . __('Themenboxen müssen mit einem existierenden Inhalt verknüpft sein.', 'ilifautpl') . '</p>';
        
        echo '<select name="_ilifautpl_topic_box_target_id" class="widefat ilifautpl-select-posts" />';
        echo '<option value="">Bitte wählen&hellip;</option>';
        echo '<option value="' . $post_id . '" selected="selected">' . $this_post->post_title . '</option>';
        echo '</select>';
    }
    
    function ilifautpl_topic_box_target_url_callback()
    {
        global $post;
        
        $url = get_post_meta($post->ID, '_ilifautpl_topic_box_target_url', true);

        echo '<p><b>Achtung:</b> Dieses Feld überschreibt das vorangehende Feld "Verknüpfter Inhalt".</p>';
        echo '<input type="text" name="_ilifautpl_topic_box_target_url" id="_ilifautpl_topic_box_target_url" class="widefat" value="' . $url . '"/>';
    }
    
    /**
     * When the post is saved, saves our custom data.
     *
     * @param int $post_id
     */
    function ilifautpl_save_topic_box_meta_boxes( $post_id )
    {
        if ( ! isset( $_POST['ilifautpl_topic_box_meta_boxes_nonce'] ) )
            return;
        
        if ( ! wp_verify_nonce( $_POST['ilifautpl_topic_box_meta_boxes_nonce'], 'ilifautpl_topic_box_meta_boxes_nonce' ) )
            return;
        
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;
        
        if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }
        
        // Sanitize user input.
        $target_id = (int)$_POST['_ilifautpl_topic_box_target_id'];
        $target_url = sanitize_url( $_POST['_ilifautpl_topic_box_target_url'] );
        
        if( ! get_post_status( $target_id ) )
            $target_id = null;
        
        // Save
        update_post_meta( $post_id, '_ilifautpl_topic_box_target_id', $target_id );
        update_post_meta( $post_id, '_ilifautpl_topic_box_target_url', $target_url );
    }
}
