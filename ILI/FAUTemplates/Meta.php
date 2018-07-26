<?php

namespace ILI\FAUTemplates;

defined('ABSPATH') || exit;

class Meta {
    
    protected $templates;
    
    public function __construct() {
        add_action( 'add_meta_boxes', array($this, 'ilifautpl_add_meta_boxes'), 10, 2 );
        add_action( 'save_post', array($this, 'ilifautpl_save_meta_boxes'), 10, 2 );
        add_action( 'wp_ajax_ilifautpl_get_slide_image', array($this, 'wp_ajax_ilifautpl_get_slide_image'), 10, 2);
    }
    
    public function ilifautpl_add_meta_boxes() {
        global $post;
        
        $screens = get_post_types();
        
        foreach ( $screens as $screen ) {
            $template = get_post_meta( $post->ID, '_wp_page_template', true );
            
            // Frontpage slides
            if( 'templates/template-frontpage.php' === $template ) {
                
                wp_enqueue_media();
                
                add_meta_box(
                    'ilifautpl-slides',
                    esc_html__( 'Slider (ILI FAU Templates)', 'mnmlwp' ),
                    array($this, 'frontpage_slides_callback'),
                    $screen
                );
            }
        }
    }
    
    public function frontpage_slides_callback() {
        wp_nonce_field( 'ilifautpl_meta_boxes_nonce', 'ilifautpl_meta_boxes_nonce' );

        $slides = get_post_meta( $post->ID, '_ilifautpl_slides', false );

        if( empty( $slides ) ) {
            $slides = array();
            $slides[0] = '';
        }
        
        // Input fields
        echo '<label class="ilifautpl-label" for="ilifautpl-frontpage-slides">Slides (URL)</label>';
        
        foreach( $slides as $key => $val ) {
            echo '<div class="ilifautpl-input-slide-wrapper">';
            echo '<input class="ilifautpl-input ilifautpl-input-slide" type="text" name="ilifautpl-frontpage-slides[]" value="' . $slides[$key] . '" placeholder="URL&hellip;">';
            echo '<a class="button ilifautpl-input-slide-media">' . __('Media', 'ili-fau-templates') . '</a><a class="button ilifautpl-remove-slide">' . __('Löschen', 'ilifautpl') . '</a>';
            echo '</div>';
        }

        echo '<a class="button ilifautpl-add-slide">' . __('Slide hinzufügen', 'ili-fau-templates') . '</a>';
        echo '<br><br><input type="submit" name="submit" id="submit" class="button button-primary" value="Änderungen speichern">';
    }
    
    // Refresh slide preview image
    function ilifautpl_get_slide_image() {
        if( isset( $_GET['id'] ) ) {
            $image = wp_get_attachment_image( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), 'medium', false, array( 'id' => 'ilifautpl-slide-preview' ) );
            
            $data = array(
                'image'    => $image,
            );
            
            wp_send_json_success( $data );
        } else {
            wp_send_json_error();
        }
    }
    
    /**
     * When the post is saved, saves our custom data.
     *
     * @param int $post_id
     */
    function ilifautpl_save_meta_boxes( $post_id )
    {
        if ( ! isset( $_POST['ilifautpl_meta_boxes_nonce'] ) )
            return;

        if ( ! wp_verify_nonce( $_POST['ilifautpl_meta_boxes_nonce'], 'ilifautpl_meta_boxes_nonce' ) )
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
        update_post_meta( $post_id, '_ilifautpl_slides', $data['ilifautpl-slides'] );
    }
}
