<?php

/**
 * Template Part "Topic Boxes"
 */
 
// Get Topic Boxes
$ilifautpl_selected_topic_boxes = get_post_meta( get_the_ID(), '_ilifautpl_topic_boxes', true );

$ilifautpl_topic_boxes = get_posts(array(
    'post_type' => 'ilifautpl_topic_box',
    'num_posts' => -1,
    'hide_empty' => true,
    'include' => $ilifautpl_selected_topic_boxes,
));

$ilifautpl_topix_box_excerpt_length = 230;

echo '<div class="content">';
    echo '<div class="container">';
        echo '<div class="row">';
            foreach( $ilifautpl_topic_boxes as $box ) {
                $ilifautpl_topic_box_target_id = get_post_meta( $box->ID, '_ilifautpl_topic_box_target_id', true );
                
                if( ! $ilifautpl_topic_box_target_id )
                    continue;
                
                $ilifautpl_topic_box_excerpt = strlen( $box->post_content ) >= $ilifautpl_topix_box_excerpt_length ? substr( $box->post_content, 0, $ilifautpl_topix_box_excerpt_length ) . '&hellip; <a href="' . esc_url( get_permalink( $ilifautpl_topic_box_target_id ) ) . '">' . __('Weiter lesen', 'ilifautpl') . '</a>' : $box->post_content;
                
                echo '<div class="ilifautpl-topic-box">';
                    echo get_the_post_thumbnail( $box->ID, 'ilifautpl-topic-box', array( 'class' => 'ilifautpl-topic-box-image' ) );
                    echo '<h3 class="ilifautpl-topic-box-title">' . $box->post_title . '</h3>';
                    echo '<p class="ilifautpl-topic-box-context">' . $ilifautpl_topic_box_excerpt . '</p>';
                echo '</div>';
            }
        echo '</div>';
    echo '</div>';
echo '</div>';

?>