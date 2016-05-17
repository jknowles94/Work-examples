var MapPage = (function() {


  // Map filter
  var Map = (function() {
    function init() {
      function updateMap(f) {
        var mapObject = jQuery("#uk-map").vectorMap("get", "mapObject");
        var mapData = Array();
        globalData = {
          "reg01": { "status": "filterin", "label": "reg01" },
          "reg02": { "status": "filterin", "label": "reg02" },
          "reg03": { "status": "filterin", "label": "reg03" },
          "reg04": { "status": "filterin", "label": "reg04" },
          "reg05": { "status": "filterin", "label": "reg05" },
          "reg06": { "status": "filterin", "label": "reg06" },
          "reg07": { "status": "filterin", "label": "reg07" },
          "reg08": { "status": "filterin", "label": "reg08" },
          "reg09": { "status": "filterin", "label": "reg09" },
          "reg10": { "status": "filterin", "label": "reg10" },
          "reg11": { "status": "filterin", "label": "reg11" },
          "reg12": { "status": "filterin", "label": "reg12" },
          "reg13": { "status": "filterin", "label": "reg13" },
          "reg14": { "status": "filterin", "label": "reg14" },
          "reg15": { "status": "filterin", "label": "reg15" },
          "reg16": { "status": "filterin", "label": "reg16" },
          "reg17": { "status": "filterin", "label": "reg17" },
          "reg18": { "status": "filterin", "label": "reg18" },
          "reg19": { "status": "filterin", "label": "reg19" },
          "reg20": { "status": "filterin", "label": "reg20" },
          "reg21": { "status": "filterin", "label": "reg21" },
          "reg22": { "status": "filterin", "label": "reg22" },
          "reg23": { "status": "filterin", "label": "reg23" },
          "reg24": { "status": "filterin", "label": "reg24" },
          "reg25": { "status": "filterin", "label": "reg25" },
          "reg26": { "status": "filterin", "label": "reg26" },
          "reg27": { "status": "filterin", "label": "reg27" },
          "reg28": { "status": "filterin", "label": "reg28" },
          "reg29": { "status": "filterin", "label": "reg29" },
          "reg30": { "status": "filterin", "label": "reg30" },
          "reg31": { "status": "filterin", "label": "reg31" },
          "reg32": { "status": "filterin", "label": "reg32" },
          "reg33": { "status": "filterin", "label": "reg33" },
          "reg34": { "status": "filterin", "label": "reg34" },
          "reg35": { "status": "filterin", "label": "reg35" },
          "reg36": { "status": "filterin", "label": "reg36" },
          "reg37": { "status": "filterin", "label": "reg37" },
          "reg38": { "status": "filterin", "label": "reg38" },
          "reg39": { "status": "filterin", "label": "reg39" },
          "reg40": { "status": "filterin", "label": "reg40" },
          "reg41": { "status": "filterin", "label": "reg41" },
          "reg42": { "status": "filterin", "label": "reg42" },
          "reg43": { "status": "filterin", "label": "reg43" },
          "reg44": { "status": "filterin", "label": "reg44" },
          "reg45": { "status": "filterin", "label": "reg45" },
          "reg46": { "status": "filterin", "label": "reg46" },
          "reg47": { "status": "filterin", "label": "reg47" },
          "reg48": { "status": "filterin", "label": "reg48" },
          "reg49": { "status": "filterin", "label": "reg49" },
          "reg50": { "status": "filterin", "label": "reg50" },
          "reg51": { "status": "filterin", "label": "reg51" }
        };
        jQuery("#regions").html("");
        jQuery.each(globalData, function(index, value) {
          mapData[index] = value.status;
          if (value.label !== false) {
            var divHtml = jQuery("<div id=\"" + index + "\" class=\"" + value.status + " region\"></div>");
            var linkHtml = jQuery("<span href=\"" + value.url + "\" data-region=\"" + index + "\" >" + value.label + "</span>");
            jQuery(divHtml).append(linkHtml);
            jQuery("#regions").append(divHtml);
          }
        });
        mapObject.series.regions[0].setValues(mapData);
      }


      function findRegion(robj, rname) {
        var code = "";
        jQuery.each(robj, function(key) {
          if (unescape(encodeURIComponent(robj[key].config.name)) === unescape(encodeURIComponent(rname))) {
            code = key;
          }
        });
        return code;
      }

      jQuery(function() {

        jQuery("#loader-screen").css("display", "initial");

        jQuery("#uk-map").vectorMap({
          map: "map",
          backgroundColor: '#c5edff',
          zoomOnScroll: false,
          focusOn: {
            x: 0,
            y: 0,
            scale: 100
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
          onRegionOver: function(event, code) {
            jQuery("#" + code).addClass("hover");
          },
          onRegionOut: function(event, code) {
            jQuery("#" + code).removeClass("hover");
          },
        });

        updateMap(null);

        jQuery("#filter-button").click(function(e) {
          updateMap(jQuery("#filter-input").val());
        });

        jQuery("#filter-input").keyup(function(e) {
          if (e.keyCode == 13) {
            updateMap(jQuery(this).val());
          }
        });

        var mapObj = jQuery("#uk-map").vectorMap("get", "mapObject");

        jQuery("#regions").on("mouseover mouseout", ".region span", function(event) {
          // event.preventDefault();
          var elem = event.target,
            evtype = event.type,
            cntrycode = findRegion(mapObj.regions, jQuery(elem).data("region"));
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
      init: init
    };
  }());


  // Global init function
  return {
    init: function() {
      Map.init();
    }

  };

}());

jQuery(document).ready(MapPage.init);
