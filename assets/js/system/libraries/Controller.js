STRIKE.Library('Controller',{
	controller_name: "_none_",
		
	initialize: function () {
		var obj = this;
		//create load scope for loader library
		this.load = {
			_this: null,
			_addMethod: function (name,method) {
				var obj = this._this;
				//wrap function effectively re-scoping it
				this[name] = function() { return method.apply(obj,arguments); };
			}
		};
		this.load._this = this;
		//load functions into scope
		var loader = STRIKE._getLibrary("Loader");
		for (var method in loader)
			this.load._addMethod(method,loader[method]);
		//remove internal functions
		this.load._addMethod = null;
	},

	
	_setControllerName: function (name) {
		this.controller_name = name;
	}
});
