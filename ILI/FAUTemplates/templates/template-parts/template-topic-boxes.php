<?php

/**
 * Template Part "Topic Boxes"
 */

$ilifautpl_topic_boxes = get_post_meta( get_the_ID(), '_ilifautpl_topic_boxes', true );

if( $ilifautpl_topic_boxes ):
    echo '<section id="ilifautpl-topic-boxes" aria-label="">';
        $topic_boxes_skew = get_post_meta( get_the_ID(), '_ilifautpl_topic_boxes_skew', true );
        if( $topic_boxes_skew === '0' ) {
            echo '<style>.ilifautpl-topic-box::after{display:none}</style>';
        }
        
        $ilifautpl_topic_boxes_string = implode(',', $ilifautpl_topic_boxes );
        echo do_shortcode('[ilifautpl_topic_boxes ids="' . $ilifautpl_topic_boxes_string . '"]');
    echo '</section>';
endif;
