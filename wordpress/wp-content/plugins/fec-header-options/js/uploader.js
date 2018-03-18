(function ($) {
	"use strict";
	$(function () {
	    $('#fec_upload_file_image_button').click(function() {
	        wp.media.editor.send.attachment = function(props, attachment) {
	            $('#fec_header_image').val(attachment.url);
	        }

	        wp.media.editor.open(this);

	        return false;
	    });
	});
}(jQuery));