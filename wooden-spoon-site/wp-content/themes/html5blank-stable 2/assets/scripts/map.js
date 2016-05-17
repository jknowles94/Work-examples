var MapPage = (function () {


// Map filter
	var Map = (function() {
		function init() {
			function updateMap(f) {
			    $.ajax({
			      type: "GET",
			      url: "../wp-content/themes/html5blank-stable 2/inc/filter.php",
			      data: {
			        filter: f
			      },
			      success: function(data) {
			        var mapObject = $("#uk-map").vectorMap("get", "mapObject");
			        var mapData = Array();
			        globalData = data;
			        $("#regions").html("");
			        $.each(globalData, function(index, value) {
			          mapData[index] = value.status;
			          if (value.label !== false) {
			            var divHtml = $("<div id=\"" + index + "\" class=\"" + value.status + " region\"></div>");
			            var linkHtml = $("<a href=\"" + value.url + "\" data-region=\"" + index + "\" >" + value.label + "</a>");
			            $(divHtml).append(linkHtml);
			            $("#regions").append(divHtml);
			          }
			        });
			        mapObject.series.regions[0].setValues(mapData);
			        $("#loader-screen").css("display", "none");
			      },
			      error: function(xhr, type, exception) {
			        // if ajax fails display error alert
			        // alert("ajax error response type " + type);
			      }
			    });
			}

		  	function findRegion(robj, rname) {
		    	var code = "";
		    	$.each(robj, function(key) {
		      		if (unescape(encodeURIComponent(robj[key].config.name)) === unescape(encodeURIComponent(rname))) {
		        		code = key;
		      		}
			    });
			    return code;
			}

			$(function() {

			    $("#loader-screen").css("display", "initial");

			    $("#uk-map").vectorMap({
			      map: "map",
			      backgroundColor: '#c5edff',
			      zoomOnScroll: false,
			      focusOn: {
					x: 0.3,
	       			y: 0.4,
	       			scale: 1.55
				  },
			      regionStyle: {
			        hover: {
			          fill: "#EC7994",
			          "fill-opacity": 1,
			          stroke: "none",
			          "stroke-width": 0,
			          cursor: "pointer"
			        }
			      },
			      series: {
			        regions: [{
			          scale: {
			            filterin: "#D51E49",
			            filterout: "#EC7994",
			            inactive: "#ADADAD"
			          },
			          normalizeFunction: "polynomial"
			        }]
			      },
			      onRegionTipShow: function(event, label, code) {
			        if (globalData[code].label) {
			          label.html('<b>' + globalData[code].label + '</b>');
			        } else {
			          event.preventDefault();
			        }
			      },
			      onRegionOver: function(event, code) {
			        $("#" + code).addClass("hover");
			      },
			      onRegionOut: function(event, code) {
			        $("#" + code).removeClass("hover");
			      },
			    }).on("click", function(e) {
			      if ($(e.target).data("code") && (globalData[$(e.target).data("code")].label !== false) ) {
			      	window.location = globalData[$(e.target).data("code")].url;
			      }
			    });

			    updateMap(null);

			    $("#filter-button").click(function(e) {
			      updateMap($("#filter-input").val());
			    });

			    $("#filter-input").keyup(function(e) {
			      if (e.keyCode == 13) {
			        updateMap($(this).val());
			      }
			    });

			    var mapObj = $("#uk-map").vectorMap("get", "mapObject");

			    $("#regions").on("mouseover mouseout", ".region a", function(event) {
			      // event.preventDefault();
			      var elem = event.target,
			        evtype = event.type,
			        cntrycode = findRegion(mapObj.regions, $(elem).data("region"));
			      if (cntrycode === "") {
			        return true;
			      }
			      if (evtype === "mouseover") {
			        mapObj.regions[cntrycode].element.setHovered(true);
			      } else {
			        mapObj.regions[cntrycode].element.setHovered(false);
			      }
			    });
			});
		}

		return {
			init:init
		};
	}());


// Global init function
	return {
		init: function () {
			Map.init();

		}

	};

}());

$(document).ready(MapPage.init);
