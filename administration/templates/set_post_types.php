<!--
Image Uploader Display Set Post Types
*************************************
Set Post Types in which the Image Uploader Display will be used.
-->
<?php
// Query for public post types
$args = array(
  'public'  => 'true',
);

// Grab public post types
$post_types = get_post_types($args);

// Array of Post Types from comma delimited list from DB
$saved_post_types = explode(',', get_option('MVGUD_post_types'));
?>
<div class="admin-mvgud set_post_types clearfix">
  <div class="clearfix">
    <div id="label">
      Use Image Uploader Display on these <u>Post Types</u>:
      <p><small>* Hold down ctrl or cmd to select multiple types</small></p>
      <p><small>* To remove all Post Types double click the one remaining type</small></p>
      <p><small>* Removing content types does not delete the data from these types</small></p>
    </div>
    <div id="settings">
      <select id="MVGUD_post_types" name="MVGUD_post_types" multiple>
        <?php
            // Cycle Post Types for select element
            foreach($post_types as $items) :
              if(in_array($items, $saved_post_types)) :
                echo "<option value='{$items}' selected>{$items}</option>";
              else :
                echo "<option value='{$items}'>{$items}</option>";
              endif;
            endforeach;
        ?>
      </select>
    </div>
  </div>
  <div class="buttons">
    <input type="button" id="set_post_types" class="save" value="Save Post Types" />
  </div>
</div>
<!-- End  -->
