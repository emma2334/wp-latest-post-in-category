<?php
/*
Plugin Name: Latest Post in Category
Description: Redirect to the latest post in a category. (Depend on Menu Item Custom Fields by Dzikri Aziz)
Version: 0.1.0
Author: Emma Chung
Author URI: http://emma2334.github.io
License: GPLv2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

class Menu_Item_Category_Latest_Post {
  protected static $fields = array();

  // Initialize plugin
  public static function init() {
    add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_fields' ), 10, 4 );
    add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );

    self::$fields = array(
      'latest-post' => __( 'Redirect to the latest post', 'menu-item-custom-fields' ),
    );
  }


  // Save custom field value
  public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
      return;
    }

    check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

    foreach ( self::$fields as $_key => $label ) {
      $key = sprintf( 'menu-item-%s', $_key );

      // Sanitize
      if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
        // Do some checks here...
        $value = $_POST[ $key ][ $menu_item_db_id ];
      } else {
        $value = null;
      }

      // Update
      if ( ! is_null( $value ) ) {
        update_post_meta( $menu_item_db_id, $key, $value );
      } else {
        delete_post_meta( $menu_item_db_id, $key );
      }
    }
  }


  // Print field
  public static function _fields( $id, $item, $depth, $args ) {
    foreach ( self::$fields as $_key => $label ) :
      $key   = sprintf( 'menu-item-%s', $_key );
      $id    = sprintf( 'edit-%s-%s', $key, $item->ID );
      $name  = sprintf( '%s[%s]', $key, $item->ID );
      $value = get_post_meta( $item->ID, $key, true );
      $class = sprintf( 'field-%s', $_key );
      $checked = checked( $value==true, true, false );
      ?>
        <p class="description description-wide <?php echo esc_attr( $class ) ?>">
          <?php printf(
            '<input type="checkbox" id="%1$s" name="%3$s" value="true" %4$s /><label for="%1$s">%2$s</label>',
            esc_attr( $id ),
            esc_html( $label ),
            esc_attr( $name ),
            esc_attr( $checked )
          ) ?>
        </p>
      <?php
    endforeach;
  }
}
Menu_Item_Category_Latest_Post::init();

?>