STRIKE.Plugin('Sample',{
	beforeRedirect: function () {
		alert('about to go...');
	},	

	afterRedirect: function () {
		alert('phew!');
	}
});
