STRIKE.Library('language',{
	_loadedURL: "",
	_lang: {},
		
	load: function (url,language) {
		var obj = this;
		//make sure it hasnt already been loaded
		if (this._loadedURL == url.toLowerCase())
			return;
		//load it
		var obj = this;
		this.__serverIO(url,{
			onComplete: function (responseText) {
				var keys = obj.__parseJson(responseText);
				//load keys into _lang
				for (key in keys) {
					obj._lang[key.toLowerCase()] = keys[key];
				}
				//add to loaded
				obj._loadedURL = url.toLowerCase();
			}
		});
	},
	
	line: function (key) {
		return this._lang[key.toLowerCase()];
	}
});
