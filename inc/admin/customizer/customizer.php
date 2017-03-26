<?php
/**
 * Courtyard Theme Customizer.
 *
 * @package Courtyard
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function courtyard_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    // Remove
    $wp_customize->remove_control( 'display_header_text' );
    $wp_customize->remove_control( 'header_textcolor' );

    //Custom Controls
    if ( class_exists( 'WP_Customize_Control' ) ):

        // Custom Checkbox Control Class
        class WP_Customize_Checkbox_Control extends WP_Customize_Control {
            public $type = 'checkbox';

            public function render_content() {
                ?>

                <label>
                    <span class="pt-checkbox-label"><?php echo esc_html( $this->label ); ?></span>

                    <span class="pt-on-off-switch">
                        <input class="pt-on-off-switch-checkbox"  type="checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />
                        <span class="pt-on-off-switch-label"></span>
                    </span>

                    <?php if ( ! empty( $this->description ) ) : ?>
                        <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                    <?php endif; ?>
                </label>
                <?php
            }
        }

        // Custom Font Size Control Class
        class WP_Customize_Font_Control extends WP_Customize_Control {

            public function render_content() {
                ?>

                <label class="pt-customizer-font">
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                    <input type="range" min="0" max="100" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
                    <input type="number" min="0" max="100" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
                </label>

                <?php
            }
        }

        // Image radio control
        class WP_Customizer_Image_Radio_Control extends WP_Customize_Control {

            public function render_content() {

            if ( empty( $this->choices ) )
                return;

            $name = '_customize-radio-' . $this->id;

            ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            <ul class="controls" id = 'pt-img-container'>

                <?php   foreach ( $this->choices as $value => $label ) :

                    $class = ($this->value() == $value)?'pt-radio-img-selected pt-radio-img-img':'pt-radio-img-img';

                    ?>

                    <li style="display: inline;">

                    <label>

                        <input <?php $this->link(); ?>style = 'display:none' type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />

                        <img src = '<?php echo esc_url( $label ); ?>' class = '<?php echo esc_attr( $class) ; ?>' />

                    </label>

                    </li>

                <?php   endforeach; ?>

            </ul>

            <?php
            }
        }

        // Theme Color
        class courtyard_theme_color_picker extends WP_Customize_Control {

            /**
             * Render the content on the theme customizer page
             */
            public function render_content() {

                if ( empty( $this->choices ) )
                    return;

                $name = $this->id;

                ?>

                <h3 class="courtyard-layout-title"><?php echo esc_html( $this->label ); ?></h3>

                <?php foreach ( $this->choices as $value => $label ) : ?>

                    <input type="radio" id="<?php echo esc_attr( $value ); ?>" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />

                    <label for="<?php echo esc_attr( $value ); ?>">
                        <?php echo esc_html( $label ); ?>
                        <span class="courtyard-radio-color">
                            <span class="courtyard-color-checked"></span>
                        </span>
                    </label>

                    <?php

                endforeach;
            }
        }

    endif;

