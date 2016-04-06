STRIKE.Helper("html",{
	br: function (num) {
		var brs = "";
		
		for (var x=0; x < num; x++)
			brs += "<br/>";
			
		return brs;
	},
	
	heading: function (text,level) {
		if (!level) level = 1;
		if (level > 3) level = 3;
		return "<h"+level+">"+text+"</h"+level+">";
	},
	
	img: function (src,attribs) {
		atts = "";
		//prepare attributes
		for (attrib in attribs) {
			atts += ' '+attrib+'="'+attribs[attrib]+'"';
		}
		
		return '<img'+atts+' src="'+src+'" />'
	},
	
	link_tag: function (src,rel,type) {
		if (!rel) rel = "stylesheet";
		if (!type) type = "text/css";
		return '<link href="'+src+'" rel="'+rel+'" type="'+type+'" />';
	},
	
	nbs: function (num) {
		var nbss = "";
		
		for (var x=0; x < num; x++)
			nbss += "&nbsp;";
			
		return nbss;
	},
	
	ol: function (list,attribs) {
		atts = "";
		lis = "";
		//prepare attributes
		for (attrib in attribs) {
			atts += ' '+attrib+'="'+attribs[attrib]+'"';
		}
		//prepare <li>s
		for (var x=0; x < list.length; x++) {
			lis += "<li>"+list[x]+"</li>";
		}
		
		return "<ol"+atts+">"+lis+"</ol>";
	},
	
	ul: function (list,attribs) {
		atts = "";
		lis = "";
		//prepare attributes
		for (attrib in attribs) {
			atts += ' '+attrib+'="'+attribs[attrib]+'"';
		}
		//prepare <li>s
		for (var x=0; x < list.length; x++) {
			lis += "<li>"+list[x]+"</li>";
		}
		
		return "<ul"+atts+">"+lis+"</ul>";
	}
});
