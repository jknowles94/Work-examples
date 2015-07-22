var google = google || { maps : { Geocoder : function(){}, Point : function(){}, Marker : function(){}, LatLngBounds : function(){}, InfoWindow : function(){} }};

//Validation plugin
;(function ( $, window, document, undefined ) {

    var pluginName = "validation",
        defaults = {
            errorTemplate: '<p class="error-message">%E%</p>',
            validateFieldCallback: function(){}
        };

    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element = element;
        this.options = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            $(this.element).on('change','[data-validate]',this.validateField);
            $(this.element).bindFirst('submit',this.validateForm);
        },
        validateForm: function(e){
            var valid = true, plugin = $(this).data("plugin_" + pluginName);

            $(plugin.element).find('[data-validate]:visible').each(function(){
                if(plugin.validateField(e,this) === false){
                    valid = false;
                }
            });

            return valid;
        },
        validateField: function(e,that){
            var valid = true,
                field = that || this,
                $field = $(field),
                requiredValidators = $field.attr('data-validate').split(' '),
                plugin = $(e.delegateTarget).data("plugin_" + pluginName),
                $error = $field.data('error') || null,
                errorHTML = null;

            if($error !== null){
            	$field.removeClass('error');
                $error.remove();
            }

            var validationAttr = {
                value: $field.val(),
                defaultValue: $field.attr('data-default'),
                match: $($field.attr('data-match')).val() || "",
                minLength: parseInt($field.attr('data-minlength-val')) || 0,
                $field: $field
            };

            for (var i = 0; i < requiredValidators.length; i++) {

                if(validators[requiredValidators[i]](validationAttr) === false){
                    errorHTML = plugin.options.errorTemplate.replace("%E%", $field.attr('data-'+requiredValidators[i]));

                    $field.data('error',$(errorHTML).insertAfter($field));
                    $field.addClass('error');
                    valid = false;
                    break;
                }
            }

            plugin.options.validateFieldCallback();

            return valid;
        }
    };

    var validators = {
        required: function(validationAttr){
            var valid = true;

            if (validationAttr.value === null || validationAttr.value === '' || validationAttr.defaultValue === validationAttr.value) {
                valid = false;
            }

            return valid;
        },
        requiredif: function(validationAttr){
            var valid = true, $otherfield = $(validationAttr.$field.attr('data-otherfield'));

            if($otherfield.val() !== "" && (validationAttr.value === null || validationAttr.value === '' || validationAttr.defaultValue === validationAttr.value)){
                valid = false;
            }

            return valid;
        },
        email: function(validationAttr){
            var valid = false;

            if (validationAttr.value.match(/^((?:(?:(?:\w[\.\-\+]?)*)\w)+)\@((?:(?:(?:\w[\.\-\+]?){0,62})\w)+)\.(\w{2,6})$/)) {
                valid = true;
            }

            return valid;
        },
        phone: function(validationAttr){
            var valid = false;

            if (validationAttr.value.length === 0 || validationAttr.value.match(/^([0-9\s])+$/g)) {
                valid = true;
            }

            return valid;
        },
        imei: function(validationAttr){
            var valid = false;

            if (validationAttr.value.match(/^[0-9]{15}$/g)) {
                valid = true;
            }

            return valid;
        },
        date: function(validationAttr){
            var valid = false;

            if (validationAttr.value.match(/^[0-3][0-9]\/[0-9]{2}\/[0-9]{4}$/g) || validationAttr.value.match(/^([0-9]{4})\-([0-9]{2})\-[0-3][0-9]$/g)) {
                valid = true;
            }

            return valid;
        },
        matched: function(validationAttr){
            var valid = false;

            if(validationAttr.value === validationAttr.match){
                valid = true;
            }

            return valid;
        },
        minlength: function(validationAttr){
            var valid = false;

            if(validationAttr.value.length >= validationAttr.minLength){
                valid = true;
            }

            return valid;
        },
        dateinpast: function(validationAttr){
            var valid = false, enteredDate = privateMethods.parseDate(validationAttr.value), currentDate = new Date();

            currentDate.setHours(23,59,59,999); // End of the day

            if(enteredDate <= currentDate){
                valid = true;
            }

            return valid;
        }
    };

    var privateMethods = {
        parseDate: function(input) {
            var parts = input.split('/');

            // If the date field wasn't split (i.e. it is in the format YYYY-mm-dd instead of dd/mm/YYYY)
            if (parts.length != 3) {
                parts = input.split('-');
                return new Date(parts[0], parts[1]-1, parts[2]); // months are 0-based
            }

            // new Date(year, month [, date [, hours[, minutes[, seconds[, ms]]]]])
            return new Date(parts[2], parts[1]-1, parts[0]); // months are 0-based
        }
    };

    $.fn.bindFirst = function(name, fn) {
        // bind as you normally would
        // don't want to miss out on any jQuery magic
        this.on(name, fn);

        // Thanks to a comment by @Martin, adding support for
        // namespaced events too.
        this.each(function() {
            var handlers = $._data(this, 'events')[name.split('.')[0]];
            // take out the handler we just inserted from the end
            var handler = handlers.pop();
            // move it at the beginning
            handlers.splice(0, 0, handler);
        });
    };

    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );

//
//Start GLH
//

var glh = (function(){
	var responsiveStates = (function(){
		return {
			init : function(){
				ssm = ssm || {};

				ssm.addStates([
					{
						id :'xs',
						maxWidth: 479
					},
					{
						id :'sm',
						minWidth: 480,
						maxWidth: 767
					},
					{
						id :'md',
						minWidth: 768,
						maxWidth: 991
					},
					{
						id :'lg',
						minWidth: 992
					}] 
					).ready();
				},
				is: function(state){
					var states = ssm.getCurrentStates();
					for ( var prop in states ){
						if (states.hasOwnProperty(prop)){
							if (states[prop].id === state){
								return true;
							}
						}
					}
					return false;

				}
		}
	}());

	var people = (function(){
		var $widget = $('[data-widget="people"]');
		return {
			init: function(){
				if($widget.length){

					$widget.find('li').each(function(){
						var $link = $(this).find('a');
						var $modal = $(this).find('.modal');

						$link.on('click', function(e){
							e.preventDefault();

							if(responsiveStates.is('md') || responsiveStates.is('lg')){
								$modal.modal();								
							}
							else {
								document.location = $link.attr('href');

							}
						})
					});

				}
			}
		}
	}());

	var dropdownSelect = (function(){
		var $widgets = $('[data-widget="dropdown-select"]');
		return {
			init : function(){
				if($widgets.length){
					$widgets.each(function(){
						var $widget = $(this);
						var $active = $widget.find('.dropdown-active');
						var $li = $widget.find('li');
						var $dropdown = $widget.find('ul');
						var $input = $widget.find('input');

						$widget.data('set', function(value){
							$active.html(value);
							$input.val(value).trigger('change');
						});

						$widget.on('mouseenter', function(){
							$dropdown.show();
						}).on('mouseleave', function(){
							$dropdown.hide();
						});

						$li.on('click', function(){
							$active.html($(this).text());
							$dropdown.hide();
							$input.val($(this).data('value')).trigger('change');
						})
					});
				}
			}
		}
	}());

	var brandsHover = (function(){
		var $module = $('.mod-fp-brands');
		var $news = $('.mod-fp-news');

		return {
			init : function(){
				if($news.length){
					var $button = $news.find('.button');
					var $popoutNews = $news.find('.popout');
					var $close = $news.find('.close');
					var timeoutNews;

					var newsHide = function(){
						$popoutNews.fadeOut();
					}

					var delayedNewsHide = function(){
						timeoutNews = setTimeout(newsHide, 2000);
					}

					$button.on('mouseenter',function(){
						$popoutNews.fadeIn();
						clearTimeout(timeoutNews);
					}).on('mouseleave', delayedNewsHide);

					$popoutNews.on('mouseenter',function(){
						clearTimeout(timeoutNews);
					}).on('mouseleave', delayedNewsHide);

					$close.on('click', newsHide);
				}
				
				if($module.length){
					$module.find('li').each(function(){
						var $widget = $(this);
						var $button = $widget.find('a');
						var $popout = $($button.attr('href'));
						var $contentBox = $popout.find('.textContent');
						var timeout;

						var hide = function(){
							$popout.stop(true, true).fadeOut();
							$button.removeClass('active');
						}

						var delayedHide = function(){
							timeout = setTimeout(hide, 100);
						}

						$button.on('mouseenter',function(){
							if(responsiveStates.is('lg')){
								$popout.stop(true, true).fadeIn();
								$button.addClass('active');
								clearTimeout(timeout);
								newsHide();
							}
						}).on('mouseleave', function(){
							if(responsiveStates.is('lg')){
								delayedHide();
							}
						}).on('click', function(){
							document.location=$contentBox.find('a').attr('href');

							return false;
						});

						$contentBox.on('mouseenter',function(){
							clearTimeout(timeout);
						}).on('mouseleave', delayedHide);
					});
				}


				// Change width of content hover panel on homepage depending on number of list items
				var $listItem = $('.mod-fp-brands li').length, 
					$listItemsWidth = $('.mod-fp-brands li').width() + 1,
					$totalListItemsWidth = ($listItem * $listItemsWidth) - 2,
					$totalWidthPull = (0 - $totalListItemsWidth / 2 - 1);

					$(".hero-home .textContent").css({
						width : $totalListItemsWidth,
						'margin-left' : $totalWidthPull
					});
			}
		}
	}());

	var carousel4Item = (function(){
		var $widget = $('[data-widget="carousel-4item"]');
		var $prev = $widget.find('.prev');
		var $next = $widget.find('.next');
		var $ul = $widget.find('ul');
		var $li = $widget.find('li');
		var position = 0;

		var movePosition = function(newPosition){
			position = newPosition;
			$ul.css('margin-left', -(($li.width()+30) * newPosition)-15);
			$prev.show();
			$next.show();

			if(position <= 0){
				$prev.hide();
			}
			if((position + 4) > $li.length){
				$next.hide();
			}

		}

		var prev = function(){
			movePosition(position - 4);
		}

		var next = function(){
			movePosition(position + 4);
		}

		return {
			init : function(){
				if($widget.length){
					$prev.on('click', prev);
					$next.on('click', next);
					if(responsiveStates.is('md') || responsiveStates.is('lg')){
						movePosition(position);
					}

					$(window).on('resize', function(){
						if(responsiveStates.is('md') || responsiveStates.is('lg')){
							movePosition(position);
						} else {
							$ul.attr('style','');
							$next.hide();
							$prev.hide();
						}
					});

				}
			}
		}
	}());

	var map = (function(){
		var $widget = $('[data-widget="map"]');
		var markersData = $widget.data('markers');
		var markers = [];
		var countryMarkers = [];
		var infowindows = [];
		if( google.hasOwnProperty ){
			var geocoder = new google.maps.Geocoder();
			var bounds = new google.maps.LatLngBounds();
		}
		var map;
		var markersJson;

		var placeMarkers = function(addressesData, markersArray){
			for(var i in addressesData){
				placeMarkersFromAddress(addressesData[i], markersArray);
			}
		}

		var placeMarkersFromAddress = function(addressData, markersArray){
			geocoder.geocode({
				'address':addressData['address']
			}, function(results){
				for(var key in results){
					if(results.hasOwnProperty(key)){
						placeMarkerFromLatLng(results[key].geometry.location, markersArray, addressData);
					}
				}
			});			
		}

		var placeMarkerFromLatLng = function(latLng, markersArray, params) {

			params = params || {};
			var markerId = markersArray.length;
			var markerMap;

			if (params.icon){			
				image = params.icon;
			} else {
				image = {
					url: '/assets/images/icon/pins/'+(markerId+1)+'.png',
					//url: '/assets/images/icon/map-pin.png',
					anchor: new google.maps.Point(15, 37)
				};
			}

			if (params.map === null){			
				markerMap = params.map;
			} else {
				markerMap = map;
			}

			markersArray.push({
				marker : new google.maps.Marker({
						position: latLng,
						icon: image,
						map: markerMap,
						anchorPoint: new google.maps.Point(0, -37)
					}),
					params: params,
					infoWindow: new google.maps.InfoWindow({
						content: infoBoxContent(params)
					})
				}
			);

			bounds.extend(markersArray[markerId].marker.position);
			map.fitBounds (bounds);

			google.maps.event.addListener(markersArray[markerId].marker, 'click', function() {
				if(markersArray[markerId].infoWindow){
					markersArray[markerId].infoWindow.open(map, this);
				}
				if (typeof params.onclick === "function"){
					params.onclick();
				}

			});

			if(markersArray.length===1){
				map.setZoom(15);
			}
		}

		var infoBoxContent = function(content){
			content = content || {};
			var directions;
			if(content.label){
				directions = 'https://www.google.com/maps/preview/dir//' + content.address.replace(" ","+");
				return (
						'<div class="infoBox">'
					+ 	'<img src="/assets/images/logo.png" class="logo" />' 
					+		'<div class="textContent">'
					+			'<div>'+content.label+'</div>'
					+	   '<a class="directions" href="'+directions+'">Get Directions</a>'
					+		'</div>'
					+ '</div>');
			}

			if(content.name){
				directions = 'https://www.google.com/maps/preview/dir//'
					+ content.address.replace(' ',"+")
					+ '/@' + content.lat
					+ ',' + content.lng
					+',13z/';

				return ('<div class="infoBox">'
					+		'<div class="textContent">'
					+			'<h2>'+content.name+'</h2>'
					+			'<p>'+content.address+'</p>'
					+			'<p class="directions"><a href="'+directions+'">Get Directions</a></p>'
					+	   	'<dl><dt>Phone</dt><dd>'+content.phone+'</dd>'
					+			'<p class=""><a href="'+content.url+'" target="_blank" class="btn-arrow">Go to Hotel Website</a></p>'
					+		'</div>'
					+ '</div>');
			}
			return;

		}

		var filterMarkers = function(){
			clearLocations();
			  	$('.no-results').hide();

			//if 'all countries' is selected
			if($('.map-countries input').val()==='.'){
				recountCountries($('.map-brands input').val());
			}
			//show filtered country
			else {
				bounds = new google.maps.LatLngBounds();
				var markerNumber = 0;
			  for (var i = 0; i < markers.length; i++) {
			  	if(
			  			markers[i].params.country.match($('.map-countries input').val()) &&
			  			markers[i].params.brand.match($('.map-brands input').val())
			  		){
					    markers[i].marker.setMap(map);
							bounds.extend(markers[i].marker.position);
							markerNumber++;
			  	} else {
				    markers[i].marker.setMap(null);		  				  		
			  	}
			  }

			  if(markerNumber > 0){
			  	map.fitBounds (bounds);
			  }
			  else{
			  	map.setZoom(2);
			  	$('.no-results').show();
			  }

				
			}
		}

		var recountCountries = function(brand){
			var countries = getCountryCount(brand);
			var country = getCountryCount(brand);

			bounds = new google.maps.LatLngBounds();
			
			for (marker in countryMarkers){
				if(countryMarkers.hasOwnProperty(marker)){
					country = countryMarkers[marker].params.country;
					if (countries[country] && countries[country].icon) {
						countryMarkers[marker].marker.setIcon(countries[country].icon)					
						countryMarkers[marker].marker.setMap(map);
						bounds.extend(countryMarkers[marker].marker.position);
					} else {
						countryMarkers[marker].marker.setMap(null);
					}
				}
			}
			map.fitBounds (bounds);
		}

		var getCountryCount = function(brand){
			var countries = {};
			for (place in markersJson) {
		  	if(markersJson.hasOwnProperty(place) && (markersJson[place].brand.match( brand ))){
			    if(countries[markersJson[place].country]){
			    	countries[markersJson[place].country].amount = countries[markersJson[place].country].amount + 1;
			    	countries[markersJson[place].country].icon.url = 
			    		'/assets/images/icon/pins/' + countries[markersJson[place].country].amount + ".png";
			    } else {
			    	countries[markersJson[place].country] = {
			    		address: markersJson[place].country,
			    		amount : 1,
			    		icon: {
									url: '/assets/images/icon/pins/1.png',
									anchor: new google.maps.Point(15, 37)
							}
			    	};
			    }
		  	}
		  }
			return countries;
		}

		var loadMap = function(){

			var markers = [];
			var marker = [];
			var styles = [ 
			  { 
			    featureType: "water", 
			    elementType: "all", 
			    stylers: [ 
			      { visibility: "on" }, 
			      { lightness: 15 } 
			    ] 
			  },{ 
			    featureType: "all", 
			    elementType: "all", 
			    stylers: [ 
			      { saturation: -100 }, 
			    ] 
			  } 
			] ;
		  
		  var styledMap = new google.maps.StyledMapType(styles,
    		{name: "Styled Map"});
			var mapOptions = {
				zoom: 5,
				scrollwheel: false,
				mapTypeControlOptions: {
      				mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
    			}
			}
			if(Modernizr.touch !== false){
    			var mapOptions = {
    				draggable:false
    			}
    		}

			map = new google.maps.Map($widget.get(0), mapOptions);
		  map.mapTypes.set('map_style', styledMap);
		  map.setMapTypeId('map_style');

			if (typeof markersData!== 'undefined'){
				placeMarkers(markersData, markers);

			} else {

			    var localTesting = true;
			    var jsonSource;

			    if (localTesting) {
			        jsonSource = '/markers.json';
			    }
			    else
			    {
			        jsonSource = '/locationmap/';
			    }

			    downloadUrl(jsonSource, function (data) {
					loadAllCountries(data, function(){
						markersJson = data;
					});
				});
			}
			$('.mod-map-wrapper input').on('change', function(){
				filterMarkers();
			});

            //If the location is in the url load those locations
			if (window.location.hash.replace('#', '').length) {
			    setTimeout(function () {
			        //console.log('dropdown click');
			        $('[data-value="' + window.location.hash.replace('#', '') + '"]').click();
			    }, 2000);   
			}

		}

		var downloadUrl = function(url,callback) {
			$.getJSON( url, function( data ) {
			  callback(data);
			});
		}

		var loadAllCountries = function(data, callback) {
			var countries = {};
			var markersSet = markers.length;
		  clearLocations();

			  //create array of countries
				for (marker in data) {
			  	if(data.hasOwnProperty(marker) && (data[marker].brand.match( $('.map-brands input').val() ))){
			  		//console.log(countries[data[marker].country]);
			  		//load countries and place on map
				    if(countries[data[marker].country]){
				    	countries[data[marker].country].amount = countries[data[marker].country].amount + 1;
				    	countries[data[marker].country].icon.url = 
				    		'/assets/images/icon/pins/' + countries[data[marker].country].amount + ".png";
				    } else {
				    	countries[data[marker].country] = {
				    		address: data[marker].country,
				    		country: data[marker].country,
				    		amount : 1,
				    		icon: {
										url: '/assets/images/icon/pins/1.png',
										anchor: new google.maps.Point(15, 37)
									},
				    		onclick: function(){
				    			$('.map-countries .dropdown-select').data('set')(this.address);
				    		}
				    	};
				    }

				    //if place markers not already loaded in
				    if(!markersSet){
						    //load marker addresses but don't place on map
					  		var name = data[marker].name;
						    var address = data[marker].address;
						    var latlng = new google.maps.LatLng(
						        parseFloat(data[marker].lat),
						        parseFloat(data[marker].lng));
						    var markerData = data[marker];
						    markerData.map = null;
						    markerData.icon = {
											url: '/assets/images/icon/pins/pin.png',
											anchor: new google.maps.Point(13, 42)
										};
					    	placeMarkerFromLatLng(latlng, markers, markerData);
				    }
			  	}
			  }

			  placeMarkers(countries, countryMarkers);
			  if(typeof callback === "function"){
			  	callback();
			  }
		}

		var clearLocations = function() {
		  for (var i = 0; i < markers.length; i++) {
		    markers[i].marker.setMap(null);
		  }
		  for (var i = 0; i < countryMarkers.length; i++) {
		    countryMarkers[i].marker.setMap(null);
		  }
		}

		return {
			init :function(){
				if ($widget.length){
					google.maps.event.addDomListener(window, 'load', loadMap);
				}
			}
		}
	}());


	//Commenting out as it's causing an error. 
	// var cookiepolicy = function(){
	// 	var close = function(){
	// 		$('#cookie-ribbon').remove();
	// 		$('body').removeClass('has-coookiebar');
	// 		return false;
	// 	}

	// 	return {
	// 		init: function(){
	// 			var cookie = Cookies.get('cookiebar');
	// 			if(cookie === undefined){
	// 				Cookies.set('cookiebar', 'false', { expires: 60*60*24*365 });
	// 				$('#cookie-ribbon').on('click','.close', close);
	// 			}
	// 			else{
	// 				$('#cookie-ribbon').remove();
	// 				$('body').removeClass('has-coookiebar');
	// 			}
	// 		}
	// 	};
	// }();

	return {
		init : function(){
			responsiveStates.init();
			brandsHover.init();
			carousel4Item.init();
			people.init();
			//subnav.init();
			dropdownSelect.init();
			map.init();
			//cookiepolicy.init();

			$('form').validation();

			// Sets active class on subnav item
			if($('.nav-subnav').length){
				$('body').scrollspy({ target: '.nav-subnav', offset: 163 });
			}
			else{
				$('body').scrollspy({ target: '.mod-fp-navSquares'});
			}

			// Adding hash to URL
			$(window).on('activate.bs.scrollspy', function(e) {
			  var $hash, $node;
			  $hash = $("a[href^='#']", e.target).attr("href").replace(/^#/, '');
			  $node = $('#' + $hash);
			  if ($node.length) {
			    $node.attr('id', '');
			  }
			  document.location.hash = $hash;
			  if ($node.length) {
			    return $node.attr('id', $hash);
			  }
			});

			// Page scrolling for sub navigation
            $('body').on('click', '.nav-subnav a', function(){
                var offset = $($(this).attr('href')).position().top - $('.navbar-fixed-top').outerHeight();

                $('html,body').animate({scrollTop: offset}, 1000);

                return false;
            });

            if(window.location.hash === "#contact"){
            	$('[href="#contact"]').click();
            }
		},
		responsiveState : responsiveStates.is
	}
}());

$(document).on('ready', glh.init);