/*----------------------------------------- HEADER PANEL ---------------------------------------------------------*/

    // General Settings
    $wp_customize->add_panel( 'courtyard_general', array(
        'priority'              => 2,
        'title'                 => esc_html__( 'General', 'courtyard' ),
    ) );

    // Pre Loader
    $wp_customize->add_section( 'courtyard_basic', array(
        'priority'              => 1,
        'title'                 => esc_html__( 'Basic', 'courtyard' ),
        'panel'                 => 'courtyard_general',
    ) );

    // Activate Optimize Bootstrap
    $wp_customize->add_setting( 'courtyard_optimize_bootstrap_activate', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_optimize_bootstrap_activate', array(
        'label'                 => esc_html__( 'Optimized Bootstrap', 'courtyard' ),
        'section'               => 'courtyard_basic',
        'settings'              => 'courtyard_optimize_bootstrap_activate',
    ) ) );

    // Activate Breadcrumbs
    $wp_customize->add_setting( 'courtyard_breadcrumbs_activate', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_breadcrumbs_activate', array(
        'label'                 => esc_html__( 'Breadcrumbs', 'courtyard' ),
        'section'               => 'courtyard_basic',
        'settings'              => 'courtyard_breadcrumbs_activate',
    ) ) );

    // Breadcrumbs Delimiter
    $wp_customize->add_setting( 'courtyard_breadcrumbs_sep', array(
        'default'               => '/',
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_sanitize_text',
    ) );

    $wp_customize->add_control( 'courtyard_breadcrumbs_sep', array(
        'type'                  => 'text',
        'label'                 => esc_html__( 'Breadcrumbs Delimiter', 'courtyard' ),
        'section'               => 'courtyard_basic',
        'settings'              => 'courtyard_breadcrumbs_sep',
    ) );

    // Background Image
    $wp_customize->add_section( 'background_image', array(
        'priority'              => 5,
        'title'                 => esc_html__( 'Background Image', 'courtyard' ),
        'panel'                 => 'courtyard_general',
    ) );

/*---------------------------------------- HEADER PANEL ----------------------------------------------------------*/

    // Header Panel
    $wp_customize->add_panel( 'courtyard_header', array(
        'priority'              => 3,
        'title'                 => esc_html__( 'Header', 'courtyard' ),
    ) );

    // Site Title & Tag-line
    $wp_customize->add_section( 'title_tagline', array(
        'priority'              => 3,
        'title'                 => esc_html__( 'Site Title & Tagline', 'courtyard' ),
        'panel'                 => 'courtyard_header',
    ) );

    // Header Secondary Logo
    $wp_customize->add_setting( 'courtyard_secondary_logo', array(
        'default'               => '',
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'courtyard_secondary_logo', array(
        'label'                 => esc_html__( 'Secondary Logo', 'courtyard' ),
        'section'               => 'title_tagline',
        'setting'               => 'courtyard_secondary_logo',
    ) ) );

    // Display Site Title
    $wp_customize->add_setting( 'courtyard_site_title_activate', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_site_title_activate', array(
        'label'                 => esc_html__( 'Display Site Title', 'courtyard' ),
        'section'               => 'title_tagline',
        'settings'              => 'courtyard_site_title_activate',
    ) ) );

    // Display tag line
    $wp_customize->add_setting( 'courtyard_site_tagline_activate', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_site_tagline_activate', array(
        'label'                 => esc_html__( 'Display Tagline', 'courtyard' ),
        'section'               => 'title_tagline',
        'settings'              => 'courtyard_site_tagline_activate',
    ) ) );

    // Header Media
    $wp_customize->add_section( 'header_image', array(
        'priority'              => 4,
        'title'                 => esc_html__( 'Header Media', 'courtyard' ),
        'panel'                 => 'courtyard_header',
    ) );

    // Activate header link back to home page
    $wp_customize->add_setting( 'courtyard_header_image_link_activate', array(
        'default'               => '',
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_header_image_link_activate', array(
        'label'                 => esc_html__( 'Activate Image Link Back to Home', 'courtyard' ),
        'section'               => 'header_image',
        'settings'              => 'courtyard_header_image_link_activate',
    ) ) );


