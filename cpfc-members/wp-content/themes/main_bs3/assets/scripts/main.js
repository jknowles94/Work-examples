var cpfc = (function () {
// Responsive stae managerment
	var responsiveStates = (function () {
		return {
			init: function () {
				ssm = ssm || {};

				ssm.addStates([
					{
						id: 'xs',
						maxWidth: 767,
						colorbox: false,
                        onEnter: function(){
                             $('.login-panel').css('margin-top', '');

                            if($('.login-panel').hasClass('open')){

                                $('.login-btn').click();
                            }

                        }
					},
					{
						id: 'sm',
						minWidth: 768,
						colorbox: false,
                        onEnter: function(){
                            $('.login-panel').css('margin-top', '');
                             if($('.login-panel').hasClass('open')){
                                $('.login-btn').click();
                            }

                            // You Tube videos overlay
                            $(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});

                            // Data Capture overlay
                            $('#data-capture-panel').colorbox({
                                href: function(){
                                    var url = $(this).attr('href');
                                    return url + ' .datacapture-panel';
                                },
                                onComplete: function() {
                                    dataCapture.init();
                                }
                            });
                        }
					}
				]).ready();
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


	// Login sliding panel
	var loginPanel = $('.login-panel');

	$('.login-btn').click(function(e) {
		e.preventDefault();

		if(!loginPanel.hasClass('open')) {
			loginPanel.animate({ marginTop: '0px' }, 300);
            $('.login-btn').html('Close');
		}
		else
		{
            var marginTopLogin = "-141px";

            $('.login-btn').html('Login');

            if (responsiveStates.is('xs')){
                marginTopLogin = "-243px";
            }
            loginPanel.animate({ marginTop: marginTopLogin }, 300);

		}

		loginPanel.toggleClass('open');
	});

	//Slidebars
    $.slidebars({
        siteClose: true
    });

    $("select").styleSelects();

   //Responsive Videos
    var $allVideos = $("iframe[src^='http://player.vimeo.com'], iframe[src^='http://www.youtube.com']");

	// Cookies
    var cookiePolicy = (function(){
        var $cookieWrapper = $('#cookie-wrapper');

        function init() {
            var cookie = Cookies.get('CPFCcookie');

            if(cookie === undefined){
                $cookieWrapper.addClass('active');
                Cookies.set('CPFCcookie', 'true', { expires: 60*60*24*365 });
                $cookieWrapper.on('click','.close', close);
            }
            else{
                close();
            }
        }

        function close() {
            $cookieWrapper.remove();
            return false;
        }

        return {
            init: init
        };
	}());

    // Data Capture
    var dataCapture = (function(){

        function init() {
            $form = $('#cboxLoadedContent form.datacapture');
            $(document).on('submit', $form, function(event) {
                $.ajax({
                    type: "POST",
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    success: function(data) {
                        $('#cboxLoadedContent').html(data);
                        $.colorbox.resize();

                        // If Data Capture was successful
                        if ($('#datacapture-success').length) {
                            $('.member-alert').remove();
                        }
                    },
                    dataType: 'html'
                });

                return false;
            });
        }

        return {
            init: init
        };
    }());

    // Reset Password Redirect
    var resetPasswordRedirect = (function(){

        function init() {
            // Check variable exists (i.e. a password has been successfully reset) and autoredirect is enabled
            if (typeof CPFC_PASSWORD_RESET_AUTO_REDIRECT != 'undefined' && CPFC_PASSWORD_RESET_AUTO_REDIRECT === true) {
                // If delay value is greater than zero delay before redirecting
                if (CPFC_PASSWORD_RESET_REDIRECT_DELAY > 0) {
                    setTimeout(function() {
                        window.location.replace(CPFC_PASSWORD_RESET_REDIRECT_URL);
                    }, CPFC_PASSWORD_RESET_REDIRECT_DELAY * 1000);
                } else { // Otherwise immediately redirect
                    window.location.replace(CPFC_PASSWORD_RESET_REDIRECT_URL);
                }
            }
        }

        return {
            init: init
        };
    }());


    // Load more - Used on home for YouTube videos
    var loadMore = (function () {

        var localTesting = false;

        function init() {
            var $loadMorePanel = $('.load-more-panel');

            $loadMorePanel.each(function() {

                var $currentPanel = $(this);
                var $loadMoreButton = $currentPanel.find('.load-button').find('a');

                $loadMoreButton.click(function (e) {
                    e.preventDefault();
                    getMore($currentPanel);



                    setTimeout(function() {
                        $(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
                    }, 1000);
                });

            });

        }


        function getMore($currentPanel) {

            $currentPanel = $($currentPanel);

            var $loadBtnContainer = $currentPanel.find('.load-button');
            var $loadMoreButton = $loadBtnContainer.find('a');
            var $loadBtnText = $loadBtnContainer.find('a span');
            $loadBtnText.html("Loading");

            var dataSource = $loadMoreButton.attr('data-source');
            var dataPlaylist = $loadMoreButton.attr('data-playlist');
            var pageIndex = $loadMoreButton.attr('data-page');
            var pageCount = $loadMoreButton.attr('data-count');
            var itemClass = $loadMoreButton.attr('data-item-class');

            var nextPage = Number(pageIndex) + 1;

            var morejson = $.getJSON(dataSource + "&pageIndex=" + pageIndex + "&pageCount=" + pageCount + "&playlistId=" + dataPlaylist, function (data) {
                var output = "";
                var closed = false;
                var itemCount = 0;

                for (var i in data) {

                    closed = false;

                    //outputCount++;

                    if (itemCount === 0 || itemCount % pageCount === 0) {
                        output += "<div class=\"row\">";
                    }

                    output +=   "<div class=\"" + itemClass + "\">" +
                                    "<div class=\"video item\">" +
                                        "<a href=\"" + data[i].link + "\" class=\"youtube cboxElement\">" +
                                            "<span>" +
                                                "<img src=\"" + data[i].localImage + "\" alt=\"\" class=\"img-responsive\" />" +
                                                "<i class=\"icon-play\"></i>" +
                                            "</span>" +
                                            "<span>" + data[i].duration + "</span>" +
                                            "<h4 class=\"h5\">" + data[i].title + "</h4>" +
                                        "</a>" +
                                    "</div>" +
                                "</div>";

                    if ((itemCount + 1) % pageCount === 0) {
                        output += "</div>";
                        closed = true;
                    }
                    itemCount++;
                }

                if (closed === false) {
                    output += "</div>";
                }

                if (data.length !== 0) {

                    $loadBtnContainer.before(output);

                    // Check if next page has any data
                    $.getJSON(dataSource + "&pageIndex=" + nextPage + "&pageCount=" + pageCount + "&playlistId=" + dataPlaylist, function (dataNext) {
                        if (dataNext.length === 0) {
                            $loadBtnContainer.hide();
                        }
                    });

                    if (itemCount < pageCount) {
                        $loadBtnContainer.hide();
                    }
                    else {
                        $loadBtnText.html("Load more");
                    }

                }
                else {
                    $loadBtnContainer.hide();
                }

                $loadMoreButton.attr('data-page', nextPage);
            });
        }


        return {
            init: init
        };
    }());

    // Carousel Video
    var carouselVideo = (function(){

        function init() {
            $('.view-carousel-video').on('click', function (e) {
                var shareUrl = window.location.protocol + "//" + window.location.host + "/?video=" + $(this).data('video-id');
                var html = "<div class=\"carousel-video-overlay-content\">" +
"    <iframe src=\"//player.vimeo.com/video/" + $(this).data('video-id') + "\" width=\"640\" height=\"360\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>" +

"    <h2>" + $(this).data('heading') + "</h2>" +
"    <p>" + $(this).data('description') + "</p>" +
"    <div class=\"social-buttons\">" +
"       <div class=\"social-button\"><a class=\"twitter-share-button\" href=\"https://twitter.com/share\" data-count=\"none\" data-text=\"" + $(this).data('heading') + "\" data-url=\"" + shareUrl + "\">Tweet</a></div>" +
"    </div>" +
"</div>";

                $.colorbox({
                    html:html,
                    onComplete:function() {
                        // Refresh Twitter widgets
                        twttr.widgets.load();

                    }
                });

                e.preventDefault();
            });
        }

        return {
            init: init
        };
    }());


// Global init function
	return {
		init: function () {
			responsiveStates.init();

            resetPasswordRedirect.init();

			cookiePolicy.init();

            loadMore.init();

            carouselVideo.init();

			try{
				$(".cycle-slideshow").cycle('goto',activeVideoSlide);
			}catch(e){}

			// SVG fallback
			if (!Modernizr.svg) {
				$('img[src*="svg"]').attr('src', function () {
					return $(this).attr('src').replace('.svg', '.png');
				});
			}

			$('.members-form').bootstrapValidator({
                threshold: 8,
                trigger: 'blur'
            });

            $('.login-form').bootstrapValidator({})
                .on('success.field.bv', function(e, data) {
					$('#ssv-'+data.field).hide();
                	$('#ssv-'+data.field).hide();
				})
                .on('error.field.bv', function(e, data) {
					$('#ssv-'+data.field).hide();
                	$('#ssv-'+data.field).hide();
				});


            $(".toggle-handle").each(function(){
            	var target;
            	var el = $(this);
            	if(target = el.data('target')){

            		if(el.data('hide')){
	            		$(target).hide();
	            	}

            		$(this).click(function(){
            			$(target).slideToggle();
            			return false;
            		});
            	}
            });

		}
	};

}());

// WURFL Device Detection and rewrite links for mobile

if(WURFL.is_mobile){

    var els = document.getElementsByTagName('a'),
        len = els.length;

    while( len-- ) {
        els[len].hostname = els[len].hostname.replace('www.cpfc.co.uk','mobile.cpfc.co.uk');
    }
}


// Show/Hide More Info Panel on Memberships Page

$( '.more-info' ).click(function() {

    var element = $("#"+$(this).data('target'));
    var p = $(this);
    var img = p.parent().parent().find("img");

    if (element.hasClass('hidden') || element.css('display') == 'none') {
    	if(element.hasClass('hidden')){
	    	element.hide();
    	    element.removeClass('hidden');
    	}
    	element.slideDown();
        p.html('Less info');
        img.attr('src',img.attr('src').replace(/-up\./,'-down.'));
	}
    else {
    	element.slideUp();
	    p.html('More info');
        img.attr('src',img.attr('src').replace(/-down\./,'-up.'));
    }

});

$(document).ready(cpfc.init);
