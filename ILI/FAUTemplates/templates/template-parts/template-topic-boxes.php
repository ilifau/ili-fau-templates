<?php

/**
 * Template Part "Topic Boxes"
 */

$ilifautpl_topic_boxes = implode(',', get_post_meta( get_the_ID(), '_ilifautpl_topic_boxes', true ) );

if( $ilifautpl_topic_boxes ):
    echo do_shortcode('[ilifautpl_topic_boxes ids="' . $ilifautpl_topic_boxes . '"]');
endif;

?>