/*------------------------------------------ COLOR PANEL --------------------------------------------------------*/

    // Colors
    $wp_customize->add_panel( 'courtyard_colors', array(
        'priority'              => 101,
        'title'                 => esc_html__( 'Colors', 'courtyard' ),
    ) );

    // Background Colors
    $wp_customize->add_section( 'colors', array(
        'priority'              => 1,
        'title'                 => esc_html__( 'Background Color', 'courtyard' ),
        'panel'                 => 'courtyard_colors',
    ) );

    // Theme Color
    $wp_customize->add_section( 'courtyard_custom_theme_color_sec', array(
        'priority'              => 1,
        'title'                 => esc_html__( 'Theme Color', 'courtyard' ),
        'panel'                 => 'courtyard_colors',
    ) );

    $wp_customize->add_setting( 'courtyard_theme_color', array(
        'default'               => 'maroon',
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_sanitize_theme_color',
    ) );

    $wp_customize->add_control( new courtyard_theme_color_picker( $wp_customize, 'courtyard_theme_color', array(
        'label'                 => esc_html__( 'Color Schemes', 'courtyard' ),
        'section'               => 'courtyard_custom_theme_color_sec',
        'settings'              => 'courtyard_theme_color',
        'type'                  => 'radio',
        'choices'               => array(
            'watermelon'            => esc_html__( 'Watermelon', 'courtyard' ),
            'red'                   => esc_html__( 'Red', 'courtyard' ),
            'orange'                => esc_html__( 'Orange', 'courtyard' ),
            'yellow'                => esc_html__( 'Yellow', 'courtyard' ),
            'lime'                  => esc_html__( 'Lime', 'courtyard' ),
            'green'                 => esc_html__( 'Green', 'courtyard' ),
            'mint'                  => esc_html__( 'Mint', 'courtyard' ),
            'teal'                  => esc_html__( 'Teal', 'courtyard' ),
            'sky-blue'              => esc_html__( 'Sky Blue', 'courtyard' ),
            'blue'                  => esc_html__( 'Blue', 'courtyard' ),
            'purple'                => esc_html__( 'Purple', 'courtyard' ),
            'pink'                  => esc_html__( 'Pink', 'courtyard' ),
            'magenta'               => esc_html__( 'Magenta', 'courtyard' ),
            'plum'                  => esc_html__( 'Plum', 'courtyard' ),
            'brown'                 => esc_html__( 'Brown', 'courtyard' ),
            'maroon'                => esc_html__( 'Maroon', 'courtyard' )
        ),
    ) ) );

    // Custom Primary Color
    $wp_customize->add_setting( 'courtyard_custom_primary_color', array(
        'default'               => '',
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'courtyard_custom_primary_color', array(
        'label'                 => esc_html__( 'Primary Color', 'courtyard' ),
        'description'           => esc_html__( 'You can override theme color by custom color', 'courtyard' ),
        'section'               => 'courtyard_custom_theme_color_sec',
        'settings'              => 'courtyard_custom_primary_color',
    ) ) );

    // Custom Secondary Color
    $wp_customize->add_setting( 'courtyard_custom_secondary_color', array(
        'default'               => '',
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'courtyard_custom_secondary_color', array(
        'label'                 => esc_html__( 'Secondary Color', 'courtyard' ),
        'section'               => 'courtyard_custom_theme_color_sec',
        'settings'              => 'courtyard_custom_secondary_color',
    ) ) );

