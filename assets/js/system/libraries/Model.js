STRIKE.Library('Model',{
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
	
	getInstance: function () {
		//create object
		var __temp = function () {
			this._addMethod = function (name,method) {
				var obj = this;
				//wrap function effectively re-scoping it
				this[name] = function() { return method.apply(obj,arguments); };
			}
		};
		//instantiate
		var temp = new __temp();
		//add methods and properties
		for (method in this) {
			if (typeof this[method] == "function" && method != "init" && method != "initialize" && method != "getInstance")
				temp._addMethod(method,this[method]);
			else
				temp[method] = this[method];
		}
		//return instance
		return (temp);
	}
});
