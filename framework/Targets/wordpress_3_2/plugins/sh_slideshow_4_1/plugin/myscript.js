// JavaScript Document
jQuery(document).ready(function(){
	// Hide all of manual link field
	jQuery('.manual_link').hide();
	
	// Check which slide is using manual link
	jQuery('.slide_link').each(function(index){
		if(jQuery(this).val()=='manual'){
			jQuery('.manual_link').eq(index).show();
		}
	});
	
	// Slide link change to manual link
	jQuery('.slide_link').change(function(e){
		var index = jQuery('.slide_link').index(jQuery(this));
		if(jQuery(this).val()=='manual'){
			jQuery('.manual_link').eq(index).show();
		}else{
			jQuery('.manual_link').eq(index).hide();
		}
	});
	
	// Add new slide
	jQuery('#add_sh_slide').click(function(){
		$index = jQuery('#shslides tr:last').index();
		window.wordpress_send_to_editor = window.send_to_editor;
		var l = jQuery('#shslides tr:last td select').val();
		jQuery('#shslides tr:last').clone().appendTo('tbody#slides');
		jQuery('#shslides span.sort-icon').eq($index+1).text('#'+($index+2));
		jQuery('#shslides input.shslideshow_slide').eq($index+1).val('');
		jQuery('#shslides .slide_link:eq('+($index+1)+') option:selected').removeAttr('selected');
		jQuery('#shslides .slide_link:eq('+($index+1)+') option[value=0]').attr('selected','selected');
		jQuery('.shslideshow_upload').eq($index+1).bind('click',upload);
		if(l == 'manual'){
			jQuery('input.manual_link').eq($index+1).val('');
		}
		jQuery('input.manual_link').eq($index+1).hide();
		jQuery('.del_sh_slide').eq($index+1).removeAttr('onclick');
		jQuery('.del_sh_slide').eq($index+1).bind('click',function(){
			jQuery(this).parents('tr').remove();
		});
		jQuery('.slide_link').eq($index+1).bind('change',function(e){
			var index = jQuery('.slide_link').index(jQuery(this));
			if(jQuery(this).val()=='manual'){
				jQuery('.manual_link').eq(index).show();
			}else{
				jQuery('.manual_link').eq(index).hide();
			}
		});
	});
	
	// Delete slide
	jQuery('.del_sh_slide').click(function(){
		if(jQuery(this).attr('onclick')==''){
			jQuery(this).parents('tr').remove();
		}
	});
});

function upload() {
		var i = jQuery('.shslideshow_upload').index(this);
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		window.send_to_editor = function(html) {
			imgurl = jQuery(html).attr('href');
			jQuery('.shslideshow_slide').eq(i).val(imgurl);
			tb_remove();
			window.send_to_editor = window.wordpress_send_to_editor;
		}
}
function del_slide(sid,index){
	if(confirm('Are you sure to delete #'+index)){
		jQuery.ajax({
			type: 'POST',
			url: path+'ajax.php',
			dataType: 'json',
			data: {
				id: sid
			},
			success:function(data){
				if(data.msg == 'Success'){
					jQuery('.del_sh_slide[onclick="del_slide('+sid+','+index+')"]').parents('tr').remove();
				}else{
					window.alert(data.msg);
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				window.alert('error');
			}
		});
	}
}