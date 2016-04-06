
/**
 * Cookie Class from Strike Framework
 *
 * Here we set/unset our cookies
 * I found it on Internet so it has to be good !
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

STRIKE.Library('cookie',{

	get_val: function (offset) {

		var endstr = document.cookie.indexOf (";", offset);

		if (endstr == -1)
			endstr = document.cookie.length;
		return unescape(document.cookie.substring(offset, endstr));


	},

	get: function (name) {

		var arg = name + "=";
		var alen = arg.length;
		var clen = document.cookie.length;
		var i = 0;
		while (i < clen) {
			var j = i + alen;
			if (document.cookie.substring(i, j) == arg)
				return this.get_val(j);
			i = document.cookie.indexOf(" ", i) + 1;
			if (i === 0) break;
		}

		return null;
	},

	set: function (name, value) {

		document.cookie = name + "=" + value + "; path=/";

	},

	delete: function (name) {

		var exp = new Date();
		exp.setTime (exp.getTime() - 1);
		var cval = this.get(name);
		document.cookie = name + "=" + cval + "; expires=" + exp.toGMTString();
	}


});
