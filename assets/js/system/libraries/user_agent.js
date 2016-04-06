/* Mucho thanks to Peter-Paul Koch @ http://www.quirksmode.org/js/detect.html for this */

BDR.Library('user_agent',{
	is_browser: function () {
		var search = this._searchString(this._dataBrowser);
		return (search && search.browser) ? true: false;
	},
	
	is_mobile: function () {
		var search = this._searchString(this._dataBrowser);
		return (search && search.mobile) ? true: false;
	},
	
	is_robot: function () {
		var search = this._searchString(this._dataBrowser);
		return (search && search.robot) ? true: false;
	},
	
	is_referral: function () {
		return (this.referrer() && this.referrer().length) ? true : false;
	},
	
	browser: function () {
		var search = this._searchString(this._dataBrowser);
		return (search && search.browser) ? search.identity : "an unknown browser";
	},
	
	mobile: function () {
		var search = this._searchString(this._dataBrowser);
		return (search && search.mobile) ? search.identity : "an unknown mobile";
	},
	
	robot: function () {
		var search = this._searchString(this._dataBrowser);
		return (search && search.robot) ? search.identity : "an unknown robot";
	},
	
	version: function () {
		//setup vars
		this._searchString(this._dataBrowser);
		//find version
		return (this._searchVersion(navigator.userAgent) || this._searchVersion(navigator.appVersion) || "an unknown version");
	},
	
	platform: function () {
		//setup vars
		this._searchString(this._dataBrowser);
		//find version
		return (this._searchString(this._dataOS).identity || "an unknown OS");
	},
	
	referrer: function () {
		return document.referrer;
	},
	
	agent_string: function () {
		return navigator.userAgent || navigator.vendor || window.opera;
	},
	
	accept_lang: function (test_lg) {
		var full_lang = navigator.language || navigator.userLanguage || navigator.systemLanguage || navigator.browserLanguage;
		var lang = full_lang.substring(0,2);
		
		return (full_lang.toLowerCase().indexOf(test_lg.toLowerCase()) > -1);
	},
	
	accept_charset: function () {
		return true;
	},
	
	_searchString: function (data) {
		for (var i=0;i<data.length;i++)	{
			var dataString = data[i].string;
			var dataProp = data[i].prop;
			this._versionSearchString = data[i].versionSearch || data[i].identity;
			if (dataString) {
				if (dataString.indexOf(data[i].subString) != -1)
					return data[i];
			}
			else if (dataProp)
				return data[i];
		}
	},
	
	_searchVersion: function (dataString) {
		var index = dataString.indexOf(this._versionSearchString);
		if (index == -1) return;
		return parseFloat(dataString.substring(index+this._versionSearchString.length+1));
	},
	
	_dataBrowser: [
		{ 	
			string: navigator.userAgent,
			subString: "OmniWeb",
			versionSearch: "OmniWeb/",
			identity: "OmniWeb",
			browser: true,
			mobile: false,
			robot: false
		},
		{
			string: navigator.vendor,
			subString: "Apple",
			identity: "Safari",
			browser: true,
			mobile: false,
			robot: false
		},
		{
			prop: window.opera,
			identity: "Opera",
			browser: true,
			mobile: false,
			robot: false
		},
		{
			string: navigator.vendor,
			subString: "iCab",
			identity: "iCab",
			browser: true,
			mobile: false,
			robot: false
		},
		{
			string: navigator.vendor,
			subString: "KDE",
			identity: "Konqueror",
			browser: true,
			mobile: false,
			robot: false
		},
		{
			string: navigator.userAgent,
			subString: "Firefox",
			identity: "Firefox",
			browser: true,
			mobile: false,
			robot: false
		},
		{
			string: navigator.vendor,
			subString: "Camino",
			identity: "Camino",
			browser: true,
			mobile: false,
			robot: false
		},
		{		// for newer Netscapes (6+)
			string: navigator.userAgent,
			subString: "Netscape",
			identity: "Netscape",
			browser: true,
			mobile: false,
			robot: false
		},
		{
			string: navigator.userAgent,
			subString: "MSIE",
			identity: "Explorer",
			versionSearch: "MSIE",
			browser: true,
			mobile: false,
			robot: false
		},
		{
			string: navigator.userAgent,
			subString: "Gecko",
			identity: "Mozilla",
			versionSearch: "rv",
			browser: true,
			mobile: false,
			robot: false
		},
		{ 		// for older Netscapes (4-)
			string: navigator.userAgent,
			subString: "Mozilla",
			identity: "Netscape",
			versionSearch: "Mozilla",
			browser: true,
			mobile: false,
			robot: false
		}
	],
	
	_dataOS : [
		{
			string: navigator.platform,
			subString: "Win",
			identity: "Windows"
		},
		{
			string: navigator.platform,
			subString: "Mac",
			identity: "Mac"
		},
		{
			string: navigator.platform,
			subString: "Linux",
			identity: "Linux"
		}
	]
});
