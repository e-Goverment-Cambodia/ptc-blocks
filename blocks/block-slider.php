<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.channchetra.com
 * @since      1.0.0
 *
 * @package    Ptc_Blocks
 * @subpackage Ptc_Blocks/blocks
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ptc_Blocks
 * @subpackage Ptc_Blocks/blocks
 * @author     Chann Chetra <chetra-chann@mptc.gov.kh>
 */
$option = new Ptc_Blocks();
if ( function_exists( 'lazyblocks' ) ) :

    lazyblocks()->add_block( array(
        'id' => 142,
        'title' => 'Block Slider',
        'icon' => 'dashicons dashicons-images-alt2',
        'keywords' => array(
            0 => 'Slider',
            1 => 'Big',
        ),
        'slug' => 'lazyblock/block-slider',
        'description' => '',
        'category' => 'lazyblocks',
        'category_label' => 'lazyblocks',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'html' => false,
            'multiple' => true,
            'inserter' => true,
        ),
        'ghostkit' => array(
            'supports' => array(
                'spacings' => false,
                'display' => false,
                'scrollReveal' => false,
            ),
        ),
        'controls' => array(
            'control_slider_cat' => array(
                'type' => 'select',
                'name' => 'category',
                'default' => 'select',
                'label' => 'Category',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'required' => 'false',
                'placeholder' => '',
                'characters_limit' => '',
                'choices' => $option->mptc_cat_listing(),
            ),
            'control_f5dbea4dca' => array(
                'label' => 'Posts/Page',
                'name' => 'posts_per_page',
                'type' => 'range',
                'child_of' => '',
                'default' => '',
                'characters_limit' => '',
                'placeholder' => '',
                'help' => 'Select Post amount to show in Carousel',
                'placement' => 'inspector',
                'hide_if_not_selected' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'required' => 'false',
                'choices' => array(
                ),
                'checked' => 'false',
                'allow_null' => 'false',
                'multiple' => 'false',
                'allowed_mime_types' => array(
                ),
                'alpha' => 'false',
                'min' => '0',
                'max' => '10',
                'step' => '',
                'date_time_picker' => 'date_time',
                'multiline' => 'false',
                'rows_min' => '',
                'rows_max' => '',
                'rows_label' => '',
                'rows_add_button_label' => '',
                'rows_collapsible' => 'true',
                'rows_collapsed' => 'true',
            ),
        ),
        'code' => array(
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
            'use_php' => true,
        ),
        'condition' => array(
            0 => 'page',
        ),
    ) );
    
endif;


// filter for Frontend output.
add_filter( 'lazyblock/block-slider/frontend_callback', 'ptc_slider_output', 10, 2 );
// filter for Editor output.
add_filter( 'lazyblock/block-slider/editor_callback', 'ptc_slider_output', 10, 2 );
if ( ! function_exists( 'ptc_slider_output' ) ) :
    /**
     * Test Render Callback
     *
     * @param string $output - block output.
     * @param array  $attributes - block attributes.
     */
    function ptc_slider_output( $output, $attributes ) {
        ob_start();
        $obj = new Ptc_Blocks(); 
        // WP_Query arguments
        $args = [
            'post_type'			=> ['post'],
            'post_status'		=> ['publish'],
            'posts_per_page'	=> $attributes['posts_per_page'],
            'cat'				=> $attributes['category']
        ];
        
        // The Query
        $slider_query = new WP_Query( $args );
        // The Loop
        if ( $slider_query->have_posts() ) { ?>
        <!-- </div> -->
        <div class="slideshow">
            <div class="slick-slideshow">
        <?php
            while ( $slider_query->have_posts() ) :
                $slider_query->the_post(); ?>
                <div class="slick-item">
                    <div class="slick-photo">
                        <div class="aspect-ratio">
                            <div class="img" style="background-image: url(<?php echo $obj->ptc_post_thumbnail('large'); ?>);" ></div>
                        </div>
                    </div>
                    <div class="primary-background-color">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </div>
                </div>
            <?php
            endwhile; ?>
            </div>
        </div>
        <?php
        }
        // Restore original Post Data
        wp_reset_postdata();
        // Return code
        return ob_get_clean();
    }
endif;
// disable block frontend wrapper.
add_filter( 'lazyblock/block-slider/frontend_allow_wrapper', '__return_false' );