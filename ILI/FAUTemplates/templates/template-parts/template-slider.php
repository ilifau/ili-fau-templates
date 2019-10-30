<?php

/**
 * Template Part "Slick Slider"
 */

$options = get_option('ili_fau_templates');
$upload_dir = wp_upload_dir();

$ilifautpl_slider_skew = get_post_meta( get_the_ID(), '_ilifautpl_slider_skew', true );
$ilifautpl_slider_classes = $ilifautpl_slider_skew === '0' ? 'ilifautpl-dont-skew' : '';

echo '<section id="ilifautpl-hero" class="' . $ilifautpl_slider_classes . '" aria-label="' . __('Slider', 'ilifautpl') . '">';
echo '<div class="ilifautpl-hero-inner">';
echo '<div class="slick-slider">';

$ilifautpl_meta = get_post_meta( get_the_ID(), '_ilifautpl_slides', true );
$ilifautpl_order = array_column($ilifautpl_meta, 'order');
array_multisort($ilifautpl_order, SORT_ASC, $ilifautpl_meta);

function ilifautpl_show_fallback_title() {
    $ilifautpl_show_title = get_post_meta( get_the_ID(), '_ilifautpl_show_fallback_title', true );
    if( $ilifautpl_show_title !== '0' ) {
        echo '<div class="container">';
            echo '<div class="row">';
                echo '<div class="container ilifautpl-slider-content">';
                        echo '<h3 class="ilifautpl-no-border">' . get_the_title( get_the_ID() ) . '</h3>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }
}

function ilifautpl_get_slide_style( $slideID, $position = '' ) {
    if( ! $slideID )
        return '';

    $ilifautpl_1920 = wp_get_attachment_image_src( $slideID, 'ilifautpl-1920' );
    $ilifautpl_1600 = wp_get_attachment_image_src( $slideID, 'ilifautpl-1600' );
    $ilifautpl_1366 = wp_get_attachment_image_src( $slideID, 'ilifautpl-1366' );
    $ilifautpl_1024 = wp_get_attachment_image_src( $slideID, 'ilifautpl-1024' );
    $ilifautpl_800 = wp_get_attachment_image_src( $slideID, 'ilifautpl-800' );
    $ilifautpl_640 = wp_get_attachment_image_src( $slideID, 'ilifautpl-640' );

    $ilifautpl_1920_portrait = wp_get_attachment_image_src( $slideID, 'ilifautpl-1920-portrait' );
    $ilifautpl_1600_portrait = wp_get_attachment_image_src( $slideID, 'ilifautpl-1600-portrait' );
    $ilifautpl_1366_portrait = wp_get_attachment_image_src( $slideID, 'ilifautpl-1366-portrait' );
    $ilifautpl_1024_portrait = wp_get_attachment_image_src( $slideID, 'ilifautpl-1024-portrait' );
    $ilifautpl_800_portrait = wp_get_attachment_image_src( $slideID, 'ilifautpl-800-portrait' );
    $ilifautpl_640_portrait = wp_get_attachment_image_src( $slideID, 'ilifautpl-640-portrait' );

    $style = '<style>
    #ilifautpl-hero .slick-slide {
        background-size:cover;
    }

    #ilifautpl-hero .slick-slide-' . $slideID . ' {
        ' . $position . '
    }

    @media screen and (orientation: landscape) and (min-width: 1601px) {
        #ilifautpl-hero .slick-slide-' . $slideID . ' {
            background-image: url(' . $ilifautpl_1920[0] . ');
        }
    }

    @media screen and (orientation: landscape) and (min-width: 1367px) and (max-width: 1600px) {
        #ilifautpl-hero .slick-slide-' . $slideID . ' {
            background-image: url(' . $ilifautpl_1600[0] . ');
        }
    }

    @media screen and (orientation: landscape) and (min-width: 1025px) and (max-width: 1366px) {
        #ilifautpl-hero .slick-slide-' . $slideID . ' {
            background-image: url(' . $ilifautpl_1366[0] . ');
        }
    }

    @media screen and (orientation: landscape) and (min-width: 801px) and (max-width: 1024px) {
        #ilifautpl-hero .slick-slide-' . $slideID . ' {
            background-image: url(' . $ilifautpl_1024[0] . ');
        }
    }

    @media screen and (orientation: landscape) and (min-width: 641px) and (max-width: 800px) {
        #ilifautpl-hero .slick-slide-' . $slideID . ' {
            background-image: url(' . $ilifautpl_800[0] . ');
        }
    }

    @media screen and (orientation: landscape) and (max-width: 640px) {
        #ilifautpl-hero .slick-slide-' . $slideID . ' {
            background-image: url(' . $ilifautpl_640[0] . ');
        }
    }

    /* Portrait */

    @media screen and (orientation: portrait) and (min-height: 1601px) {
        #ilifautpl-hero .slick-slide-' . $slideID . ' {
            background-image: url(' . $ilifautpl_1920_portrait[0] . ');
        }
    }

    @media screen and (orientation: portrait) and (min-height: 1367px) and (max-height: 1600px) {
        #ilifautpl-hero .slick-slide-' . $slideID . ' {
            background-image: url(' . $ilifautpl_1600_portrait[0] . ');
        }
    }

    @media screen and (orientation: portrait) and (min-height: 1025px) and (max-height: 1366px) {
        #ilifautpl-hero .slick-slide-' . $slideID . ' {
            background-image: url(' . $ilifautpl_1366_portrait[0] . ');
        }
    }

    @media screen and (orientation: portrait) and (min-height: 801px) and (max-height: 1024px)  {
        #ilifautpl-hero .slick-slide-' . $slideID . ' {
            background-image: url(' . $ilifautpl_1024_portrait[0] . ');
        }
    }

    @media screen and (orientation: portrait) and (min-height: 641px) and (max-height: 800px) {
        #ilifautpl-hero .slick-slide-' . $slideID . ' {
            background-image: url(' . $ilifautpl_800_portrait[0] . ');
        }
    }
    
    @media screen and (orientation: portrait) and (max-height: 640px) {
        #ilifautpl-hero .slick-slide-' . $slideID . ' {
            background-image: url(' . $ilifautpl_640_portrait[0] . ');
        }
    }

    </style>';

    return $style;
}

