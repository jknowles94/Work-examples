<?php
function google_maps_meta_box_markup($object) {

  wp_enqueue_style('jvectormap_css', get_template_directory_uri() . "/bower_components/jquery-jvectormap-2.0.3/jquery-jvectormap-2.0.3.css");

  wp_enqueue_script('jvectormap_js', get_template_directory_uri() . "/bower_components/jquery-jvectormap-2.0.3/jquery-jvectormap-2.0.3.min.js");

  wp_enqueue_script('region_map_balls', get_template_directory_uri() . "/assets/scripts/region_map_balls.js");

  wp_enqueue_script('google_maps', "https://maps.googleapis.com/maps/api/js?key=AIzaSyB-sxaQgwkxbqccr07057osEs3Xi0sCWeE");

  wp_enqueue_script('club_admin', get_template_directory_uri() . "/assets/scripts/club_admin.js");

  add_thickbox();

  ?>
    <style>
      svg{
        width:  100%;
        height: 100%;
      }
      .map{
        width:    90%;
        height:   90%;
      }
    </style>

  <a href="#TB_inline?width=&height=550&inlineId=google-maps-modal" class="thickbox button" id="google-map-link" id="show-modal">Google Maps</a>
  <a href="#TB_inline?width=&height=550&inlineId=woodenspoon-maps-modal" class="thickbox button" id="woodenspoon-map-link">Woodenspoon Maps</a>

  <div id="google-maps-modal" style="display:none;">
    <div class="map" id="google-maps"></div>
    <a class="button" href="javascript:void(0);" id="load-map">Load Map</a>
    <a class="button" href="javascript:void(0);" id="geocode">Geolocalize</a>
  </div>
  <div id="woodenspoon-maps-modal" style="display:none;">
    <div class="map" id="woodenspoon-maps"></div>
  </div>

  <?php
}

function add_google_maps_meta_box() {
  add_meta_box(
    "google-maps-box",
    "Google Maps",
    "google_maps_meta_box_markup",
    "club",
    "side",
    "high",
    null
  );
}

add_action(
  "add_meta_boxes",
  "add_google_maps_meta_box"
);
