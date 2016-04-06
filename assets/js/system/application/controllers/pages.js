STRIKE.Controller('pages',{

	init: function () {
		
		console.log('init');

	},	

	change_pages: function(selector) {

		var speed = 200;
		var _strike = this;

		var data_href = selector.attr('data-transition-href');

		console.log(data_href);
		
		if (typeof data_href != 'undefined') {

			$('div[id^=block-]').fadeOut(speed, function() {
				
				_strike.redirect(data_href);

			});

		}


	}



});
