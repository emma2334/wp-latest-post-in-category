<?php

class EC_Category_Latest_Post_Redirect {
  public static function init() {
    add_action( 'parse_request', array( __CLASS__, 'redirect' ) );
  }


  public function redirect($request) {
    if( isset( $_GET['latest'] ) && isset( $request->query_vars['category_name'] ) ){
      $latest = new WP_Query( array(
        'category_name' => $request->query_vars['category_name'],
        'posts_per_page' => 1
      ) );
      if( $latest->have_posts() ){
        wp_redirect( get_permalink( $latest->post->ID ) );
        exit;
      }
    }
  }
}


EC_Category_Latest_Post_Redirect::init();

?>