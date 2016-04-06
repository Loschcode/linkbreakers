/**
 * Timer Js 
 * A simple timer in javascript language 
 * V0.1 - Jeremie Ges
 *
 * The main function is timer() 
 * init_timer : Init the timer with X seconds
 */

 var started = false;
 var interval = null;
 var description = null;
 var redirect_action = false;

 function timer(init_timer, init_type, init_description) {

	// We will go generate pretty html
	generate_html();

	// Put msg in global var
	if(init_type == null) type = 'MSG';
	if (init_description == null) description = ' ';
	else description = init_description;
	if (init_type == 'URL') redirect_action = true;

	var display_timer = document.getElementById('timer');

	// Set value of init timer
	display_timer.innerHTML = init_timer; 
	start();
	
	// Set actions span
	//document.getElementById('actions').innerHTML = ' <a href="#" onclick="start(); return false;">Start Timer</a>';

}

function generate_html() {
	var js_function_element = document.getElementById('js_function');
	js_function_element.innerHTML = '<span id="timer">timer</span>';
}

function start() {
	if (!started) interval = setInterval("decrease()", 1000);
	if (!started) started = true;

}

function decrease() {


	var timer_element = document.getElementById('timer');
	var time = timer_element.textContent-1;


	if (time >= 0) timer_element.innerHTML = time;


		// Time is down, end() function called
		if (time <= 0) end();

	}

	function redirect(url) {
		document.location.href= url;
	}

	function end() {
		console.log(description);
		clearInterval(interval);
		console.log('end called');
		console.log(redirect_action);
		if (description != null && !redirect_action) document.getElementById('timer').innerHTML = description;
		if (description != null && redirect_action) redirect(description);
	}