/*---------------------------------------- SINGLE POST ----------------------------------------------------------*/

    // Posts Settings
    $wp_customize->add_panel( 'courtyard_post_settings', array(
        'priority'              => 112,
        'title'                 => esc_html__( 'Post Settings', 'courtyard' ),
    ) );

    // Post Settings
    $wp_customize->add_section( 'courtyard_post_sidebar_section', array(
        'priority'              => 2,
        'title'                 => esc_html__( 'Sidebar', 'courtyard' ),
        'panel'                 => 'courtyard_post_settings',
    ) );

    // Post global sidebar.
    $wp_customize->add_setting( 'courtyard_post_global_sidebar', array(
        'default'           => 'right_sidebar',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'courtyard_sanitize_choices',                
    ) );

    $wp_customize->add_control( new WP_Customizer_Image_Radio_Control( $wp_customize, 'courtyard_post_global_sidebar', array(
        'type'               => 'radio',
        'priority'           => 1,
        'label'              => esc_html__('Available Sidebar', 'courtyard'),
        'description'        => esc_html__('Select default layout for single posts. This layout will be reflected in all single posts unless unique layout is set for specific post.', 'courtyard'),
        'section'            => 'courtyard_post_sidebar_section',
        'settings'           => 'courtyard_post_global_sidebar',
        'choices'            => array(
            'right_sidebar'                 => get_template_directory_uri() . '/images/right-sidebar.png',
            'left_sidebar'                  => get_template_directory_uri() . '/images/left-sidebar.png',
            'no_sidebar_full_width'         => get_template_directory_uri() . '/images/no-sidebar-full-width-layout.png',
        ),
    ) ) );

    // Posts meta Settings
    $wp_customize->add_section( 'courtyard_post_meta_settings_sec', array(
        'priority'              => 3,
        'title'                 => esc_html__( 'Meta', 'courtyard' ),
        'panel'                 => 'courtyard_post_settings',
    ) );

    // Post Author
    $wp_customize->add_setting( 'courtyard_post_meta_author', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_post_meta_author', array(
        'label'                 => esc_html__( 'Post Author', 'courtyard' ),
        'section'               => 'courtyard_post_meta_settings_sec',
        'settings'              => 'courtyard_post_meta_author',
    ) ) );

    // Post Date
    $wp_customize->add_setting( 'courtyard_post_meta_date', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_post_meta_date', array(
        'label'                 => esc_html__( 'Post Date', 'courtyard' ),
        'section'               => 'courtyard_post_meta_settings_sec',
        'settings'              => 'courtyard_post_meta_date',
    ) ) );

    // Post Categories
    $wp_customize->add_setting( 'courtyard_post_meta_categories', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_post_meta_categories', array(
        'label'                 => esc_html__( 'Post Categories', 'courtyard' ),
        'section'               => 'courtyard_post_meta_settings_sec',
        'settings'              => 'courtyard_post_meta_categories',
    ) ) );

    // Post Tags
    $wp_customize->add_setting( 'courtyard_post_meta_tags', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_post_meta_tags', array(
        'label'                 => esc_html__( 'Post Tags', 'courtyard' ),
        'section'               => 'courtyard_post_meta_settings_sec',
        'settings'              => 'courtyard_post_meta_tags',
    ) ) );

    // Posts meta Settings
    $wp_customize->add_section( 'courtyard_post_navigation_settings_sec', array(
        'priority'              => 4,
        'title'                 => esc_html__( 'Navigation', 'courtyard' ),
        'panel'                 => 'courtyard_post_settings',
    ) );

    // Post Nex/Prev article
    $wp_customize->add_setting( 'courtyard_post_nex_prev_article', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_post_nex_prev_article', array(
        'label'                 => esc_html__( 'Next/Previous Article', 'courtyard' ),
        'section'               => 'courtyard_post_navigation_settings_sec',
        'settings'              => 'courtyard_post_nex_prev_article',
    ) ) );

    
