<?php

class RCLP_Category_Latest_Post_Redirect {
  public static function init() {
    add_action( 'parse_request', array( __CLASS__, '_url_redirect' ) );
    add_filter( 'wp_get_nav_menu_items', array( __CLASS__, '_navbar_redirect' ), 11, 3 );
  }


  // URL query redirect
  public function _url_redirect( $request ) {
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


  // Update menu link
  public function _navbar_redirect( $items, $menu, $args ) {
    foreach( $items as $item ) {
        $item->redirect_latest_post == true && $item->object == 'category' && $item->url .= '?latest';
    }

    return $items;
  }
}


RCLP_Category_Latest_Post_Redirect::init();

?>