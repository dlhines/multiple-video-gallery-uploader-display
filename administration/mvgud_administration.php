<?php

class MVGUD_administration {

  public function __construct() {
    add_action('admin_menu',
      array (
        $this ,
        'MVGUD_administration_admin_add_menu'
      ));

    add_action( 'wp_ajax_mvgud_set_post_types',
      array(
        $this,
        'MVGUD_administration_set_post_types'
      ));
  }

  /**
  * MVGUD_administration_admin_add_menu
  * Build administration page and menu
  */
  public function MVGUD_administration_admin_add_menu() {
    // Administration Page creation
    $hook = add_menu_page(
      'Multiple Video Gallery Uploader Display',
      'Multiple Video Gallery Uploader Display',
      'manage_options',
      'multiple-video-gallery-uploader-display-administration',
      array(
        $this,
        'MVGUD_administration_main'),
        ''
    );

    add_action( 'load-' . $hook , array( $this, 'MVGUD_administration_assets' ) );

  }

  /**
  * MVGUD_administration_main
  * Set post types that will access MVGUD
  */
  public function MVGUD_administration_main() {
    require_once ( MVGUD_PLUGIN_FOLDER . '/administration/templates/main.php' );
  }

  public function MVGUD_administration_assets() {
    wp_enqueue_style( 'multiple-video-gallery-uploader-display-administration', MVGUD_PLUGIN_DIR . 'administration/css/multiple-video-gallery-uploader-display-administration.css', array(), '0.0.0', 'all');
    wp_enqueue_script( 'multiple-video-gallery-uploader-display-administration', MVGUD_PLUGIN_DIR . 'administration/js/multiple-video-gallery-uploader-display-administration.js', array('jquery'), null, true);
    wp_localize_script( 'multiple-video-gallery-uploader-display-administration', 'mvgud_set_post_types',
      array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'ajax_nonce' => wp_create_nonce('MVGUD')
      )
    );
  }

  /**
  * MVGUD_administration_set_post_types
  * Set post types that will access MVGUD
  */
  public function MVGUD_administration_set_post_types() {

    check_ajax_referer( 'MVGUD', 'security' );
    $post_types = $_POST['post_types'];
    $post_types = implode(',', $post_types);

    $update = update_option('MVGUD_post_types', $post_types);

    if($update = 1) {
      if(!empty($_POST['post_types'])) :
        echo "\nYou have successfully updated the Content Types\non which MVGUD will be attached.";
      else :
        echo "You have cleared all Post Types.\n\nYou are no longer using MVGUD.";
      endif;
    } else {
      echo "Error: Updating Content Types not Succesfull. Contact Administrator.\n";
    };

    wp_die();
  }
}

$mvgud_administration = new MVGUD_administration();

?>
