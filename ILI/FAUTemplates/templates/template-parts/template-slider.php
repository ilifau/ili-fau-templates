<?php

/**
 * Template Part "Slick Slider"
 */

$ilifautpl_meta = get_post_meta( get_the_ID(), '_ilifautpl_slides', true );

if( is_array( $ilifautpl_meta ) && ! empty( $ilifautpl_meta ) ):
    echo '<h2 class="screen-reader-text">' . __('Slider', 'ilifautpl') . '</h2>';
    echo '<div class="slick-slider">';
        foreach( $ilifautpl_meta as $key => $slide ):
            $link_html = ! empty( $ilifautpl_meta[$key]['link'] ) ? ' <a href="' . $ilifautpl_meta[$key]['link'] . '">' . __('Zum Inhalt', 'ilifautpl') . '</a>' : '';
            $ilifautpl_headline = ! empty( $ilifautpl_meta[$key]['link'] ) ? '<a href="' . $ilifautpl_meta[$key]['link'] . '">' . $ilifautpl_meta[$key]['headline'] . '</a>' : $ilifautpl_meta[$key]['headline'];
            
            echo '<div class="slick-slide" style="background:url(' . $ilifautpl_meta[$key]['url'] . ') center center;">';
                echo '<div class="container">';
                    echo '<div class="row">';
                        echo '<div class="ilifautpl-slider-content">';
                            echo '<h3><a href="' . $ilifautpl_meta[$key]['link'] . '">' . $ilifautpl_headline . '</a></h3><br />';
                            echo '<p>' . $ilifautpl_meta[$key]['subtitle'] . '<span class="ilifautpl-slide-read-more">' . $link_html . '</span></p>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        endforeach;
    echo '</div>';
    echo '<span class="ilifautpl-arrow prev"></span>';
    echo '<span class="ilifautpl-arrow next"></span>';
endif;

?>