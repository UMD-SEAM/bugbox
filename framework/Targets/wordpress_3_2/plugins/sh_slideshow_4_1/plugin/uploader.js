// Javascript for upload
jQuery(document).ready(uploader);

function uploader() {
	window.wordpress_send_to_editor = window.send_to_editor;
	jQuery('.shslideshow_upload').click(function(e) {
		var i = jQuery('.shslideshow_upload').index(this);
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		window.send_to_editor = function(html) {
			imgurl = jQuery(html).attr('href');
			jQuery('.shslideshow_slide').eq(i).val(imgurl);
			tb_remove();
			window.send_to_editor = window.wordpress_send_to_editor;
		}
	});
}