<?php

namespace ILI\FAUTemplates;

defined('ABSPATH') || exit;

class Shortcodes {
    
    function __construct() {
        add_shortcode('ilifautpl_topic_boxes', array($this, 'ilifautpl_shortcode_topic_boxes'));
        add_shortcode('themenboxen', array($this, 'ilifautpl_shortcode_topic_boxes'));
    }

    function ilifautpl_shortcode_topic_boxes( $atts ) {
        extract( shortcode_atts( array (
            'ids' => '',
            'text_length' => '',
            'read_more' => '',
            'remove_skew' => '',
        ), $atts ) );

        if( ! $ids )
            return '';
        
        $ids = str_replace(' ', '', $ids);
        $ids = explode(',', $ids);
        
        $options = get_option('ili_fau_templates');
        
        if( $text_length ) {
            $topic_box_excerpt_length = absint($text_length);
        } else {
            $topic_box_excerpt_length = $options['ili_fau_templates_topic_box_excerpt_length_default'] ? $options['ili_fau_templates_topic_box_excerpt_length_default'] : 150;
        }

        foreach( $ids as $key => $id ) {
            $ids[$key] = (int)$id;
        }
        
        if( is_page_template( 'templates/template-landing-page.php' ) ) {
            $show_read_more = get_post_meta( get_the_ID(), '_ilifautpl_show_topic_boxes_read_more', true ) === '1' ?: false;
        } else {
            $show_read_more = $read_more !== '' ? filter_var( $read_more, FILTER_VALIDATE_BOOLEAN ) : true;
        }
        
        $args = array(
            'post_type' => 'ilifautpl_topic_box',
            'include' => $ids,
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'post__in',
        );

        $topic_boxes = get_posts( $args );

        if( empty( $topic_boxes ) )
            return;

        if( filter_var( $remove_skew, FILTER_VALIDATE_BOOLEAN ) ) {
            echo '<style>.ilifautpl-topic-box::after{display:none}</style>';
        }

        $html = '<div class="ilifautpl-topic-boxes">';
            foreach( $topic_boxes as $key => $box ) {
                $target_id = get_post_meta( $box->ID, '_ilifautpl_topic_box_target_id', true );

                if( ! $target_id )
                    continue;
                    
                $topic_box_url = esc_url( get_permalink( $target_id ) );
                
                if( strlen( $box->post_content ) > $topic_box_excerpt_length ) {
                    $topic_box_excerpt = preg_replace('/\s+?(\S+)?$/', '', substr($box->post_content, 0, $topic_box_excerpt_length)) . '&hellip;';
                } else {
                    $topic_box_excerpt = $box->post_content;
                }
                
                $html .= '<div class="ilifautpl-topic-box" id="ilifautpl-topic-box-' . $key  . '">';
                    $html .= '<div aria-hidden="true" role="presentation" tabindex="-1" class="passpartout" itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject">';
                        $html .= '<meta itemprop="url" content="' . get_the_post_thumbnail_url( $box->ID ) . '">';
                        $html .= '<a href="' . $topic_box_url . '">';
                            $html .= get_the_post_thumbnail(
                                $box->ID,
                                'ilifautpl-topic-box',
                                array(
                                    'class' => 'ilifautpl-topic-box-image',
                                    'itemprop' => 'thumbnailUrl',
                                )
                            );
                        $html .= '</a>';
                    $html .= '</div>';
                    $html .= '<h3 itemprop="title"><a href="' . $topic_box_url . '">' . $box->post_title . '</a></h3>';
                    $html .= '<p itemprop="description">' . $topic_box_excerpt;

                    if( $show_read_more ) {
                        $html .= ' <a aria-hidden="true" tabindex="-1" href="' . $topic_box_url . '">' . __('Weiterlesen', 'ilifautpl') . '</a><span class="screen-reader-text">' . __('Weiterlesen', 'ilifautpl') . '</span>';
                    }
                    
                    $html .= '</p>';
                $html .= '</div>';
            }
        $html .= '</div>';

        return $html;
    }
}
