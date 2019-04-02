<?php 
/*
 Plugin Name: Tabib Hospital Pro Posttype
 Plugin URI: https://www.themeseye.com/
 Description: Creating new post type for Tabib Hospital Pro Theme
 Author: Themeseye
 Version: 1.0
 Author URI: https://www.themeseye.com/
*/

define( 'tabib_hospital_pro_posttype_version', '1.0' );
add_action( 'init', 'tabib_hospital_pro_posttype_create_post_type' );
add_action( 'init', 'createcategory');

function tabib_hospital_pro_posttype_create_post_type() {
  register_post_type( 'services',
    array(
      'labels' => array(
        'name' => __( 'What We Do','tabib-hospital-pro-posttype' ),
        'singular_name' => __( 'What We Do','tabib-hospital-pro-posttype' )
      ),
      'capability_type' => 'post',
      'menu_icon'  => 'dashicons-portfolio',
      'public' => true,
      'supports' => array(
        'title',
        'editor',
        'thumbnail'
      )
    )
  );
  register_post_type( 'team',
    array(
        'labels' => array(
            'name' => __( 'Team','tabib-hospital-pro-posttype' ),
            'singular_name' => __( 'Team','tabib-hospital-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-welcome-learn-more',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  register_post_type( 'testimonials',
	array(
		'labels' => array(
			'name' => __( 'Testimonials','tabib-hospital-pro-posttype-pro' ),
			'singular_name' => __( 'Testimonials','tabib-hospital-pro-posttype-pro' )
			),
		'capability_type' => 'post',
		'menu_icon'  => 'dashicons-businessman',
		'public' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail'
			)
		)
	);
  
}
function createcategory() {
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => __( 'Categories', 'tameer-construction-pro' ),
    'singular_name'     => __( 'Categories', 'tameer-construction-pro' ),
    'search_items'      => __( 'Search cats', 'tameer-construction-pro' ),
    'all_items'         => __( 'All Categories', 'tameer-construction-pro' ),
    'parent_item'       => __( 'Parent Categories', 'tameer-construction-pro' ),
    'parent_item_colon' => __( 'Parent Categories:', 'tameer-construction-pro' ),
    'edit_item'         => __( 'Edit Categories', 'tameer-construction-pro' ),
    'update_item'       => __( 'Update Categories', 'tameer-construction-pro' ),
    'add_new_item'      => __( 'Add New Categories', 'tameer-construction-pro' ),
    'new_item_name'     => __( 'New Categories Name', 'tameer-construction-pro' ),
    'menu_name'         => __( 'Categories', 'tameer-construction-pro' ),
  );
  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'createcategory' ),
  );
  register_taxonomy( 'createcategory', array( 'services' ), $args );
}
/*-------------------------Serives section------------------------*/
function tabib_hospital_pro_bn_services_meta_box(){
  add_meta_box('tabib_hospital_pro_posttype_services_meta',__('Enter Details','tabib_hospital_pro'),'tabib_hospital_pro_posttype_bn_services_meta_callback','services','normal','high');
}
// Hook things in for admin
if(is_admin()){
  add_action('admin_menu','tabib_hospital_pro_bn_services_meta_box');
}
/*----------------Adds a meta box for custom post-----------------*/
function tabib_hospital_pro_posttype_bn_services_meta_callback( $post ){
  wp_nonce_field( basename(__File__),'tabib_hospital_pro_posttype_services_meta_nonce');
    $bn_stored_meta = get_post_meta( $post->ID );
     if(!empty($bn_stored_meta['description'][0]))
      $bn_description = $bn_stored_meta['description'][0];
    else
      $bn_description = '';
    ?>
    <div id="testimonials_custom_stuff">
      <table id="list">
        <tbody id="the-list" data-wp-lists="list:meta">
          <tr id="meta-1">
            <td class="left">
              <?php _e( 'description', 'tameer-construction-pro-posttype' )?>
            </td>
            <td class="left" >
              <input type="text" name="description" id="description" 
              value="<?php echo esc_attr($bn_description); ?>" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  <?php
  }
  function tabib_hospital_pro_bn_services_save($post_id){
    if(!isset ($_POST['tabib_hospital_pro_posttype_services_meta_nonce']) || !wp_verify_nonce($_POST['tabib_hospital_pro_posttype_services_meta_nonce'],basename(__FILE__))){
      return;
  }
   if (!current_user_can('edit_post',$post_id)) {
    return;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
    return;
  }
  //save description name
  if( isset( $_POST[ 'description' ] ) ) {
    update_post_meta( $post_id, 'description', sanitize_text_field($_POST[ 'description']) );
  }
}
add_action( 'save_post', 'tabib_hospital_pro_bn_services_save' );

