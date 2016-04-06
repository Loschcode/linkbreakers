STRIKE.Library('Loader',{
	controller: function (name) {
		//load controller into its own scope with _controller appended to the name of the scope
		this[name+'_controller'] = STRIKE._getController(name);
	},
	
	model: function (name) {
		//load model into its own scope with _model appended to the name of the scope
		this[name] = STRIKE._getModel(name);
	},
	
	view: function (name,data,stage) {
		//use templating system to render html
		var html = this.__parseTemplate(STRIKE._getView(name),data);
		//dont do this: if (!stage) stage = STRIKE.stage;
		if (stage)
			stage.innerHTML = html;
		else
			return html;
	},
	
	helper: function () {
		
		// Count arguments of this method
		count_arguments = arguments.length;

		// Loop all 
		for (var i = 0; i < count_arguments; i++) {

			// Get the name
			var name = arguments[i];

			// Get helper by name
			var helper = STRIKE._getHelper(name);

			// Magic !
			for (var method in helper) { 
				if (typeof helper[method] == "function") {
					//_addMethod found in BaseClass
					if (method.toLowerCase() == "init") {
						//initialize helper
						this.__addMethod("__temp",helper[method]);
						this.__temp();	
					}
					else
						this.__addMethod(method,helper[method]);
				}
			}

		}
	},
	
	library: function (name, new_name) {

		if (new_name) {

			//load library into its own scope
			this[new_name] = STRIKE._getLibrary(name);

		} else {

			this[name] = STRIKE._getLibrary(name);

		}

	},
	
	plugin: function (name) {
		//load library into its own scope
		STRIKE.loadPlugin(name);
	}
});
