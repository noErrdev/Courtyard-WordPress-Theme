<?php

/**
 * Rooms Widget section.
 */
class courtyard_rooms_widget extends WP_Widget {
  function __construct() {
    $widget_ops = array( 'classname' => 'pt-rooms-section', 'description' => esc_html__( 'Display some pages as rooms.', 'courtyard' ) );
    $control_ops = array( 'width' => 200, 'height' =>250 );
    parent::__construct( false, $name = esc_html__( 'PT: Rooms', 'courtyard' ), $widget_ops, $control_ops);
  }

    function form( $instance ) {
      $instance = wp_parse_args(
        (array) $instance, array(
          'title'             => '',
          'sub_title'         => '',
          'button_text'       => esc_html__( 'view all rooms', 'courtyard'),
          'button_url'        => '#',
          'background_color'  => '',
        )
      );
      ?>

      <div class="pt-room">
        <div class="pt-admin-input-wrap">
          <p><?php esc_html_e('This widget displays all pages related to Single Room Template.', 'courtyard'); ?></p>
          <p><em><?php esc_html_e('Tip: to rearrange the room order, edit each room page and add a value in Page Attributes > Order', 'courtyard'); ?></em></p>
        </div><!-- .pt-admin-input-wrap -->

        <div class="pt-admin-input-wrap">

          <div class="pt-admin-input-label">
              <label
              for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title', 'courtyard'); ?></label>
          </div><!-- .pt-admin-input-label -->

          <div class="pt-admin-input-holder">
              <input type="text" id="<?php echo $this->get_field_id('title'); ?>"
                 name="<?php echo $this->get_field_name('title'); ?>"
                 value="<?php echo esc_attr($instance['title']); ?>"
                 placeholder="<?php esc_attr_e('Title', 'courtyard'); ?>">
          </div><!-- .pt-admin-input-holder -->

          <div class="clear"></div>

        </div><!-- .pt-admin-input-wrap -->

        <div class="pt-admin-input-wrap">

          <div class="pt-admin-input-label">
              <label
              for="<?php echo $this->get_field_id('sub_title'); ?>"><?php esc_html_e('Sub Title', 'courtyard'); ?></label>
          </div><!-- .pt-admin-input-label -->

          <div class="pt-admin-input-holder">
              <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('sub_title'); ?>"
                  name="<?php echo $this->get_field_name('sub_title'); ?>"
                  placeholder="<?php esc_attr_e('Short description', 'courtyard'); ?>"><?php echo esc_textarea($instance['sub_title']); ?></textarea>
          </div><!-- .pt-admin-input-holder -->

          <div class="clear"></div>

      </div><!-- .pt-admin-input-wrap -->

      <div class="pt-admin-input-wrap">

        <div class="pt-admin-input-label">
          <label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php esc_html_e( 'Button Text', 'courtyard' ); ?></label>
        </div><!-- .pt-admin-input-label -->

        <div class="pt-admin-input-holder">
          <input type="text" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo esc_attr( $instance['button_text'] ); ?>" placeholder="<?php esc_attr_e( 'View All Rooms', 'courtyard' ); ?>">
        </div><!-- .pt-admin-input-holder -->

        <div class="clear"></div>

      </div><!-- .pt-admin-input-wrap -->

      <div class="pt-admin-input-wrap">

        <div class="pt-admin-input-label">
          <label for="<?php echo $this->get_field_id( 'button_url' ); ?>"><?php esc_html_e( 'Botton URL', 'courtyard' ); ?></label>
        </div><!-- .pt-admin-input-label -->

        <div class="pt-admin-input-holder">
          <input type="text" id="<?php echo $this->get_field_id( 'button_url' ); ?>" name="<?php echo $this->get_field_name( 'button_url' ); ?>" value="<?php echo esc_attr( $instance['button_url'] ); ?>" placeholder="<?php esc_attr_e( 'http://precisethemes.com/', 'courtyard' ); ?>">
        </div><!-- .pt-admin-input-holder -->

        <div class="clear"></div>

      </div><!-- .pt-admin-input-wrap -->

      <div class="pt-admin-input-wrap">

        <div class="pt-admin-input-label">
            <label
            for="<?php echo $this->get_field_id('background_color'); ?>"><?php esc_html_e('Color', 'courtyard'); ?></label>
        </div><!-- .pt-admin-input-label -->

        <div class="pt-admin-input-holder">
            <input type="text" id="<?php echo $this->get_field_id('background_color'); ?>"
                class="pt-color-picker"
                name="<?php echo $this->get_field_name('background_color'); ?>"
                value="<?php echo esc_attr($instance['background_color']); ?>">
            <p><em><?php esc_html_e('Choose the background color for the widget section.', 'courtyard'); ?></em></p>
        </div><!-- .pt-admin-input-holder -->

          <div class="clear"></div>

      </div><!-- .pt-admin-input-wrap -->

      </div><!-- .pt-room -->
    <?php }

