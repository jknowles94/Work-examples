var initializeGoogleMap = function() {

  var myLatlng = new google.maps.LatLng(57.7242969, -4.6957701);

  var mapOptions = {
    zoom: 6,
    center: myLatlng
  };

  window.googleMap = new google.maps.Map(document.getElementById('google-maps'), mapOptions);
  if (jQuery("[data-name='latitude'] input").val() && jQuery("[data-name='longitude'] input").val()) {
    setTimeout((function() {
      add_marker(
        jQuery("[data-name='latitude'] input").val(),
        jQuery("[data-name='longitude'] input").val()
      );
    }), 4000);
  }
};

var initializeWoodenspoonMap = function() {
  console.log(window.map);
  window.map = new jvm.Map({
    container: jQuery("#woodenspoon-maps"),
    regionsSelectable: true,
    selectedRegions: jQuery("[data-name='mapregion'] input").val(),
    regionsSelectableOne: true,
    map: "region_map_balls",
    focusOn: { x: 0, y: 0, scale: 100 },
    onRegionSelected: function(e, code, isSelected, selectedRegions) {
      jQuery("[data-name='mapregion'] input").val(code);
    }
  });
};

var add_marker = function(lat, lng) {
  var myLatlng = new google.maps.LatLng(lat, lng);
  if (window.marker != null) {
    window.marker.setPosition(myLatlng);
  } else {
    window.marker = new google.maps.Marker({
      position: myLatlng,
      map: window.googleMap,
      draggable: true,
      title: "Drag me!"
    });
    google.maps.event.addListener(window.marker, 'dragend', function(event) {
      jQuery("[data-name='latitude'] input").val(event.latLng.lat());
      jQuery("[data-name='longitude'] input").val(event.latLng.lng());
    });
  }
  window.googleMap.setCenter(myLatlng);
  window.googleMap.setZoom(12);
};


jQuery(document).ready(function() {
  initializeWoodenspoonMap();
  // initializeGoogleMap();

  jQuery("#load-map").click(function() {
    initializeGoogleMap();
  });
  jQuery("#geocode").click(function() {
    var address = [
      jQuery("[data-name='clubname'] input").val(), [
        jQuery("[data-name='line1'] input").val(),
        jQuery("[data-name='line2'] input").val(),
        jQuery("[data-name='line3'] input").val()
      ].join(" "),
      jQuery("[data-name='town'] input").val(),
      jQuery("[data-name='postcode'] input").val()
    ].join(", ");

    jQuery.ajax({
      dataType: "json",
      url: 'https://maps.googleapis.com/maps/api/geocode/json',
      data: {
        sensor: false,
        address: address
      },
      success: function(data, textStatus) {
        var lat, lng;
        lat = data.results[0].geometry.location.lat;
        lng = data.results[0].geometry.location.lng;
        jQuery("[data-name='latitude'] input").val(lat);
        jQuery("[data-name='longitude'] input").val(lng);
        add_marker(lat, lng)
      }
    });
  });
});