$ilifautpl_has_thumb = has_post_thumbnail( get_the_ID() );
$ilifautpl_has_slides = is_array( $ilifautpl_meta ) && ! empty( $ilifautpl_meta ) && ! empty( $ilifautpl_meta[0]['id'] );

// Show slider only if post/page has thumbnail or slides attached
if( $ilifautpl_has_slides || $ilifautpl_has_thumb ) {
    
    // Post/Page has slides
    if( $ilifautpl_has_slides ) {
        foreach( $ilifautpl_meta as $key => $slide ):
            $link_html = ! empty( $slide['link'] ) ? ' <a href="' . $ilifautpl_meta[$key]['link'] . '">' . __('Weiterlesen', 'ilifautpl') . '</a>' : '';
            $ilifautpl_headline = $slide['headline'];
            $ilifautpl_slide_atts = fau_get_image_attributs( $slide['id'] );

            echo '<div class="slick-slide slick-slide-' . $slide['id'] . '">';
                echo ilifautpl_get_slide_style( $slide['id'], $ilifautpl_meta[$key]['position'] );
            
                $ilifautpl_slider_overlay = get_post_meta( get_the_ID(), '_ilifautpl_slider_overlay', true );  
                echo ( ! empty( $ilifautpl_slider_overlay ) && $ilifautpl_slider_overlay !== 'none' ) ? '<div class="ilifautpl-slide-overlay ilifautpl-slide-overlay--' . $ilifautpl_slider_overlay . '"></div>' : '';

                echo '<div class="container">';
                    echo '<div class="row">';
                        echo '<div class="container ilifautpl-slider-content">';
                            echo '<a href="' . $slide['link'] . '">';
                                if( ! empty( $ilifautpl_headline ) )
                                    echo '<h3>' . $ilifautpl_headline . '</h3>';
                                
                                if( ! empty( $slide['subtitle'] ) )
                                    echo '<p><a href="' . $slide['link'] . '">' . $slide['subtitle'] . '</a></p>';
                            
                            // echo '<p>' . $slide['subtitle'] . '<span class="ilifautpl-slide-read-more">' . $link_html . '</span></p>';
                            echo '</a>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
                
                if( ! empty( $ilifautpl_slide_atts['credits'] ) ) {
                    echo '<div class="ilifautpl-slide-credits">' . $ilifautpl_slide_atts['credits'] . '</div>';
                }
            echo '</div>';
        endforeach;
    
    // No slides available, show attachment image instead
    } else {
        echo '<div class="slick-slide" style="background: #f1f1f1 url(' . get_the_post_thumbnail_url( get_the_ID(), 'full' ) . ') ' . $ilifautpl_meta[$key]['position'] . '">';
            echo ilifautpl_show_fallback_title();
        echo '</div>';
    }

// Neither slides not thumbnail => fallback
} else {
    // If default slide is URL
    if( $options['ili_fau_templates_slide_default'] !== 0 ) {
        $basename = basename( plugin_dir_path(  dirname( __FILE__, 4 ) ) );
        $ilifautpl_default_image = esc_url( plugins_url() . '/' . $basename . '/assets/img/slide-default.jpg' );
    } else {
        $ilifautpl_slide_atts = fau_get_image_attributs( $options['ili_fau_templates_slide_default'] );
        $ilifautpl_default_image = esc_url( $upload_dir['baseurl'] . '/' . $ilifautpl_slide_atts['attachment_file'] );
    }
    
    echo '<div class="slick-slide" style="background: #f1f1f1 url(' . $ilifautpl_default_image . ') ' . $ilifautpl_meta[$key]['position'] . '">';
        echo ilifautpl_show_fallback_title();
    echo '</div>';
}

echo '</div>'; // Slick Slider

// Arrow Nav
$ilifautpl_slider_has_arrows = get_post_meta( get_the_ID(), '_ilifautpl_slider_has_arrows', true );
if( (int)$ilifautpl_slider_has_arrows === 1 ) {
    echo '<button class="ilifautpl-arrow prev" aria-label="' . __('Previous') . '"></button>';
    echo '<button class="ilifautpl-arrow next" aria-label="' . __('Next') . '"></button>';
}

echo '</div>'; // Hero Inner
echo '</section>'; // Hero Section