    function update( $new_instance, $old_instance ) {
      $instance = $old_instance;
      
      $instance['title']              = sanitize_text_field( $new_instance['title'] );
      $instance['button_text']        = sanitize_text_field( $new_instance['button_text'] );
      $instance['button_url']         = esc_url_raw( $new_instance['button_url'] );
      $instance['background_color']   = sanitize_text_field( $new_instance['background_color'] );
      if ( current_user_can( 'unfiltered_html' ) )
        $instance['sub_title'] = $new_instance['sub_title'];
      else
        $instance['sub_title'] = wp_kses( trim( wp_unslash( $new_instance['sub_title'] ) ), wp_kses_allowed_html( 'post' ) );
      return $instance;
    }

    function widget( $args, $instance ) {
      ob_start();
      extract( $args );
      global $post;
      
      $title              = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '');
      $sub_title          = isset( $instance['sub_title'] ) ? $instance['sub_title'] : '';
      $button_text        = isset( $instance['button_text'] ) ? $instance['button_text'] : '';
      $button_url         = isset( $instance['button_url'] ) ? $instance['button_url'] : '';
      $background_color   = isset( $instance['background_color'] ) ? $instance['background_color'] : null;

      $get_featured_pages = new WP_Query( array(
        'no_found_rows'   => true,
        'post_status'     => 'publish',
        'posts_per_page'  => intval( 5 ),
        'post_type'       =>  array( 'page' ),
        'orderby'         => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
        'meta_query' => array(
            array(
              'key' => '_wp_page_template',
              'value' => 'page-templates/template-rooms.php'
            )
          )
      ) );

      $countPosts = intval( $get_featured_pages->post_count );

      $inline_style = '';
        
      if ( $background_color != '') {
        $inline_style = ' style="background-color:' . esc_attr($background_color) . '"';
      }

      echo $args['before_widget'] = str_replace('<section', '<section' .$inline_style , $args['before_widget']); ?>

      <div class="pt-rooms-sec">
        <div class="container">
          <div class="row">
              <div class="col-md-12">
                  <header>
                    <?php if ( !empty( $title ) ) : ?>

                      <h2 class="widget-title"><?php echo esc_html( $title ); ?></h2>

                    <?php endif; ?>

                    <?php if ( !empty( $sub_title ) ) : ?>
                      <h4><?php echo wp_kses_post( $sub_title ); ?></h4>
                    <?php endif; ?>
                    
                  </header>
              </div><!-- .col-md-12 -->

              <div class="col-md-12">
                  <div class="swiper-container pt-rooms-slider">
                      <div class="swiper-wrapper">
                          <?php if ( $get_featured_pages->have_posts() ) : ?>

                              <?php while( $get_featured_pages->have_posts() ) : $get_featured_pages->the_post();
                                  $image_id     = get_post_thumbnail_id();
                                  $image_path   = wp_get_attachment_image_src( $image_id, 'courtyard-400x260', true );
                                  $image_alt    = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                                  $alt          = !empty( $image_alt ) ? $image_alt : the_title_attribute( 'echo=0' ) ;
                                  ?>

                                  <div class="swiper-slide">
                                      <div class="pt-room-col">

                                          <?php if( has_post_thumbnail() ) : ?>
                                              <figure>
                                                  <a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">
                                                    <img src="<?php echo esc_url( $image_path[0] ); ?>" alt="<?php echo esc_attr( $alt ); ?>" title="<?php the_title_attribute(); ?>" />
                                                  </a>
                                              </figure>
                                          <?php endif; ?>

                                          <div class="pt-room-cont transition35">
                                              <a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><i class="pt-arrow-right transition5"></i></a>
                                              <h3><a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                                              <p><?php echo wp_trim_words( get_the_excerpt(), 22, '' ); ?></p>
                                          </div><!-- .pt-room-cont -->
                                      </div><!-- .pt-room-col -->
                                  </div><!-- .swiper-slide -->

                              <?php endwhile;
                              // Reset Post Data
                              wp_reset_postdata(); ?>

                          <?php endif; ?>
                      </div><!-- .swiper-wrapper -->

                      <?php if ( !empty( $button_text ) && $countPosts >= 3 ) : ?>

                          <div class="pt-rooms-more">
                              <div class="pt-rooms-more-holder">
                                  <i class="pt-arrow-left transition35"></i>
                                  <a href="<?php echo esc_url( $button_url ); ?>" class="transition35"><?php echo esc_html( $button_text ); ?></a>
                                  <i class="pt-arrow-right transition35"></i>
                              </div><!-- .pt-rooms-more-holder -->
                          </div><!-- .pt-services-more -->

                      <?php endif; ?>
                  </div><!-- .swiper-container -->
              </div><!-- .col-md-12 -->
          </div><!-- .row -->
        </div><!-- .container -->
      </div><!-- .pt-room-sec -->

      <?php echo $args['after_widget'];
      ob_end_flush();
    }
}