STRIKE.Library('session',{
	_data: {},
	
	sess_destroy: function () {
		this._data = {};
	},
	
	userdata: function (key) {
		return this._data[key];
	},
	
	set_userdata: function (key,value) {
		this._data[key] = value;
	},
	
	unset_userdata: function (key) {
		//is it enough to just set the key to null?
		this._data[key] = null;
	},
	
	/* unlike CI flashdata, these flashdata functions relate to 
	storing data using the swf javascript API for the ability
	to store the data cross page-refreshes */
	
	flashdata: function (key) {
		return this._data[key];
	},
	
	set_flashdata: function (key,value) {
		this._data[key] = value;
	},
	
	unset_flashdata: function (key) {
		//is it enough to just set the key to null?
		this._data[key] = null;
	}
});
