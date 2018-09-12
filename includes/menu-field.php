<?php

class Menu_Item_Category_Latest_Post {
  protected static $field = 'redirect_latest_post';

  // Initialize plugin
  public static function init() {
    add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_fields' ), 10, 4 );
    add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );
    add_action( 'admin_footer', array( __CLASS__, '_remove_fields' ) );
    add_filter( 'wp_setup_nav_menu_item', array( __CLASS__, '_merge' ) );
  }


  // Print fields
  public static function _fields( $id, $item, $depth, $args ) {
    $key   = self::$field;
    $id = sprintf( 'edit-menu-item-latest-post-%s', $item->ID );
    $name  = sprintf( '%s[%s]', $key, $item->ID );
    $value = get_post_meta( $item->ID, $key, true );
    $checked = checked( $value==true, true, false );
    ?>
    <p class="field-latest-post description description-wide">
      <?php printf(
        '<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="true" %3$s />Redirect to the latest post</label>',
        esc_attr( $id ),
        esc_attr( $name ),
        esc_attr( $checked )
      ) ?>
    </p>
    <?php
  }


  // Remove fields
  public static function _remove_fields() {
    global $pagenow;
    if ($pagenow == "nav-menus.php"){
    ?>
      <script>
        jQuery( document ).ready( function() {
          jQuery( 'li.menu-item:not(.menu-item-category) .field-latest-post' ).remove();

          jQuery( '#menu-settings-column .button.submit-add-to-menu' ).click(function() {
            setTimeout( function() {
              jQuery( 'li.menu-item:not(.menu-item-category) .field-latest-post' ).remove();
            }, 2000 );
          } );
        } );
      </script>
    <?php
    }
  }


  // Save custom field value
  public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
      return;
    }

    check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

    $key = self::$field;

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


  // Merge data into $item
  public static function _merge( $item ) {
    $key = self::$field;
    $value = get_post_meta( $item->ID, self::$field, true );
    $item->$key = $value;

    return $item;
  }

}


Menu_Item_Category_Latest_Post::init();

?>