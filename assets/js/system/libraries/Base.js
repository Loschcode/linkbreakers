STRIKE.Library('Base',{
	init: function () {},
	
	initialize: function () {},
	
	/* PRIVATE METHODS */

	__addMethod: function (name,method) {
		//do no overwrite method that already exists!
		if (typeof this[name] != "function") {
			//wrap function effectively re-scoping it
			var obj = this;
			obj[name] = function() { return method.apply(obj,arguments); };
		}
	},
	
	/* ONLY USED IF NO JS LIBRARY IS SUPPLIED AND OVERWRITES */
	
	__captureHistory: function () {
		//check for RSH
		if (window.dhtmlHistory) {
			//set vars
			STRIKE._History = [];
			STRIKE._HistoryIndex = -1;
			STRIKE.History = window.dhtmlHistory;
			STRIKE.History.create({
				toJSON: function (obj) {
					var json = {};
					for (var prop in obj)
						json[prop] = obj[prop];
					return json;
				},
				fromJSON: function (s) {
					try {return STRIKE._getLibrary('Base').__parseJson(s);}
					catch (e) {return s;}
				}
			});
			STRIKE.History.initialize();
			STRIKE.History.addListener(function(index){
				if (index > -1) {
					var history = STRIKE._History[index];
					//ignore History
					var temp = STRIKE.History;
					STRIKE.History = null;
					//run the IO
					STRIKE._getLibrary('Base').__serverIO(history.url,history.request_options);
					//restore History
					STRIKE.History = temp;
				}
			});
		}
	},
	
	__serverIO: function (url,options) {
		var request_options = {
			asynchronous: true,
			method: "GET",
			form: null,
			evaljs: false,
			oncreate: function(){},
			oncomplete: function(){}
		};
		var req =  (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	    if (req) {
	    	if (options) { 
				for (method in options)
					request_options[method.toLowerCase()] = options[method];
			} 
			//callback (bound)
			request_options.oncreate();
			//make request
			onrsc = function(){
				if (req.readyState == 4) {
					if (req.status == 200) {

						STRIKE.set_debug('debug', 'Try to load ' + url, true);

						//callback
						if (request_options.evaljs) 
							request_options.oncomplete(eval(req.responseText));
						else if (req.responseXML && req.responseXML.documentElement) 
							request_options.oncomplete(req.responseXML);
						else 
							request_options.oncomplete(req.responseText);
					}
					else {
						request_options.onerror(req);
					}
		        }
			};
			if (request_options.asynchronous)
				req.onreadystatechange = onrsc;
			if (request_options.form) {
			    //convert form to string
			    var poststr = "";
			    for (i=0; i < request_options.form.elements.length; i++) {
			    	var elem = request_options.form.elements[i];
			    	if (elem.tagName.toLowerCase() == "input") {
			            if (elem.type.toLowerCase() == "checkbox")
			               	poststr += elem.name + ((elem.checked) ? "=" + escape(elem.value) : "=") + "&";
			            else if (elem.type.toLowerCase() == "radio") {
			            	if (elem.checked)
			                	poststr += elem.name + "=" + escape(elem.value) + "&";
			            }
			            else if (elem.name)
			            	poststr += elem.name + "=" + escape(elem.value) + "&";
			      	}   
			        else if (elem.tagName.toLowerCase() == "select") {
			        	poststr += elem.name + "=" + escape(elem.options[elem.selectedIndex].value) + "&";
			        }
			   	}
			   	request_options.form = poststr;
				//force post
				request_options.method = "POST";
			}
			//open request
			req.open(request_options.method.toUpperCase(),url,request_options.asynchronous);
			//set headers
			if (request_options.form) {
			    req.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				req.setRequestHeader("Content-length",request_options.form.length);
				req.setRequestHeader("Connection","close");
			}
			//send data
            req.send(request_options.form);
            //synchronous calls for FF need this
           	if (!request_options.asynchronous) {
           		onrsc();
           	}
        }
	},
	
	__parseJson: function (json_str) {
		return eval("("+json_str+")");
	},
	
	__parseTemplate: function (html,data) {
		//simple string replace of vars
		if (data) {
			for (var variable in data) {
				html = html.replace(new RegExp('%'+variable+'%','gi'),data[variable]);
			}
		}
		return html;
	}
});
