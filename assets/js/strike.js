/**
 * Strike.js
 *
 * An open source application development framework for javascript based on the core of JsIgniter
 *
 * @package		Strike
 * @author		Jeremie Ges
 * @copyright	2013
 */

// ------------------------------------------------------------------------

STRIKE = {


	// Strike initialized ?
	initialized: false,
	docLoaded: false,

	// Show debug console ?
	is_debug: false,
	counter: 0,
	has_js_library: "",
	js_library: null,

	// Path of system 
	system_url: "system/",	

	// Base url (ex: http://localhost:8888)
	base_url: "",

	// No idea about this
	index_file: "",
	stage: null,

	// Here we will stock all config datas
	Config: null,

	// Stock here controllers loaded
	Controllers: {},

	// Stock here models loaded
	Models: {},

	// Stock here views loaded
	Views: {},

	// Stock here plugins loaded
	Plugins: {},

	// Stock here helpers loaded
	Helpers: {},

	// Stock here libraries loaded
	Libraries: {},
	History: null,
	_History: [],
	_HistoryIndex: -1,


	// ------------------------------------------------------------------------

	initialize: function () {

		// Set system url based on strike.js url
		var StrikeRegEx = /(^|[\/\\])strike\.js(\?|$)/;
		var scripts = document.getElementsByTagName("script");
		for (var i = 0; i < scripts.length; i++) {
			var src = "";
			if (src = scripts[i].getAttribute("src")) {
				if (src.match(StrikeRegEx)) {
					this.system_url = src.replace("strike.js","")+"system/";
					break;
				}
			}
		}

		// No config ? Ok require the config.js file
		if (!this.Config)
			document.write('<script language="javascript" type="text/javascript" src="' + this.system_url + 'application/config/config.js"></script>');
		
		// Require the base class
		if (!this.Libraries['Base'])
			document.write('<script language="javascript" type="text/javascript" src="'+this.system_url+'libraries/Base.js"></script>');
		
		// jQuery not exist ? Ok good bye.
		if (typeof jQuery == 'undefined') {
			return false;
		}
		
		// Retrieve javascript library and add to base class (replacing Base functions SERVER IO and cie)
		document.write('<script language="javascript" type="text/javascript" src="'+this.system_url+'libraries/_jQuery.js"></script>');
		
		// Guess base url
		STRIKE.base_url = location.protocol + "//" + location.hostname + (location.port && ":" + location.port) + "/";
			
		// Get the route current url
		STRIKE.route_current_url = location.href;

		// Guess the route uri
		STRIKE.route_uri = STRIKE.route_current_url.split(STRIKE.base_url).join('') + '/';

		// More setup onload
		this._addOnLoad(function(){

			// Set vars of config.js
			for (_var in STRIKE.Config) 
				STRIKE[_var] = STRIKE.Config[_var];

			// Manual system url
			if (STRIKE.system_url == "system/" && STRIKE.base_url.length)
				STRIKE.system_url = STRIKE.base_url+"/"+STRIKE.system_url;


			// Give stage if no specified
			if (!STRIKE.stage && document.getElementsByTagName("body").length) STRIKE.stage = document.getElementsByTagName("body")[0];
			
			// Retrieve javascript library and add to base class (replacing Base functions)
			STRIKE.Libraries['Base'] = STRIKE._combine({init:function(){},initialize:function(){}},STRIKE._combine(STRIKE._combine({},STRIKE._getLibrary("_jQuery")),STRIKE._getLibrary('Base')));
			
			//start history handling
			if (!STRIKE.History)
				STRIKE._getLibrary('Base').__captureHistory();



		});

		// Ok dude the core is now loaded !
		this.initialized = true;
	},

	// ------------------------------------------------------------------------

	require: function (file) {
		this._getLibrary('Base').__serverIO(this.system_url+file+'.js',{asynchronous:false,method:((this.is_debug)?"POST":"GET"),evalJS:true});
	},
	
	/****** INITIALIZERS ******/
	
	Controller: function (name,methods) { 
		//store abstract
		this.Controllers[name] = this._combine(methods,this._getLibrary("Controller"));
		//initialize
		this.Controllers[name]._setControllerName(name);
		this.Controllers[name].initialize();
		this.dispatch(name,'init');
	},
	Model: function (name,methods) {
		//store abstract
		this.Models[name] = this._combine(methods,this._getLibrary("Model"));
		//initialize
		this.Models[name].initialize();
		this.Models[name].init();
	},
	Plugin: function (name,methods) {
		//store abstract
		this.Plugins[name] = this._combine(methods,this._getLibrary("Plugin"));
		//initialize
		this.Plugins[name].initialize();
		this.Plugins[name].init();
	},
	Helper: function (name,methods) {
		this.Helpers[name] = methods;
	},
	Library: function (name,methods) {
		//store abstract
		if (name != "Base")
			this.Libraries[name] = this._combine(methods,this._getLibrary('Base'));
		else
			this.Libraries[name] = methods;
		//initialize
		this.Libraries[name].initialize();
		this.Libraries[name].init();
	},
	
	/******* PRIVATE *******/
	
	_combine: function (to,from) {
		for (var method in from) {
			if (!to[method])
				to[method] = from[method];
		}
		return to;
	},
	
	_addOnLoad: function (method) {
		if (!this.docLoaded) {
			var old_load = (typeof window.onload == "function") ? window.onload : function(){};
			window.onload = function(){
				STRIKE.docLoaded = true;
				old_load();
				method();
			}
		}
		else {
			method();
		}
	},
	
	_getController: function (name,force) {
		if (!this.Controllers[name] || force) {
			this.require("application/controllers/"+name);
		}
		return this.Controllers[name];
	},
	
	_getModel: function (name) {
		if (!this.Models[name]) {
			this.require("application/models/"+name);
		}
		
		return this.Models[name];
	},
	
	_getView: function (name) {
		if (!this.Views[name]) {
			var views = this.Views;

			// No conflict if developper add .html at the end of name view
			name = name.split('.html').join('');
			this._getLibrary('Base').__serverIO(this.system_url+'application/views/'+name+'.html',{
				asynchronous:false,
				method:"POST",
				onComplete: function (responseText) {
					views[name] = responseText;
				},
				error: function() {
					console.log('Unable to load ressource');
				}
			});
		}
		
		return this.Views[name];
	},

	_getHelper: function (name) {
		if (!this.Helpers[name]) {
			this.require("helpers/"+name+"_helper");
			if (!this.Helpers[name]) //check application dir for custom helpers
				this.require("application/helpers/"+name+"_helper");
		}

		return this.Helpers[name];
	},

	_getLibrary: function (name) {
		if (!this.Libraries[name]) {
			this.require("libraries/"+name);
			if (!this.Libraries[name]) //check application dir for custom helpers
				this.require("application/libraries/"+name);
		}

		return this.Libraries[name];
	},
	
	/****** DEBUG ******/

	/**
	 * Set debug
	 *
	 * Add new line in the debug
	 *
	 * @access	public
	 * @param 	string 	Label of debug (e.g debug, error, core, info)
	 * @param 	string 	Msg to log
	 * @param 	bool  	Do you want display [strike] before label ?
	 * @return	void
	 */
	set_debug: function(label, msg, strike) {

		// Display log debug ?
		if (! this.Config.show_debug)
			return false;

		// Create shortcut var
		var show_label = this.Config.show_label_debug;
		var count = show_label.length;

		// Security
		if (typeof show_label == 'null' || typeof show_label == 'undefined' || show_label.length <= 0) {

			return false;

		} 


		// Check if developer want display this label 
		for (var i = 0; i < count; i++) {

			if (show_label[i] == label) {

				// Exist 
				var tmp_log = '';

				// Prefix STRIKE if strike var equal true
				if (typeof strike != 'undefined' && strike) {

					tmp_log += '[STRIKE] ';

				}

				tmp_log += '[' + label.toUpperCase() + '] - ' + msg;

				// Output line
				console.log(tmp_log);

			}

		}




	},

	
	
	/****** PUBLIC ******/
	
	trace: function (msg) {
		if (this.is_debug) {
			var console = document.getElementById("__JSIgniter_debug_content_console");
			if (console) {
				var div = document.createElement("div");
				//add trace
				console.insertBefore(div,console.firstChild);
				div.innerHTML = msg;
				//remove hidden traces
				var traces = console.childNodes;
				for (var x=(traces.length-1); x > 22; x--) {
					console.removeChild(traces[x]);
				} 
			}
		}
	},
	
	loadPlugin: function (name) {
		if (!this.Plugins[name]) {
			this.require("application/plugins/"+name);
		}
	},

	loadController: function (name) {
		this._getController(name);
		//debug
		if (this.is_debug) {
			var controllers = document.getElementById("__JSIgniter_debug_content_controllers");
			if (controllers) {
				var div = document.createElement("div");
				//add trace
				controllers.insertBefore(div,controllers.firstChild);
				div.innerHTML = "<a href='javascript:void(0);' onclick='STRIKE._getController(\""+name+"\",true)' style='color:black;float:right;'>Reload</a>"+name;
			}
		}
	},
	
	dispatch: function (controller,event) {
		var args = [];
		var objController = this._getController(controller);	
		if (objController) { 
			if (typeof objController[event] == "function") {
				//get args
				for (var x=2; x < arguments.length; x++)
					args.push(arguments[x]);


				//call function
				objController[event].apply(objController,args);

			} else {

				STRIKE.set_debug('error', 'Unable to exec {controller: ' + controller + '} {function: ' + event + '}', true);

			}
		}
	},

	preload_controllers: function(controllers) {

		if (typeof controllers == 'string') {

			// Load the simple controller and stop execution
			this.dispatch(controllers, 'init');
			return true;

		}

		// We must loop controllers
		if (typeof controllers == 'object') {

			var size = controllers.length;

			for (var i = 0; i < size; i++) {

				this.dispatch(controllers[i], 'init');

			}

			return true;

		}

		return false;

	},

	/**
	 * Route
	 *
	 * Check if match with global pattern (The uri)
	 *
	 * @access	public
	 * @param 	string 	Pattern to test
	 * @return	bool
	 */
	route: function(pattern) {
		
		var pattern_parsed = STRIKE._route_parse(pattern);
		
		var global_pattern = STRIKE.route_uri;

		// rtrim "/"
		if (global_pattern[global_pattern.length-1] === '/') {

			global_pattern = global_pattern.substring(0, global_pattern.length - 1);

		}

		var match = global_pattern.match(new RegExp(pattern_parsed));

		// Check match patterns
		if (match !== null && match[0] == match['input']) {

			return true;

		} else {

			return false;

		}


	},

	/**
	 * Route Parse
	 *
	 * Return pattern parsed with Regex for variables segment
	 *
	 * @access	private
	 * @param 	string 	The pattern to parse
	 * @return	string
	 */
	_route_parse: function(pattern) {

		// Trim "/" at left
		if (pattern[0] === '/') {

			pattern = pattern.substring(1);

		}

		// Trim "/" at right
		if (pattern[pattern.length] === '/') {

			pattern = pattern.substring(0, pattern.length - 1);

		}

		segments = pattern.split('/');

		for (var i = 0; i < segments.length; i++) {


			// Not empty ?
			if (segments[i] !== '') {

				var segment = segments[i];

				if (segment[0] == ':') {

					segments[i] = '[a-zA-Z0-9_-]*';

				}

			}

		}

		var implode = segments.join('/');


		return implode;


	},

	/**
	 * Route segment
	 *
	 * Get value of segment uri
	 *
	 * @access	public
	 * @param 	int 	Position to show
	 * @return	mixed 	False / String
	 */
	route_segment: function(pos) {

		var uri = STRIKE.route_uri;
		var segments = uri.split('/');

		// Get the last value of array
		// -1 because we start count at 0
		var last_value = segments[segments.length-1];

		// Delete last value if empty
		if (last_value === '') {

			segments.splice(segments.length-1, 1);

		}

		// If exists
		if (segments[pos] !== undefined) {

			return segments[pos];

		} else {

			return false;

		}

	}


};

STRIKE.initialize();
