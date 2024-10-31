jQuery(function(jQuery) {
	
	// Lists ---------------------
	jQuery('.ptdl_add_list').click(function() {
		field = jQuery(this).closest('#ptdl_table').find('.ptdl_container:last').clone(true);
		fieldLocation = jQuery(this).closest('#ptdl_table').find('.ptdl_container:last');
		jQuery('.ptdl_clear input', field).val('')
			.attr('name', function(index, name) {
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			})
			.attr('id', function(index, name) {
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			})
			.removeAttr("checked");
		jQuery('label', field).val('')
			.attr('for', function(index, name) {
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			});
		jQuery('.ptdl_shortcode input', field)
			.val(function(index, name) {
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			});
		jQuery('.ptdl_items li:not(:first)', field).remove();
		field.insertAfter(fieldLocation, jQuery(this).closest('td'));
		return false;
	});
	
	jQuery('.ptdl_remove_list').click(function(){
		jQuery(this).parent().remove();
		return false;
	});
	
	// Items ---------------------
	jQuery('.ptdl_add_item').click(function() {
		field = jQuery(this).parent().parent('.ptdl_items').find('li:last').clone(true);
		fieldLocation = jQuery(this).parent().parent('.ptdl_items').find('li:last');
		jQuery('input', field).val('')
			.attr('name', function(index, name) {
				return name.replace(/(\D+)(\d+)(\D+)$/, function(str, m1, m2, m3) {
					var newstr = (+m2 + 1) + "";
					return m1 + new Array(3 - newstr.length).join("") + newstr + m3;
				});
			})
			.attr('id', function(index, name) {
				return name.replace(/(\D+)(\d+)(\D+)$/, function(str, m1, m2, m3) {
					var newstr = (+m2 + 1) + "";
					return m1 + new Array(3 - newstr.length).join("") + newstr + m3;
				});
			})
			.removeAttr("checked");
		field.insertAfter(fieldLocation, jQuery(this).parent().parent('.ptdl_items'));
		return false;
	});
	
	jQuery('.ptdl_remove_item').click(function(){
		jQuery(this).parent().remove();
		return false;
	});
	
	jQuery('#ptdl_table .ptdl_items').sortable({
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		handle: '.ptdl_handle'
	});
	
	// Shortcode ---------------------
	jQuery('.ptdl_add_shortcode').click(function() {
		shortcode = jQuery(this).siblings('input').val();
		send_to_editor(shortcode);
		return false;
	});
	
	// Datepicker ----------------------
	jQuery('.ptdl_date').datepicker({
		dateFormat: 'yy-mm-dd'
	});
	
	// Checked ----------------------------
	jQuery('.ptdl_container input[type=checkbox]').change(function() {
		jQuery(this).siblings('input').toggleClass('ptdl_checked');
	});

});