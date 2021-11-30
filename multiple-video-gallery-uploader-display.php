<?php
/**
* Plugin Name: Multiple Video Gallery Uploader Display (MVGUD)
* Description: Video SRC uploader for video galleries (per post type).
* Author: Daniel Hines (dlhines.net)
**/
define('MVGUD_PLUGIN_FOLDER', dirname(__FILE__) );
define('MVGUD_PLUGIN_BASE_FILENAME', plugin_basename(__FILE__));

// Grab plugin directory name from the plugin main file name
define('MVGUD_PLUGIN_DIR', plugins_url() . "/" . str_replace(".php","", substr(MVGUD_PLUGIN_BASE_FILENAME, strpos(MVGUD_PLUGIN_BASE_FILENAME, "/") + 1)) . "/");

class MVGUD_initiate {

    public function __construct() {

      // Include Administration Page
      require ( MVGUD_PLUGIN_FOLDER . '/administration/mvgud_administration.php' );

      // Include IUD post_type_display and render frontend
      require ( MVGUD_PLUGIN_FOLDER . '/mvgud/mvgud.php' );

    }

}

$initiate = new MVGUD_initiate();
?>