/* Services shortcode */
function tabib_hospital_pro_posttype_services_func( $atts ) {
  $services = '';
  $services = '<div class="row">';
  $query = new WP_Query( array( 'post_type' => 'services') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=services');

  while ($new->have_posts()) : $new->the_post();
        $custom_url ='';
        $post_id = get_the_ID();
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $services_image= get_post_meta(get_the_ID(), 'meta-image', true);
        if(get_post_meta($post_id,'meta-services-url',true !='')){$custom_url =get_post_meta($post_id,'meta-services-url',true); } else{ $custom_url = get_permalink(); }
        $services .= '<div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="services-box">
                          <div class="service-img">
                             <div class="services_icon">
                             <img class="" src="'.esc_url($services_image).'">
                              <img class="client-img" src="'.esc_url($thumb_url).'" alt="team-thumbnail" />
                          </div>
                        </div>
                      <div class="service-content">
                        <h4><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h4>
                        <p>
                          '.$excerpt.'
                        </p>
                    </div>
                  </div>
                </div>';


    if($k%2 == 0){
      $services.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $services = '<h2 class="center">'.esc_html__('Post Not Found','tabib-hospital-pro-posttype').'</h2>';
  endif;
  $services .= '</div>';
  return $services;
}

add_shortcode( 'te-services', 'tabib_hospital_pro_posttype_services_func' );

/* ----------------- Team ---------------- */
function tabib_hospital_pro_posttype_bn_designation_meta() {
    add_meta_box( 'tabib_hospital_pro_posttype_bn_meta', __( 'Enter Designation','tabib-hospital-pro-posttype' ), 'tabib_hospital_pro_posttype_bn_meta_callback', 'team', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'tabib_hospital_pro_posttype_bn_designation_meta');
}
/* Adds a meta box for custom post */
function tabib_hospital_pro_posttype_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'tabib_hospital_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $meta_designation = get_post_meta( $post->ID, 'meta-designation', true );
    $meta_team_email = get_post_meta( $post->ID, 'meta-team-email', true );
    $meta_team_call = get_post_meta( $post->ID, 'meta-team-call', true );
    $meta_team_time = get_post_meta( $post->ID, 'meta-team-time', true );
    $meta_team_face = get_post_meta( $post->ID, 'meta-facebookurl', true );
    $meta_team_twit = get_post_meta( $post->ID, 'meta-twitterurl', true );
    $meta_team_gplus = get_post_meta( $post->ID, 'meta-googleplusurl', true );
    $meta_team_pint = get_post_meta( $post->ID, 'meta-pinteresturl', true );
    $meta_team_inst = get_post_meta( $post->ID, 'meta-instagramurl', true );
    ?>
    <div id="team_custom_stuff">
        <table id="list-table">         
          <tbody id="the-list" data-wp-lists="list:meta">
              <tr id="meta-9">
                <td class="left">
                  <?php esc_html_e( 'Designation', 'tabib-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_attr($meta_designation); ?>" />
                </td>
              </tr>
              <tr id="meta-9">
                <td class="left">
                  <?php esc_html_e( 'Email', 'tabib-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="text" name="meta-team-email" id="meta-team-email" value="<?php echo esc_attr($meta_team_email); ?>" />
                </td>
              </tr>
              <tr id="meta-9">
                <td class="left">
                  <?php esc_html_e( 'Phone', 'tabib-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="text" name="meta-team-call" id="meta-team-call" value="<?php echo esc_attr($meta_team_call); ?>" />
                </td>
              </tr>
              <tr id="meta-9">
                <td class="left">
                  <?php esc_html_e( 'Time', 'tabib-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="text" name="meta-team-time" id="meta-team-time" value="<?php echo esc_attr($meta_team_time); ?>" />
                </td>
              </tr>
              <tr id="meta-3">
                <td class="left">
                  <?php esc_html_e( 'Facebook Url', 'tabib-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-facebookurl" id="meta-facebookurl" value="<?php echo esc_attr($meta_team_face); ?>" />
                </td>
              </tr>
              <tr id="meta-5">
                <td class="left">
                  <?php esc_html_e( 'Twitter Url', 'tabib-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-twitterurl" id="meta-twitterurl" value="<?php echo esc_attr($meta_team_face); ?>" />
                </td>
              </tr>
              <tr id="meta-6">
                <td class="left">
                  <?php esc_html_e( 'GooglePlus URL', 'tabib-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-googleplusurl" id="meta-googleplusurl" value="<?php echo esc_attr($meta_team_gplus); ?>" />
                </td>
              </tr>
              <tr id="meta-7">
                <td class="left">
                  <?php esc_html_e( 'Pinterest URL', 'tabib-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-pinteresturl" id="meta-pinteresturl" value="<?php echo esc_attr($meta_team_pint); ?>" />
                </td>
              </tr>
               <tr id="meta-8">
                <td class="left">
                  <?php esc_html_e( 'Instagram URL', 'tabib-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-instagramurl" id="meta-instagramurl" value="<?php echo esc_attr($meta_team_inst); ?>" />
                </td>
              </tr>
              
          </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom fields meta input */
function tabib_hospital_pro_posttype_bn_metadesig_team_save( $post_id ) {
    if( isset( $_POST[ 'meta-desig' ] ) ) {
        update_post_meta( $post_id, 'meta-desig', sanitize_text_field($_POST[ 'meta-desig' ]) );
    }
    if( isset( $_POST[ 'meta-call' ] ) ) {
        update_post_meta( $post_id, 'meta-call', sanitize_text_field($_POST[ 'meta-call' ]) );
    }
    // Save facebookurl
    if( isset( $_POST[ 'meta-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-facebookurl', esc_url_raw($_POST[ 'meta-facebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-linkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-linkdenurl', esc_url_raw($_POST[ 'meta-linkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-twitterurl', esc_url_raw($_POST[ 'meta-twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-googleplusurl', esc_url_raw($_POST[ 'meta-googleplusurl' ]) );
    }

    // Save Pinterest
    if( isset( $_POST[ 'meta-pinteresturl' ] ) ) {
        update_post_meta( $post_id, 'meta-pinteresturl', esc_url_raw($_POST[ 'meta-pinteresturl' ]) );
    }

     // Save Instagram
    if( isset( $_POST[ 'meta-instagramurl' ] ) ) {
        update_post_meta( $post_id, 'meta-instagramurl', esc_url_raw($_POST[ 'meta-instagramurl' ]) );
    }
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', sanitize_text_field($_POST[ 'meta-designation' ]) );
    }

    // Save Email
    if( isset( $_POST[ 'meta-team-email' ] ) ) {
        update_post_meta( $post_id, 'meta-team-email', sanitize_text_field($_POST[ 'meta-team-email' ]) );
    }
    // Save Call
    if( isset( $_POST[ 'meta-team-call' ] ) ) {
        update_post_meta( $post_id, 'meta-team-call', sanitize_text_field($_POST[ 'meta-team-call' ]) );
    }
    // Save time
    if( isset( $_POST[ 'meta-team-time' ] ) ) {
        update_post_meta( $post_id, 'meta-team-time', sanitize_text_field($_POST[ 'meta-team-time' ]) );
    }
}
add_action( 'save_post', 'tabib_hospital_pro_posttype_bn_metadesig_team_save' );

/* team shorthcode */
function tabib_hospital_pro_posttype_team_func( $atts ) {
    $team = ''; 
    $custom_url ='';
    $team = '<div class="row">';
    $query = new WP_Query( array( 'post_type' => 'team' ) );
    if ( $query->have_posts() ) :
    $k=1;
    $new = new WP_Query('post_type=team'); 
    while ($new->have_posts()) : $new->the_post();
    	$post_id = get_the_ID();
    	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
      if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
		  $url = $thumb['0'];
      $excerpt = wp_trim_words(get_the_excerpt(),25);
      $designation= get_post_meta($post_id,'meta-designation',true);
      $facebookurl= get_post_meta($post_id,'meta-facebookurl',true);
      $linkedin=get_post_meta($post_id,'meta-linkdenurl',true);
      $twitter=get_post_meta($post_id,'meta-twitterurl',true);
      $googleplus=get_post_meta($post_id,'meta-googleplusurl',true);
      $youtube=get_post_meta($post_id,'meta-youtubeurl',true);
      $pinterest=get_post_meta($post_id,'meta-pinteresturl',true);
      $instagram=get_post_meta($post_id,'meta-instagramurl',true);
      $team .= '<div class="team_box col-lg-3 col-md-6 col-sm-6">
                    <div class="image-box ">
                      <div class="box team_img">
                        <img class="client-img" src="'.esc_url($thumb_url).'" alt="team-thumbnail" />
                      </div>
                    </div>
                  <div class="content_box w-100 float-left">
                    <div class="box-content team-box">
                      <h4 class="team_name"><a href="'.get_permalink().'">'.get_the_title().'</a></h4>
                      <p class="designation">'.esc_html($designation).'</p>
                    </div>
                     <div class="social-icon">';
                          if($facebookurl != ''){
                            $team .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                          } if($twitter != ''){
                            $team .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                          } if($googleplus != ''){
                            $team .= '<a class="" href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                          } if($youtube != ''){
                            $team .= '<a class="" href="'.esc_url($youtube).'" target="_blank"><i class="fab fa-youtube"></i></a>';
                          }if($pinterest != ''){
                            $team .= '<a class="" href="'.esc_url($pinterest).'" target="_blank"><i class="fab fa-pinterest-p"></i></a>';
                          }if($instagram != ''){
                            $team .= '<a class="" href="'.esc_url($instagram).'" target="_blank"><i class="fab fa-instagram"></i></a>';
                          }
                        $team .= '</div>
                    
                  </div>
                </div>';

      if($k%2 == 0){
          $team.= '<div class="clearfix"></div>'; 
      } 
      $k++;         
  endwhile; 
  wp_reset_postdata();
  $team.= '</div>';
  else :
    $team = '<h2 class="center">'.esc_html_e('Not Found','tabib-hospital-pro-posttype').'</h2>';
  endif;
  return $team;
}
add_shortcode( 'tabib-hospital-pro-team', 'tabib_hospital_pro_posttype_team_func' );

/* customer section */
/* Adds a meta box to the customer editing screen */
function tabib_hospital_pro_posttype_bn_customer_meta_box() {
	add_meta_box( 'tabib-hospital-pro-posttype-pro-customer-meta', __( 'Enter Designation', 'tabib-hospital-pro-posttype-pro' ), 'tabib_hospital_pro_posttype_bn_customer_meta_callback', 'testimonials', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'tabib_hospital_pro_posttype_bn_customer_meta_box');
}

/* Adds a meta box for custom post */
function tabib_hospital_pro_posttype_bn_customer_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'tabib_hospital_pro_posttype_posttype_customer_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
	$desigstory = get_post_meta( $post->ID, 'customer-desig', true );
  $tes_facebook = get_post_meta( $post->ID, 'meta-tes-facebookurl', true );
  $tes_twitter = get_post_meta( $post->ID, 'meta-tes-twitterurl', true );
  $tes_gplus = get_post_meta( $post->ID, 'meta-tes-googleplusurl', true );
  $test_pinterest = get_post_meta( $post->ID, 'meta-tes-pinteresturl', true );
  $tes_instagram = get_post_meta( $post->ID, 'meta-tes-instagramurl', true );
	?>
	<div id="testimonials_custom_stuff">
		<table id="list">
			<tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-9">
          <td class="left">
            <?php esc_html_e( 'Designation', 'tabib-hospital-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="customer-desig" id="customer-desig" value="<?php echo esc_attr($desigstory); ?>" />
          </td>
        </tr>
        <tr id="meta-3">
          <td class="left">
            <?php esc_html_e( 'Facebook Url', 'tabib-hospital-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-facebookurl" id="meta-tes-facebookurl" value="<?php echo esc_attr($tes_facebook); ?>" />
          </td>
        </tr>
        <tr id="meta-5">
          <td class="left">
            <?php esc_html_e( 'Twitter Url', 'tabib-hospital-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-twitterurl" id="meta-tes-twitterurl" value="<?php echo esc_attr($tes_twitter); ?>" />
          </td>
        </tr>
        <tr id="meta-6">
          <td class="left">
            <?php esc_html_e( 'GooglePlus URL', 'tabib-hospital-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-googleplusurl" id="meta-tes-googleplusurl" value="<?php echo esc_attr($tes_gplus); ?>" />
          </td>
        </tr>
        <tr id="meta-7">
          <td class="left">
            <?php esc_html_e( 'Pinterest URL', 'tabib-hospital-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-pinteresturl" id="meta-tes-pinteresturl" value="<?php echo esc_attr($test_pinterest); ?>" />
          </td>
        </tr>
        <tr id="meta-8">
          <td class="left">
            <?php esc_html_e( 'Instagram URL', 'tabib-hospital-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-instagramurl" id="meta-tes-instagramurl" value="<?php echo esc_attr($tes_instagram); ?>" />
          </td>
        </tr>
      </tbody>
		</table>
	</div>
	<?php
}

/* Saves the custom meta input */
function tabib_hospital_pro_posttype_bn_metadesig_save( $post_id ) {
	if (!isset($_POST['tabib_hospital_pro_posttype_posttype_customer_meta_nonce']) || !wp_verify_nonce($_POST['tabib_hospital_pro_posttype_posttype_customer_meta_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Save desig.
	if( isset( $_POST[ 'tabib_hospital_pro_posttype_posttype_customer_desigstory' ] ) ) {
		update_post_meta( $post_id, 'tabib_hospital_pro_posttype_posttype_customer_desigstory', sanitize_text_field($_POST[ 'tabib_hospital_pro_posttype_posttype_customer_desigstory']) );
	}
  
  // Course Name
  if( isset( $_POST[ 'customer-desig' ] ) ) {
    update_post_meta( $post_id, 'customer-desig', sanitize_text_field($_POST[ 'customer-desig' ]) );
  } 

  // Save facebookurl
    if( isset( $_POST[ 'meta-tes-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tes-facebookurl', esc_url_raw($_POST[ 'meta-tes-facebookurl' ]) );
    }
    
    if( isset( $_POST[ 'meta-tes-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tes-twitterurl', esc_url_raw($_POST[ 'meta-tes-twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-tes-googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tes-googleplusurl', esc_url_raw($_POST[ 'meta-tes-googleplusurl' ]) );
    }

    // Save Pinterest
    if( isset( $_POST[ 'meta-tes-pinteresturl' ] ) ) {
        update_post_meta( $post_id, 'meta-tes-pinteresturl', esc_url_raw($_POST[ 'meta-tes-pinteresturl' ]) );
    }

     // Save Instagram
    if( isset( $_POST[ 'meta-tes-instagramurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tes-instagramurl', esc_url_raw($_POST[ 'meta-tes-instagramurl' ]) );
    }

}

add_action( 'save_post', 'tabib_hospital_pro_posttype_bn_metadesig_save' );

/* testimonials shortcode */
function tabib_hospital_pro_posttype_customer_func( $atts ) {
	$customer = '';
	$customer = '<div class="row">';
	$query = new WP_Query( array( 'post_type' => 'testimonials') );

    if ( $query->have_posts() ) :

	$k=1;
	$new = new WP_Query('post_type=testimonials');

	while ($new->have_posts()) : $new->the_post();
        $custom_url = '';
      	$post_id = get_the_ID();
      	$excerpt = wp_trim_words(get_the_excerpt(),25);
        $course= get_post_meta($post_id,'customer-desig',true);

        $tfacebookurl= get_post_meta($post_id,'meta-tes-facebookurl',true);
        $tlinkedin=get_post_meta($post_id,'meta-linkdenurl',true);
        $ttwitter=get_post_meta($post_id,'meta-tes-twitterurl',true);
        $tgoogleplus=get_post_meta($post_id,'meta-tes-googleplusurl',true);
        $tpinterest=get_post_meta($post_id,'meta-tes-pinteresturl',true);
        $tinstagram=get_post_meta($post_id,'meta-tes-instagramurl',true);

      	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
		    if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $customer .= '
          <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="short_text"><p>'.$excerpt.'</p></div>
            <div class="customer_box mb-3">
              <div class="image-box">
                <div class="customer-overlay">
                  <img class="testi-img" src="'.esc_url($thumb_url).'" />
                    <h4 class="customer_name post"><a href="'.get_permalink().'">'.esc_html(get_the_title()) .'</a></h4>
                    <p class="desig-name"> '.esc_html($course).'</p>
                </div>
              </div>
            </div>
          </div>';
		if($k%3 == 0){
			$customer.= '<div class="clearfix"></div>';
		}
      $k++;
  endwhile;
  else :
  	$customer = '<h2 class="center">'.esc_html__('Post Not Found','tabib-hospital-pro-posttype-pro').'</h2>';
  endif;
  $customer .= '</div>';
  return $customer;
}
add_shortcode( 'tabib-hospital-pro-testimonials', 'tabib_hospital_pro_posttype_customer_func' );