/*------------------------------------------- BLOG PANEL -------------------------------------------------------*/

    // Archive/Category Settings
    $wp_customize->add_panel( 'courtyard_blog_settings', array(
        'priority'              => 113,
        'title'                 => esc_html__( 'Blog Settings', 'courtyard' ),
    ) );

    // Settings
    $wp_customize->add_section( 'courtyard_blog_general_sec', array(
        'priority'              => 1,
        'title'                 => esc_html__( 'Settings', 'courtyard' ),
        'panel'                 => 'courtyard_blog_settings',
    ) );

    // Excerpt Length
    $wp_customize->add_setting( 'courtyard_blog_post_excerpt_length', array(
        'default'               => 40,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_sanitize_number_range',
    ) );

    $wp_customize->add_control( new WP_Customize_Font_Control(  $wp_customize, 'courtyard_blog_post_excerpt_length', array(
        'label'                 => esc_html__( 'Excerpt Length', 'courtyard' ),
        'section'               => 'courtyard_blog_general_sec',
        'settings'              => 'courtyard_blog_post_excerpt_length',
    ) ) );

    // Show Read More
    $wp_customize->add_setting( 'courtyard_blog_show_read_more', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_blog_show_read_more', array(
        'label'                 => esc_html__( 'Read More Button', 'courtyard' ),
        'section'               => 'courtyard_blog_general_sec',
        'settings'              => 'courtyard_blog_show_read_more',
    ) ) );

    // Read More Text
    $wp_customize->add_setting( 'courtyard_blog_read_more_text', array(
        'default'               => esc_html__( 'Read More', 'courtyard' ),
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_sanitize_text',
    ) );

    $wp_customize->add_control( 'courtyard_blog_read_more_text', array(
        'type'                  => 'text',
        'label'                 => esc_html__( 'Read More Text', 'courtyard' ),
        'section'               => 'courtyard_blog_general_sec',
        'settings'              => 'courtyard_blog_read_more_text',
    ) );

    // Blog Sidebar
    $wp_customize->add_section( 'courtyard_blog_sidebar_section', array(
        'priority'              => 2,
        'title'                 => esc_html__( 'Sidebar', 'courtyard' ),
        'panel'                 => 'courtyard_blog_settings',
    ) );

    // blog global sidebar.
    $wp_customize->add_setting( 'courtyard_blog_global_sidebar', array(
        'default'           => 'right_sidebar',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'courtyard_sanitize_choices',                 
    ) );

    $wp_customize->add_control( new WP_Customizer_Image_Radio_Control( $wp_customize, 'courtyard_blog_global_sidebar', array(
        'type'               => 'radio',
        'priority'           => 1,
        'label'              => esc_html__('Available Sidebar', 'courtyard'),
        'description'        => esc_html__('Select default sidebar. This sidebar will be reflected in all pages unless unique layout is set for specific page as well as reflected in whole site archives, categories, search page etc.', 'courtyard'),
        'section'            => 'courtyard_blog_sidebar_section',
        'settings'           => 'courtyard_blog_global_sidebar',
        'choices'            => array(
            'right_sidebar'                 => get_template_directory_uri() . '/images/right-sidebar.png',
            'left_sidebar'                  => get_template_directory_uri() . '/images/left-sidebar.png',
            'no_sidebar_full_width'         => get_template_directory_uri() . '/images/no-sidebar-full-width-layout.png',
        ),
    ) ) );

    // Posts meta Settings
    $wp_customize->add_section( 'courtyard_blog_post_meta_sec', array(
        'priority'              => 3,
        'title'                 => esc_html__( 'Meta', 'courtyard' ),
        'panel'                 => 'courtyard_blog_settings',
    ) );

    // Date
    $wp_customize->add_setting( 'courtyard_blog_post_date', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_blog_post_date', array(
        'label'                 => esc_html__( 'Post Date', 'courtyard' ),
        'section'               => 'courtyard_blog_post_meta_sec',
        'settings'              => 'courtyard_blog_post_date',
    ) ) );

    // Author
    $wp_customize->add_setting( 'courtyard_blog_post_author', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_blog_post_author', array(
        'label'                 => esc_html__( 'Post Author', 'courtyard' ),
        'section'               => 'courtyard_blog_post_meta_sec',
        'settings'              => 'courtyard_blog_post_author',
    ) ) );
    

