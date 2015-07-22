var liteatnite = (function () {

    var responsiveStates = (function () {
        return {
            init: function () {
                ssm = ssm || {};

                ssm.addStates([
					{
						id: 'xs',
						maxWidth: 479,
						colorbox: false
					},
					{
						id: 'sm',
						minWidth: 480,
						maxWidth: 767,
						colorbox: false
					},
					{
						id: 'md',
						minWidth: 768,
						maxWidth: 991
					},
					{
						id: 'lg',
						minWidth: 992
					}]
				).ready();
            },
            is: function (state) {
                var states = ssm.getCurrentStates();
                for (var prop in states) {
                    if (states.hasOwnProperty(prop)) {
                        if (states[prop].id === state) {
                            return true;
                        }
                    }
                }
                return false;

            }
        };
    }());


    // Text expander
    var showHide = (function () {
        return {
            init: function () {

                $('a.toggleContent').click(function () {
                    var $this = $(this);

                    if ($this.hasClass('active')) {
                        $this.prev('.expand').slideUp();
                        $this.removeClass('active');
                        $this.html($this.attr('data-more'));
                    }
                    else {
                        $this.prev('.expand').slideDown();
                        $this.addClass('active');
                        $this.html($this.attr('data-less'));
                    }
                    return false;
                });
            }
        };
    }());


    // Kids Triathlon Google Maps
    var eventMap = (function () {
        function createMap(lat, lng, zoomVal) {
            var mapOptions = {
                center: new google.maps.LatLng(lat, lng),
                zoom: zoomVal,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                styles: [{ featureType: 'all', stylers: [{ saturation: -100 }, { gamma: 0.0 }] }]
            };

            map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);
            markerData = {
                0: {
                    lat: 51.516066,
                    lng: -0.125992,
                    url: 'http://www.litenite.co.uk',
                    icon: "../images/icons/map-marker.png",
                    title: "Oasis Swimming Centre",
                    data: {
                        drive: false,
                        zip: "WC2H 9AG",
                        city: "London",
                        address: "32 Endell St"
                    }
                }
            };

            for (var markerId in markerData) {
                markers[markerId] = createMarker(markerData[markerId]);
            }
        }

        function createMarker(data) {
            var myLatLng = new google.maps.LatLng(data.lat, data.lng);

            var marker = new google.maps.Marker({
                position: myLatLng,
                icon: data.icon,
                map: map,
                title: data.title
            });
            google.maps.event.addListener(marker, 'click', function () {
                window.location.href = data.url;
            });
            return marker;
        }

        var map;
        var markers = {};

        function initialize() {
            createMap(51.516066, -0.125992, 18);
        }

        return {
            init: function () {
                if ($("#googleMap").length) {
                    google.maps.event.addDomListener(window, 'load', initialize);
                }
            }
        };

    }());

    var eventsCarousel = (function () {
        var $widget = $('[data-widget="eventsCarousel"]');
        var $next = $widget.find('.next');
        var $prev = $widget.find('.prev');
        var $event = $widget.find('.event');
        var $events = $widget.find('.events');
        var position = 0;
        var newMargin = 0;

        var move = function (newPos) {
            var width, margin;

            resizeObjects(function () {
                width = $event.outerWidth() + parseInt($event.css('margin-right'));

                // NIGEL EDIT - offset the width calulations by 1 since we start with the left box blank
                margin = width * newPos;

                if (responsiveStates.is('lg')) {
                    margin = (width * newPos) - width;
                }
                else {
                    margin = margin - ($('.container-text').width() * 0.5) + ($event.outerWidth() * 0.5);
                }


                if (newPos <= $event.length) {
                    $events.css('margin-left', -margin);
                    position = newPos;
                    $events.find('.active').removeClass('active');
                    $event.eq(newPos).addClass('active');
                }
                if (position === 0) {
                    // NIGEL EDIT - start with left box blank on large screens
                    if (responsiveStates.is('lg')) {
                        $events.css('margin-left', width);
                    }
                    else {
                        $events.css('margin-left', '0');
                    }
                }
                if (position <= 0) {
                    $prev.hide();
                } else {
                    $prev.show();
                }
                if (position >= ($event.length - 1)) {
                    $next.hide();
                } else {
                    $next.show();
                }

            });

        };


        var next = function (e) {
            e.preventDefault();
            move(position + 1);
        };
        var prev = function (e) {
            e.preventDefault();
            move(position - 1);
        };

        var resizeObjects = function (callback) {
            setTimeout(function () {
                if (responsiveStates.is('xs') || responsiveStates.is('sm')) {
                    $event.width($('.container-text').width() * 0.7);
                    $events.width(9999);
                } else if (responsiveStates.is('md')) {
                    $event.width($('.container-text').width() * 0.8);
                    $events.width(9999);
                } else if (responsiveStates.is('lg')) {
                    // NIGEL EDIT - Scale down for 992 up
                    $event.width($('.container-text').width() * 0.23);
                    $events.width(9999);
                } else {
                    $event.css("width", "");
                    $events.css("width", "");
                }
                if (typeof callback === "function") {
                    callback();
                }
            }, 100);

        };

        return {
            init: function () {
                if ($widget.length) {
                    $next.on('click', next);
                    $prev.on('click', prev);

                    position = $widget.find('.active').index();
                    move(position);

                    $(window).on('resize', function () {
                        move(position);
                    });
                }
            }
        };
    }());




    var gallery = (function () {
        var $galleryContainer = $('.gallery'),
            $galleryCycles = $('.cycle-slideshow', $galleryContainer),
            $galleryCycle = $('.cycle-gallery'),
            $carouselCycle = $('.cycle-carousel'),
            $carouselCycleVisible = $carouselCycle.attr('data-cycle-carousel-visible'),
            $galleryFilterButtons = $('.gallery-filters a');

        var setup = function () {
            sync();
            filter();
        };

        var sync = function () {
            var $galleryCycle = $('.cycle-gallery'),
            $carouselCycle = $('.cycle-carousel'),
            $carouselCycleVisible = $carouselCycle.attr('data-cycle-carousel-visible');

            $galleryCycle.on('cycle-next cycle-prev', function (e, opts) {

            if(opts.slideCount > $carouselCycleVisible) {
                $carouselCycle.cycle('goto', opts.currSlide);
            }

            });

            $('.cycle-slide', $carouselCycle).click(function () {
                var index = $carouselCycle.data('cycle.API').getSlideIndex(this);
                $galleryCycle.cycle('goto', index);
            });
        };

        var filter = function () {
            $galleryFilterButtons.click(function (e) {
                e.preventDefault();

                var filterButton = $(this);
                var filter = filterButton.attr('data-filter-value');
                var filterButtonText = filterButton.html();

                var filterButtonParent = filterButton.parents('.gallery-filters');
                var filterCategory = filterButtonParent.attr('data-filter');

                var filterSelectedTitle = $('.dropdown-selected', filterButtonParent);

                $galleryFilterButtons.removeClass("active");
                filterButton.addClass("active");

                filterSelectedTitle.html(filterButtonText);
                filterSelectedTitle.attr('data-filter-selected-value', filter);

                var url = '/Gallery/RefreshGallery?';
                $($('.gallery-filters .dropdown-selected')).each(function (index) {
                    url += $(this).attr('data-filter') + 'Id=' + $(this).attr('data-filter-selected-value') + '&';
                });
                url = url.substring(0, url.length - 1);
                console.log(url);

                $('#GalleryResultsUpdatePanel').load(url, function () {
                    reinit();
                });
            });
        };

        var reinit = function () {
            var $galleryCycles = $('.cycle-slideshow', $galleryContainer);

            $galleryCycles.cycle();
            sync();
            console.log('Reinit');
        };

        return {
            init: function () {
                if ($('.gallery').length) {
                    setup();
                }
            }
        };
    }());

    var relayAccordion = (function () {
        if ($('.relay').length) {
            // setup map
            var latlngcenter = new google.maps.LatLng(53.972138, -7.915266);

            var myOptions = {
                zoom: 6,
                center: latlngcenter,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                streetViewControl: false,
                styles: [{ featureType: 'all', stylers: [{ saturation: -100 }, { gamma: 0.0 }] }]
            };

            var map = new google.maps.Map(document.getElementById('relay-map'), myOptions);
            
            var infowindow = new google.maps.InfoWindow();

            var directionsDisplay = new google.maps.DirectionsRenderer({ suppressMarkers: true, polylineOptions:{ strokeColor:'#ED1C29', strokeWeight: 6 }});
            var directionsService = new google.maps.DirectionsService();
            directionsDisplay.setMap(map);
            
            // create array of locations from the data attributes on the accorion header
            var markerData = [];

            $('.relay .item-toggle').each(function() {
                    obj = {};
                    obj.start = $(this).attr("data-start");
                    obj.end = $(this).attr("data-end");
                    obj.startAddress = $(this).attr("data-start-address");
                    obj.endAddress = $(this).attr("data-end-address");
                    markerData.push(obj);

            });

            // create marker for each start location and also the final location
            for (var markerId in markerData) {
                createMarker(markerData[markerId].start, markerData[markerId].startAddress);
            }
            createMarker(markerData[markerData.length-1].end, markerData[markerId].endAddress);

        }

        // create accordion and change map based on its id
        var setup = function () {

            $(".relay .accordion").accordion({
                active: false,
                collapsible: true,
                header: ".item-toggle",
                heightStyle: "content",
                icons: false,
                activate: function (event, ui) {
                    if (ui.newHeader.length) {
                        getDirections(ui.newHeader.attr('data-id'));
                    }
                }
            });

        };


        function createMarker(latlng, title) {
            var latlngcurrent = getLatLngFromString(latlng);
            var marker;
            if(title === undefined) { title = ''; }

            marker = new google.maps.Marker({
                map: map,
                position: latlngcurrent,
                icon: "../images/icons/map-marker.png",
                title: title
            });

            if(title !== '') {
                google.maps.event.addListener(marker, 'click', function () {
                    infowindow.setContent("<div class=\"map_marker\">" + title + "</div>");
                    infowindow.open(map, marker);
                });
            }

            return marker;
        }


        function getLatLngFromString(ll) {
            var lat = ll.replace(/\s*\,.*/, ''); 
            var lng = ll.replace(/.*,\s*/, ''); 
             
            var latLng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
             
            return latLng;
        }

        function getDirections(markerID) {

            var request = {
                origin: markerData[markerID].start,
                destination: markerData[markerID].end,
                travelMode: google.maps.DirectionsTravelMode.DRIVING

            };

            directionsService.route(request, function (response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                    adjustMapPosition();

                } else {
                    alert("We could not find directions for your request, please check your From and To inputs then try again.");
                }
            });
        }


        function adjustMapPosition() {
            window.setTimeout(function() {
                map.setZoom(map.getZoom() - 1);
           }, 200);
            window.setTimeout(function() {
                map.panBy(-250,0);
           }, 400);
        }


        return {
            init: function () {
                if ($('.relay').length) {
                    setup();
                }
            }
        };
    }());

    return {
        init: function () {
            responsiveStates.init();

            // SVG fallback
            if (!Modernizr.svg) {
                $('img[src*="svg"]').attr('src', function () {
                    return $(this).attr('src').replace('.svg', '.png');
                });
            }
            // Placeholder fallback
            if (!Modernizr.input.placeholder) {
                $('input, textarea').placeholder();
            }

            // Mobile nav
            var flexnav = $(".flexnav");
            flexnav.flexNav();

            // Smooth scroll secondary links
            if ($('.nav-secondary').length) {
                $('.nav-secondary .scroll-link').smoothScroll({ offset: -145 });
            }
            if (flexnav.length) {
                $('.scroll-link', flexnav).smoothScroll({
                    offset: -70,
                    beforeScroll: function () {
                        flexnav.removeClass('flexnav-show');
                        $(".menu-button").removeClass('active');
                    }
                });
            }

            // About Timeline
            eventsCarousel.init();

            // Show/Hide Text
            showHide.init();

            // Triathlon page
            if ($('#googleMap').length) {
                eventMap.init();
            }

            // Gallery
            gallery.init();

            // Relay page
            relayAccordion.init();

            // Colorbox
            $('.colorbox-link').colorbox({
                transition: "none",
                close: '&times;',
                href: function () {
                    return $(this).attr('href') + ' #' + $(this).attr("data-colorbox-element");
                }
            });

        },
        responsiveStates: responsiveStates
    };

}());
$(document).ready(liteatnite.init);
