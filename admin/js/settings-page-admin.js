(function ($) {
	'use strict';

	$(document).ready(function () {
		$('#save_setting_binox').submit(function (event) {

			event.preventDefault(); //this will prevent the default submit
			//check if is valid domain value
			let domain = $("#binox_wp_domain").val()
			// is valid regex domain
			domain = domain.replace(/^\s+|\s+$/g, '');
			let regex = /^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/;
			if (!regex.test(domain)) {
				alert("Domain is not valid");
				return;
			}
			$(this).unbind('submit').submit();
		})
	})

})(jQuery);