/*--------------------------------------- FOOTER PANEL -----------------------------------------------------------*/
    // Footer
    $wp_customize->add_panel( 'courtyard_footer_settings', array(
        'priority'              => 124,
        'title'                 => esc_html__( 'Footer', 'courtyard' ),
    ) );

    // Back To Top Settings
    $wp_customize->add_section( 'courtyard_footer_back_to_top_sec', array(
        'priority'              => 1,
        'title'                 => esc_html__( 'Back to Top', 'courtyard' ),
        'panel'                 => 'courtyard_footer_settings',
    ) );

    // Go To Top Button
    $wp_customize->add_setting( 'courtyard_footer_go_to_top', array(
        'default'               => 1,
        'capability'            => 'edit_theme_options',
        'sanitize_callback'     => 'courtyard_checkbox_sanitize',
    ) );

    $wp_customize->add_control( new WP_Customize_Checkbox_Control( $wp_customize, 'courtyard_footer_go_to_top', array(
        'label'                 => esc_html__( 'Go to Top Button', 'courtyard' ),
        'section'               => 'courtyard_footer_back_to_top_sec',
        'settings'              => 'courtyard_footer_go_to_top',
    ) ) );

    // Footer Widgets
    $wp_customize->add_section( 'courtyard_footer_widgets_sec', array(
        'priority'              => 2,
        'title'                 => esc_html__( 'Footer Widgets', 'courtyard' ),
        'panel'                 => 'courtyard_footer_settings',
    ) );

    // Footer Widget Area Layout
    $wp_customize->add_setting( 'courtyard_footer_widget_area_layout', array(
        'default'           => '1by4_1by4_1by4_1by4',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'courtyard_sanitize_choices',                
    ) );

    $wp_customize->add_control( new WP_Customizer_Image_Radio_Control( $wp_customize, 'courtyard_footer_widget_area_layout', array(
        'type'               => 'radio',
        'label'              => esc_html__('Footer Layout', 'courtyard'),
        'description'        => esc_html__('Select layout for footer widget columns. It generates some widget areas for Footer based on the layout.', 'courtyard'),
        'section'            => 'courtyard_footer_widgets_sec',
        'settings'           => 'courtyard_footer_widget_area_layout',
        'choices'            => array(
            '1by1'                      => get_template_directory_uri() . '/images/right-sidebar.png',
            '1by2_1by2'                 => get_template_directory_uri() . '/images/left-sidebar.png',
            '1by3_1by3_1by3'            => get_template_directory_uri() . '/images/no-sidebar-full-width-layout.png',
            '1by4_1by4_1by4_1by4'       => get_template_directory_uri() . '/images/no-sidebar-full-width-layout.png',
        ),
    ) ) );

    /**
     * Checkbox Sanitize
     */
    function courtyard_checkbox_sanitize( $input ) {
        if ( $input == 1 ) {
            return 1;
        } else {
            return '';
        }
    }

    /**
     * Sanitize Choices
     */
    function courtyard_sanitize_choices( $input, $setting ) {
        global $wp_customize;

        $control = $wp_customize->get_control( $setting->id );

        if ( array_key_exists( $input, $control->choices ) ) {
            return $input;
        } else {
            return $setting->default;
        }
    }

    /**
     * Text
     */
    function courtyard_sanitize_text( $input ) {
        return wp_kses_post( force_balance_tags( $input ) );
    }

    /**
     * Number Range Sanitize
     */
    function courtyard_sanitize_number_range( $number, $setting ) {

        // Ensure input is an absolute integer.
        $number = absint( $number );

        // Get the input attributes associated with the setting.
        $atts = $setting->manager->get_control( $setting->id )->input_attrs;

        // Get minimum number in the range.
        $min = ( isset( $atts['min'] ) ? $atts['min'] : $number );

        // Get maximum number in the range.
        $max = ( isset( $atts['max'] ) ? $atts['max'] : $number );

        // Get step.
        $step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );

        // If the number is within the valid range, return it; otherwise, return the default
        return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
    }

    /**
     * Header Layout Sanitize
     */
    function courtyard_sanitize_theme_color( $value ) {
        if ( ! in_array( $value, array( 'watermelon', 'red', 'orange', 'yellow', 'lime', 'green', 'mint', 'teal', 'sky-blue', 'blue', 'purple', 'pink', 'magenta', 'plum', 'brown', 'maroon' ) ) )
            $value = 'sky-blue';

        return $value;
    }
}
add_action( 'customize_register', 'courtyard_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function courtyard_customize_preview_js() {
    wp_enqueue_script( 'courtyard_customizer', get_template_directory_uri() . '/js/customizer-preview.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'courtyard_customize_preview_js' );
