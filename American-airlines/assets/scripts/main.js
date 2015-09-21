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
				invalid: 'icon-attention-alt',
				validating: 'icon-loading'
				},
				live:'disabled',
				fields: {
		            postcode: {
		                validators: {
		                    zipCode: {
		                        country: 'GB',
		                        message: 'Invalid Postcode'
		                    }
		                }
		            }
		        }

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


// Global init function
	return {
		init: function () {
			responsiveStates.init();

			siteForms.init();

			// SVG fallback
			if (!Modernizr.svg) {
				$('img[src*="svg"]').attr('src', function () {
					return $(this).attr('src').replace('.svg', '.png');
				});
			}

			if($('.form-control-feedback').hasClass('icon-attention-alt')){
				$('.required').addClass('hidden');
			}

			$('input').placeholder();

		}

	};

}());

$(document).ready(website.init);
