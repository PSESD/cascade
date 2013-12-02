$(document).on('refresh.cascade-api', '.refreshable', function(e) {
	var settings = {};
	var instructions = {};
	if (typeof $('body').data('refreshable') === 'object' ) {
		settings = jQuery.extend(true, settings, $('body').data('refreshable'));
	}
	if (typeof settings.baseInstructions === 'object') {
		instructions = jQuery.extend(true, instructions, settings.baseInstructions);
	}
	delete settings.baseInstructions;
	if (typeof $(this).data('instructions') === 'object' ) {
		instructions = jQuery.extend(true, instructions, $(this).data('instructions'));
	}
	
	if (settings.data === undefined) {
		settings.data = {};
	}
	settings.dataType = 'json';
	settings.type = 'POST';
	settings.context = $(this);
	settings.success = function(r, textStatus, jqXHR) {
		if (r.content) {
			$(this).replaceWith(r.content);
		} else {
			console.log("failed to refresh");
		}
	};
	settings.data.instructions = instructions;
	var request = jQuery.ajax(settings);
});