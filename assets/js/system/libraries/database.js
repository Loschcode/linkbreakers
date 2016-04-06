JI.Library('database',{
	query: function (sql,argArray) {
		
	},
	
	simple_query: function (sql) {
		
	},
	
	escape: function () {
		
	},
	
	call_function: function (method) {
		var args = [];
		//create args to pass into method
		for (var x=1; x < arguments.length; x++)
			args.push(arguments[x]);
		//call method with args
		this[method].call(args);
	}	
	
	/* HELPER */
	
	insert_id: function () {
		
	},
	
	affected_rows: function () {
		
	},
	
	count_all: function () {
		
	},
	
	platform: function () {
		
	},
	
	version: function () {
		
	},
	
	last_query: function () {
		
	},
	
	insert_string: function () {
		
	},
	
	update_string: function () {
		
	},
	
	/* ACTIVE RECORD */
	
	get: function () {
		
	},
	
	get_where: function () {
		
	},
	
	select: function () {
		
		return this;
	},
	
	select_max: function () {
		
		return this;
	},
	
	select_min: function () {
		
		return this;
	},
	
	select_avg: function () {
		
		return this;
	},
	
	select_sum: function () {
		
		return this;
	},
	
	from: function () {
		
		return this;
	},
	
	join: function () {
		
	},
	
	where: function () {
		
		return this;
	},
	
	or_where: function () {
		
		return this;
	},
	
	or_where_in: function () {
		
		return this;
	},
	
	where_not_in: function () {
		
		return this;
	},
	
	or_where_not_in: function () {
		
		return this;
	},
	
	like: function () {
		
		return this;
	},
	
	or_like: function () {
		
		return this;
	},
	
	not_like: function () {
		
		return this;
	},
	
	or_not_like: function () {
		
		return this;
	},
	
	group_by: function () {
		
		return this;
	},
	
	distinct: function () {
		
		return this;
	},
	
	having: function () {
		
		return this;
	},
	
	or_having: function () {
		
		return this;
	},
	
	order_by: function () {
		
		return this;
	},
	
	limit: function () {
		
		return this;
	},
	
	count_all_results: function () {
		
	},
	
	count_all: function () {
		
	},
	
	insert: function () {
		
	},
	
	set: function () {
		
	},
	
	update: function () {
		
	},
	
	_delete: function () {
		
	},
	
	empty_table: function () {
		
	},
	
	truncate: function () {
		
	},
	
	/* ACTIVE RECORD CACHING */
	
	start_cache: function () {
	
	},
	
	stop_cache: function () {
	
	},
	
	flush_cache: function () {
	
	},
	
	/* TRANSACTIONS */
	
	trans_start: function (rollback) {
	
	},
	
	trans_complete: function () {
	
	},
	
	trans_strict: function () {
	
	},
	
	trans_status: function () {
	
	},
	
	trans_off: function () {
	
	},
	
	trans_begin: function () {
	
	},
	
	trans_rollback: function () {
	
	},
	
	trans_commit: function () {
	
	},
	
	/* TABLE DATA */
	
	list_tables: function () {
	
	},
	
	table_exists: function () {
	
	},
	
	list_fields: function (table) {
	
	},
	
	field_exists: function (field,table) {
	
	},
	
	field_data: function (field,table) {
	
	},
	
	/* QUERY RESULT OBJECT */
	
	_query: {
		index:0,
		
		result: function () {
			
		},
		
		result_array: function () {
			
		},
		
		row: function (index) {
			
		},
		
		row_array: function (index) {
			
		},
		
		first_row: function (type) {
			switch (type.toLowerCase()) {
				case 'array':
				
					break
				default:
					
					break;
			}
		},
		
		last_row: function (type) {
			switch (type.toLowerCase()) {
				case 'array':
				
					break
				default:
					
					break;
			}
		},
		
		next_row: function (type) {
			switch (type.toLowerCase()) {
				case 'array':
				
					break
				default:
					
					break;
			}
		},
		
		previous_row: function (type) {
			switch (type.toLowerCase()) {
				case 'array':
				
					break
				default:
					
					break;
			}
		},
		
		num_rows: function () {
			
		},
		
		num_fields: function () {
			
		},
		
		free_result: function () {
			
		}
	}
});
