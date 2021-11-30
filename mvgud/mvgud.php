<?php

class MVGUD {

  public function __construct() {
    add_action( 'add_meta_boxes' , array ( $this, 'MVGUD_meta_box'));
    add_action( 'admin_enqueue_scripts' , array ( $this, 'MVGUD_scripts_styles') );
    add_action( 'wp_enqueue_scripts' , array ( $this, 'MVGUD_scripts_styles_render') );
    add_shortcode( 'mvgud_display' , array( $this, 'MVGUD_shortcode') );
    add_action( 'save_post',
      array (
        $this ,
        'MVGUD_save_post'
      ));
  }

  /**
  * MVGUD_meta_box
  * metabox callback
  *
  *
  */
  public function MVGUD_meta_box() {
    $pt_s = get_option('MVGUD_post_types');

    if(!empty($pt_s)) {
      $display = explode(',', get_option('MVGUD_post_types'));

      add_meta_box(
        'multiple-video-gallery-uploader-display', // id
        'Multiple Video Gallery Uploader Display (MVGUD)', // title
        array ( $this, 'MVGUD_cb'), // callback
        $display, // content-type
        'normal', // display
        'default' // priority
      );
    }
  }

  /**
  * MVGUD_scripts_styles
  * styles for post type administration
  *
  *
  */
  public function MVGUD_scripts_styles() {
    wp_enqueue_script( 'MVGUD', MVGUD_PLUGIN_DIR .  'mvgud/js/multiple-video-gallery-uploader-display.js', array('jquery'), null, true );
    wp_enqueue_style( 'multiple-video-gallery-uploader-display', MVGUD_PLUGIN_DIR .  'mvgud/css/multiple-video-gallery-uploader-display.css', array(), '0.0.0', 'all' );

    // Font Awesome
    if (wp_script_is( '556f7ce196.js' )) {
      return;
    } else {
      wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/556f7ce196.js', array(), '0.0.0', 'true');
      wp_script_add_data( 'font-awesome', array( 'crossorigin' ) , array( 'anonymous' ) );
    }
  }

  /**
  * MVGUD_scripts_styles_render
  * styles for frontend display
  *
  *
  */
  public function MVGUD_scripts_styles_render() {
    wp_enqueue_style('multiple-video-gallery-uploader-display-render', MVGUD_PLUGIN_DIR . 'css/multiple-video-gallery-uploader-display-render.css', array(), '0.0.0', 'all');
  }

  /**
  * MVGUD_cb
  * @param $post  post ID
  *
  *
  */
  public function MVGUD_cb( $post ) {
    $videos = get_post_meta( $post->ID, 'mvgud_post_video_set');

    wp_nonce_field('MVGUD_meta_box_nonce', 'MVGUD_nonce');
    ?>
    <div id="video-uploader-display">
      <div id="mvgud_instructions">
        <p class="header">MVGUD Instructions ( Show )</p>
        <section class="clearfix">
          <?php require "mvgud-instructions.php"; ?>
        </section>
      </div>
      <p>Copy/Paste into Content Window: <b style="font-size: 1.2rem;">[mvgud_display id="<?php echo $post->ID; ?>" title=""]</b></p>
      <div id="gallery_load" name="video-uploader-display">
        <?php
          if(count($videos)) {
            for($i = 0; $i < 3; $i++) {
              if(isset($videos[0][$i]))  {
                echo '<i class="fas fa-arrows-alt fa-2x"></i> <input class="video-input" name="vid[]" value="' . $videos[0][$i] . '"/><br />';
              } else {
                echo '<i class="fas fa-arrows-alt fa-2x"></i> <input class="video-input" name="vid[]" /><br />';
              }
            }
          } else {
            for($i = 0; $i <= 2; $i++) {
              echo '<i class="fas fa-arrows-alt fa-2x"></i> <input class="video-input" name="vid[]" /><br />';
            }
          }
        ?>
      </div>
    </div>
    <?php
  }

  /**
  * MVGUD_shortcode
  * @param  $atts shortcode attributes
  *
  *
  */
  public function MVGUD_shortcode ( $atts ) {
    $atts = shortcode_atts( array(
      'id' => '',
      'title' => '',
    ), $atts, 'migud_post_video_set' );

    $videos = get_post_meta( $atts['id'], 'mvgud_post_video_set');

    if($videos) {
      $output = "\n" . '<!--- Multiple Video Gallery Uploader (MAFGUD)---!>' . "\n";
      $output .= '<div id="mvgud-render">' . "\n";
      $output .= "\t" . '<h5 class="mvgud-title">' . $atts['title'] . "</h5>\n";

      foreach($videos[0] as $vids) :
        $output .= "\t" . '<span class="mvgud-span">' . "\n";
        $output .= "\t\t" . '<iframe src="' . $vids . '"';
        $output .= ' frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';         $output .= "\n";
        $output .= "\t" . "</span><br />\n";
      endforeach;

      $output .= "</div>";
      $output .= "\n" . '<!--- End (MVGUD)---!>' . "\n\n";
      return $output;
    } else {
      return;
    }
  }

  /**
  * MVGUD_save_post
  * @param  $postID retrieve post ID
  *
  *
  */
  public function MVGUD_save_post( $postID ) {
    $arr = $_POST['vid'];
    $var = [];

    if ( isset( $_POST['MVGUD_nonce']) && wp_verify_nonce( $_POST['MVGUD_nonce'], 'MVGUD_meta_box_nonce' )) {
      echo "Multiple Video Gallery Uploader Display Nonce does not verify";
      exit;
    } else {
    // Remove empty, null, or 0 entries
      foreach($arr as $a) :
          if($a !== "") {
            array_push($var, sanitize_text_field($a));
          }
      endforeach;
      if (count($var) == 0) {
        delete_post_meta( $postID, 'mvgud_post_video_set' );
      } else {
        // Save Array
        update_post_meta( $postID, 'mvgud_post_video_set', $var );
      }
    }
  }
}

$mvgud = new MVGUD();

?>
