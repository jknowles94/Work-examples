var website = (function () {
	// Responsive state management
	var responsiveStates = (function() {

		function init() {
			ssm.addStates([
				{
					id: 'xs',
					maxWidth: 767,
					colorbox: false,
					onEnter: function(){
						console.log('Enter mobile');
					},
					onLeave: function(){
						console.log('Leave mobile');
					}
				},
				{
					id: 'sm',
					minWidth: 768,
					maxWidth: 991,
					colorbox: false
				},
				{
					id: 'md',
					minWidth: 992,
					maxWidth: 1199
				},
				{
					id: 'lg',
					minWidth: 1200
				}
			]);
			ssm.ready();
		}

		function current(state) {
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

        return {
            init: init,
            current: current
        };
	}());

	// Form validation, add class of .form-vaildate around the form to validate
	var siteForms = (function () {
		var $forms = $('.form-validate');

		function init() {
			$forms.bootstrapValidator({
				excluded: [':disabled'],
				feedbackIcons: {
				valid: 'icon-ok',
				invalid: 'icon-cancel',
				validating: 'icon-loading'
				},
				fields: {
					donationRepetition: {
						validators: {
							notEmpty: {
								message: 'You must select a payment type'
							}

						}
					},
					postcode: {
						validators: {
		                    zipCode: {
		                        country: 'GB',
		                        message: 'The value is not valid UK postcode'
		                    }
		                }
					}
				}
			});

			$('#donationRepetition-month').click(function(){
				$('.form-control-feedback').css({
					top: 0
				});
			});

			$('#donationRepetition-year').click(function(){
				$('.form-control-feedback').css({
					top: '36%'
				});
			});

			$('#donationRepetition-oneoff').click(function(){
				$('.form-control-feedback').css({
					top: '70%'
				});
			});
				
		}

		return {
			init: function () {
				if ($forms.length) {
					init();
				}
			}
		};
	}());

	//Slidebar
	var Slidebar = (function () {

		function init() {
			$.slidebars();
		}

		return {
			init:init
		};
	}());

	// Global init function
	return {
		init: function () {
			responsiveStates.init();

			siteForms.init();

			Slidebar.init();

			// SVG fallback
			if (!Modernizr.svg) {
				$('img[src*="svg"]').attr('src', function () {
					return $(this).attr('src').replace('.svg', '.png');
				});
			}

			$('input, textarea').placeholder();

		}
	};

}());

$(document).ready(website.init);
