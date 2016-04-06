STRIKE.Library("_jQuery",{
	__serverIO: function (url,options) {
		var request_options = {
			url: url,
			beforeSend: function(){},
			success: function(){}
		};
		//prepare options
		for (var option in options) {
			switch (option.toLowerCase()) {
				case "asynchronous":
					request_options.async = options.asynchronous;
					break;
				case "form":
					request_options.form = options[option];
					break;
				case "method":
					request_options.type = options[option].toUpperCase();
					break;
				case "evaljs":
					request_options.dataType = "script";
					break;
				case "beforesend":
				case "oncreate": 
					request_options.beforeSend = options[option];
					break;
				case "success":
				case "oncomplete": 
					request_options.success = options[option];
					break;
				default:
					request_options[option] = options[option];
					break;
			}
		}
		//make request
		if (request_options.form) { //submit a form
			//callback
			request_options.beforeSend();
			//convert form to JSON
		    var postjson = {};
		    for (i=0; i < request_options.form.elements.length; i++) {
		    	var elem = request_options.form.elements[i];
		    	if (elem.tagName.toLowerCase() == "input") {
		            if (elem.type.toLowerCase() == "checkbox")
		               postjson[elem.name] = (elem.checked) ? elem.value : "";
		            else if (elem.type.toLowerCase() == "radio") {
		            	if (elem.checked)
		                	postjson[elem.name] = elem.value;
		            }
		            else if (elem.name)
		            	postjson[elem.name] = elem.value;
		      	}   
		        else if (elem.tagName.toLowerCase() == "select") {
		        	postjson[elem.name] = elem.options[elem.selectedIndex].value;
		        }
		   	}
		   	request_options.form = postjson;
			//post form
			jQuery.post(url,request_options.form,request_options.success);
		}
		else //normal request
			jQuery.ajax(request_options);
	}
});