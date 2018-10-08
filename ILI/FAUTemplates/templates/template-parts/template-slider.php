<?php

/**
 * Template Part "Slick Slider"
 */

$options = get_option('ili_fau_templates');

echo '<section id="ilifautpl-hero" aria-label="">';
echo '<div class="ilifautpl-hero-inner">';
echo '<h2 class="screen-reader-text">' . __('Slider', 'ilifautpl') . '</h2>';
echo '<div class="slick-slider">';

$ilifautpl_meta = get_post_meta( get_the_ID(), '_ilifautpl_slides', true );

$ilifautpl_has_thumb = has_post_thumbnail( get_the_ID() );
$ilifautpl_has_slides = is_array( $ilifautpl_meta ) && ! empty( $ilifautpl_meta ) && ! empty( $ilifautpl_meta[0]['url'] );

// Show slider only if post/page has thumbnail or slides attached
if( $ilifautpl_has_slides || $ilifautpl_has_thumb ) {
    
    // Load slides section css only if slides attached
    if( $ilifautpl_has_slides ) {
        $ilifautpl_slider_skew = get_post_meta( get_the_ID(), '_ilifautpl_slider_skew', true );
        
        if( (int)$ilifautpl_slider_skew === 0 ) {
            echo '<style>#ilifautpl-hero::after { transform: none }</style>';
        }
    }
    
    // Post/Page has slides
    if( $ilifautpl_has_slides ) {
        foreach( $ilifautpl_meta as $key => $slide ):
            $link_html = ! empty( $ilifautpl_meta[$key]['link'] ) ? ' <a href="' . $ilifautpl_meta[$key]['link'] . '">' . __('Weiterlesen', 'ilifautpl') . '</a>' : '';
            $ilifautpl_headline = ! empty( $ilifautpl_meta[$key]['link'] ) ? '<a href="' . $ilifautpl_meta[$key]['link'] . '">' . $ilifautpl_meta[$key]['headline'] . '</a>' : $ilifautpl_meta[$key]['headline'];

            echo '<div class="slick-slide" style="background: #f1f1f1 url(' . $ilifautpl_meta[$key]['url'] . ') center center;">';
                echo '<div class="container">';
                    echo '<div class="row">';
                        echo '<a href="' . $ilifautpl_meta[$key]['link'] . '" class="container ilifautpl-slider-content">';
                            echo '<h3>' . $ilifautpl_headline . '</h3>';
                            // echo '<p>' . $ilifautpl_meta[$key]['subtitle'] . '<span class="ilifautpl-slide-read-more">' . $link_html . '</span></p>';
                            echo '<p>' . $ilifautpl_meta[$key]['subtitle'] . '</p>';
                        echo '</a>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        endforeach;
    
    // No slides available, show thumbnail instead
    } else {
        echo '<div class="slick-slide" style="background: #f1f1f1 url(' . get_the_post_thumbnail_url( get_the_ID(), 'full' ) . ') center center;"></div>';
    }

// Neither slides not thumbnail => fallback
} else {
    echo '<div class="slick-slide" style="background: #f1f1f1 url(' . $options['ili_fau_templates_slide_default'] . ') center center"></div>';
}

echo '</div>'; // Slick Slider

// Arrow Nav
$ilifautpl_slider_has_arrows = get_post_meta( get_the_ID(), '_ilifautpl_slider_has_arrows', true );
if( (int)$ilifautpl_slider_has_arrows === 1 ) {
    echo '<button class="ilifautpl-arrow prev"></button>';
    echo '<button class="ilifautpl-arrow next"></button>';
}

echo '</div>'; // Hero Inner
echo '</section>'; // Hero Section
