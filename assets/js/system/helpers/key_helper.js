// Not all datas added, so we must rewrite this shit
// <!> Used by autocomplete controller
STRIKE.Helper("key",{

	ctrl_combination: function(key) {

		/*e = jQuery.Event("keydown");        
		e.which = 50;
		e.ctrlKey = true;
		$("input").trigger(e);

		console.log('done');*/

    },


	convert_key: function (string) {

		var key = false;
		var key_datas = [];

		// Letters
		key_datas['a'] = 65;
		key_datas['b'] = 66;
		key_datas['c'] = 67;
		key_datas['d'] = 68;
		key_datas['e'] = 69;
		key_datas['f'] = 70;
		key_datas['g'] = 71;
		key_datas['h'] = 72;
		key_datas['i'] = 73;
		key_datas['j'] = 74;
		key_datas['k'] = 75;
		key_datas['l'] = 76;
		key_datas['m'] = 77;
		key_datas['n'] = 78;
		key_datas['o'] = 79;
		key_datas['p'] = 80;
		key_datas['q'] = 81;
		key_datas['r'] = 82;
		key_datas['s'] = 83;
		key_datas['t'] = 84;
		key_datas['u'] = 85;
		key_datas['v'] = 86;
		key_datas['w'] = 87;
		key_datas['x'] = 88;
		key_datas['y'] = 89;
		key_datas['z'] = 90;

		// Controls
		key_datas['backspace'] = 8;
		key_datas['enter'] = 13;
		key_datas['space'] = 32;
		key_datas['ctrl'] = 17;
		key_datas['cmd'] = 91; // Mac (apple cmd touch)
		key_datas['alt'] = 18;
		key_datas['maj'] = 20;

		key_datas['['] = 219;
		key_datas['|'] = 220;
		key_datas[']'] = 221;
		key_datas['('] = 53;
		key_datas[')'] = 189;

		key_datas['arrow_down'] = 40;
		key_datas['arrow_up'] = 38;

		if (key_datas[string]) {
			return key_datas[string];
		} 

		return false;
	}
});
