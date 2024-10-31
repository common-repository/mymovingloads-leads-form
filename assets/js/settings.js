jQuery(document).ready(function($){
	$('#form-button-field-color').wpColorPicker({defaultColor: true});
	$('#form-background-field-color').wpColorPicker({defaultColor: true});

	// hide_div
	$('#mml_leadform_option_formbg').on('click', function() {
		if ($(this).is(':checked')) {
			$('#bg_cp_options').removeClass('hide_div');
		} else {
			$('#bg_cp_options').addClass('hide_div');
		}
	});
});