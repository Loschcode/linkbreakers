/**
 * Chrono Js 
 * A simple chrono in javascript language 
 * V0.1 - Jeremie Ges
 *
 * The main function is chrono() 
 * init_chrono : Init the timer with X seconds
 */

 var interval = null;
 var started = false;
 var stoped = false;
 var chrono_element = null;
 var chrono_val = null;


 function chrono(init_chrono) {

 	generate_html();

 	chrono_element = document.getElementById('chrono');

 	if (init_chrono != null) {
 		chrono_val = init_chrono;
 		chrono_element.innerHTML = chrono_val;
 	}

 	start();
 }

function generate_html() {
	var js_function_element = document.getElementById('js_function');
	js_function_element.innerHTML = '<span id="chrono">timer</span>';
}

 function start() {
 	if (!started) interval = setInterval("increase()", 1000);
 	if (!started) started = true;
 }

 function increase() {
 	time = parseInt(chrono_element.textContent);
 	time = time+1;
 	chrono_element.innerHTML = time;
 }

