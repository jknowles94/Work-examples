<?php
function custom_meta_box_markup($object) {

  wp_enqueue_style('jvectormap_css', get_template_directory_uri() . "/bower_components/jquery-jvectormap-2.0.3/jquery-jvectormap-2.0.3.css");

  wp_enqueue_script('jvectormap_js', get_template_directory_uri() . "/bower_components/jquery-jvectormap-2.0.3/jquery-jvectormap-2.0.3.min.js");

  wp_enqueue_script('region_map', get_template_directory_uri() . "/assets/scripts/region_map.js");

  wp_enqueue_script('admin_map', get_template_directory_uri() . "/assets/scripts/admin_map.js");

  add_thickbox();

  ?>

<a href="#TB_inline?width=&height=550&inlineId=modal-window-id" class="thickbox">Map Region</a>


<div id="modal-window-id" style="display:none;">
<style>
  svg{
    width:90%;
    height:100%;
  }
  .region.hover{
    color: white;
    background-color: #D51E49;
  }
  #uk-map{
    width: 90%;
    height: 98%;
    display: inline-block;
  }
  #regions{
    width: 10%;
    height: 98%;
    display: inline-block;
    overflow-y: scroll;
  }
</style>
<div id="uk-map"></div>
<div id="regions"></div>
</div>

<?php
}

function add_custom_meta_box() {
  add_meta_box(
    "demo-meta-box",
    "Map Region",
    "custom_meta_box_markup",
    "region",
    "side",
    "high",
    null
  );
}

add_action(
  "add_meta_boxes",
  "add_custom_meta_box"
);
add_action(
  "save_post",
  "save_custom_meta_box",
  10,
  3
);
