<?php
/*
Plugin Name: Redirect to Category Latest Post
Plugin URI: https://github.com/emma2334/wp-redirect-to-category-latest-post
Description: Redirect category menu item to the latest post in it.
Version: 0.2.2
Author: Emma Chung
Author URI: http://emma2334.github.io
License: GPLv2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/



class RCLP_Redirect_To_Category_Latest_Post {
  private static $instance;

  public static function instance() {
    if ( !self::$instance ) {
      self::$instance = new static;

      self::$instance->includes();
    }

    return self::$instance;
  }


  private function includes() {
    require_once( dirname( __FILE__ ).'/includes/redirect.php' );

    if(is_admin()) {
      require_once( dirname( __FILE__ ).'/includes/menu-field.php' );
    }
  }

}

RCLP_Redirect_To_Category_Latest_Post::instance();

?>