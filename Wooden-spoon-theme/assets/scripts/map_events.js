function rad(x) {
  return x * Math.PI / 180;
}

function isPostcodeValid(postcode) {
  var patt = new RegExp(/^(GIR 0AA)|((([ABCDEFGHIJKLMNOPRSTUWYZ][0-9][0-9]?)|(([ABCDEFGHIJKLMNOPRSTUWYZ][ABCDEFGHKLMNOPQRSTUVWXY][0-9][0-9]?)|(([ABCDEFGHIJKLMNOPRSTUWYZ][0-9][ABCDEFGHJKSTUW])|([ABCDEFGHIJKLMNOPRSTUWYZ][ABCDEFGHKLMNOPQRSTUVWXY][0-9][ABEHMNPRVWXY])))) [0-9][ABDEFGHJLNPQRSTUWXYZ]{2})$/i);
  var res = patt.test(postcode);
  return res;
}

function latLngPostcode(postcode) {
  jQuery.ajax({
    dataType: "json",
    url: "https://maps.googleapis.com/maps/api/geocode/json",
    data: {
      sensor: false,
      address: postcode
    },
    success: function(data, textStatus) {
      var lat, lng;
      lat = data.results[0].geometry.location.lat;
      lng = data.results[0].geometry.location.lng;
      find_closest_marker(lat, lng);
      if($('.club').hasClass('filterout')) {
        
        $('.club.filterout').each(function() {
          var regionid = $(this).attr('id');
          $('path[data-code="' + regionid + '"]').css("opacity", 0.5);
          console.log($('path[data-code="' + regionid + '"]'));
        });
        $('.club.filterin').each(function() {
          var regionid = $(this).attr('id');
          $('path[data-code="' + regionid + '"]').css("opacity", 1);
          console.log($('path[data-code="' + regionid + '"]'));
        });
      }
    }
  });
}

function find_closest_marker(lat, lng) {
  var R = 6371; // radius of earth in km
  var distances = [];
  var closest = -1;
  for (var club in clubs) {
    var mlat = clubs[club].latitude;
    var mlng = clubs[club].longitude;
    var dLat = rad(mlat - lat);
    var dLong = rad(mlng - lng);
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(rad(lat)) * Math.cos(rad(lat)) * Math.sin(dLong / 2) * Math.sin(dLong / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    distances[club] = d;
    if (closest == -1 || d < distances[closest]) {
      closest = club;
    }
  }
  map.clearSelectedRegions();
  jQuery(".filterin").removeClass("filterin").addClass("filterout");
  jQuery("#" + closest).addClass("filterin");
  jQuery("#" + closest).removeClass("filterout");
  map.setSelectedRegions(closest);
  

}

jQuery(document).ready(function() {
  window.map = new jvm.Map({
    container: jQuery("#woodenspoon-maps"),
    backgroundColor: "#c5edff",
    regionStyle: {
      initial: {
        fill: "#ADADAD",
        "fill-opacity": 1,
        stroke: "none",
        "stroke-width": 0,
        "stroke-opacity": 1
      },
      hover: {
        fill: null,
        "fill-opacity": 0.5,
      },
      selected: {
        fill: null,
        "stroke-width": 0,
      },
      selectedHover: {}
    },
    map: "region_map_balls",
    inactive: "#ADADAD",
    focusOn: { x: 0, y: 0, scale: 1 },
    series: {
      regions: [{
        values: values,
        scale: scale
      }]
    },
    onRegionClick: function(e, code) {
      if (clubs.hasOwnProperty(code)) {
        window.location = clubs[code].url;
      }
    },
    onRegionTipShow: function(e, tip, code) {
      if (clubs.hasOwnProperty(code)) {
        tip.html(clubs[code].label);
        return true;
      } else {
        return false;
      }
    },
    onRegionOut: function(event, code) {
      $("#" + code).removeClass("hover");
    },
    onRegionOver: function(event, code) {
      if (!clubs.hasOwnProperty(code)) {
        event.preventDefault();
      } else {
        $("#" + code).addClass("hover");
      }
    }
  });

  $("#clubs").on("mouseover mouseout", ".club a", function(event) {
    var elem = event.target;
    var evType = event.type;
    var code = $(elem).data("club");
    if (evType === "mouseover") {
      map.regions[code].element.setHovered(true);
    } else {
      map.regions[code].element.setHovered(false);
    }
  });

  

  $("#clubs").html("");

  $.each(clubs, function(index, value) {
    var divHtml = $("<div id=\"" + index + "\" class=\"filterin club\"></div>");
    var linkHtml = $("<a href=\"" + value.url + "\" data-club=\"" + index + "\" >" + value.label + "</a>");
    $(divHtml).append(linkHtml);
    $("#clubs").append(divHtml);
  });

  

  $("#filter-button").click(function(e) {
    postcode = $("#filter-input").val();
    validPostcode = isPostcodeValid(postcode)
    if (true == validPostcode) {
      latLngPostcode(postcode);
    } else {
      alert("Postcode invalid EG. XX00 0XX");
    }
  });

  $("#filter-input").keypress(function(e) {
    postcode = $("#filter-input").val();
    validPostcode = isPostcodeValid(postcode)
    if (e.which == 13) {
      if (true == validPostcode) {
        latLngPostcode(postcode);
      } else {
        alert("Postcode invalid EG. XX00 0XX");
      }
      
    }
    
  });

});
