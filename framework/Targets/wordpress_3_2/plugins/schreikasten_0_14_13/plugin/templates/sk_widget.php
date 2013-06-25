<script type='text/javascript'>
	/* <![CDATA[ */
	
	function sk_timer%rand%() {
		var sk_timer_div = document.getElementById('sk_timer%rand%');
		if(sk_timer_div.value) clearTimeout(sk_timer_div.value);
		timer_id=setTimeout( 'sk_refresh%rand%(true);' , %time% );
		sk_timer_div.value=timer_id;
	}
	
	function sk_refresh%rand%(timer) {
		var sk_timer_div = document.getElementById('sk_timer%rand%');%show_timer%
		sk_feed( document.getElementById('sk_page%rand%').value, %rand%, sk_semaphore%rand%, false, timer);
	}
	
	function for_delete%rand%() {
		document.getElementById('sk_for_id%rand%').value='0';
		document.getElementById('sk_for_name%rand%').innerHTML='';
		document.getElementById('sk_for_tr%rand%').className='sk-for-nai';
	}
	
	function for_set%rand%(id, name) {
		document.getElementById('sk_for_id%rand%').value=id;
		document.getElementById('sk_for_name%rand%').innerHTML=name;
		var text=document.getElementById('sk_text%rand%').value;
		if(%qa%) {
			document.getElementById('sk_text%rand%').value='%answer%: '+text;
		} else {
			document.getElementById('sk_text%rand%').value='%for% '+name+' - '+text;
		}
		document.getElementById('sk_for_tr%rand%').className='sk-for-yep';
	}
	
	function sk_replyDelete%rand%(id, text) {
		var aux = document.getElementById('sk-%rand%-'+id);
		aux.setAttribute('class', 'sk-reply sk-reply-on');
		aux.setAttribute('className', 'sk-reply sk-reply-on');
		if(confirm(text)) {
			sk_action(id, 'delete', %rand%, sk_semaphore%rand%);
		} else {
			aux.setAttribute('class', 'sk-reply');
			aux.setAttribute('className', 'sk-reply'); //IE sucks
		}
	}
	
	function sk_pressButton%rand%() {
		var alias=document.getElementById('sk_alias%rand%').value;
		var text=document.getElementById('sk_text%rand%').value;
		var email=document.getElementById('sk_email%rand%').value;
		var skfor=document.getElementById('sk_for_id%rand%').value;
		if(text.length>%maxchars%) {
			alert('%lenght%');
			return false;
		}%ask_email%%email_in_text%
		document.getElementById('th_sk_alias%rand%').innerHTML = alias;
		document.getElementById('th_sk_text%rand%').innerHTML = text.replace(/\n/g,"<br>");;
		if(%chat%){
			document.getElementById('throbber-page%rand%').setAttribute('class','throbber-page-on');
			document.getElementById('throbber-page%rand%').setAttribute('className','throbber-page-on'); //IE sucks
		} else {
			document.getElementById('throbber-img%rand%').setAttribute('class','throbber-img-on');
			document.getElementById('throbber-img%rand%').setAttribute('className','throbber-img-on'); //IE sucks
			document.getElementById('throbber-img%rand%').style.visibility='visible';
		}
		for_delete%rand%();
		document.getElementById('sk_page%rand%').value=1;
		document.getElementById('sk_text%rand%').value='';
		div_sk_allowed = document.getElementById('sk_allowed%rand%');
		if(div_sk_allowed.value > 0) {
			div_sk_allowed.value = div_sk_allowed.value - 1;
		}
		
		sk_add( alias, email, text, skfor, %rand%, sk_semaphore%rand% );
	}
/* ]]> */
</script>
%sk_general%
<script type='text/javascript'>
	window.onblur = function () {sk_hasFocus = false;}
	window.onfocus = function () {sk_hasFocus = true; document.title = sk_old_title;}
	var sk_semaphore%rand%=new sk_Semaphore();
	sk_semaphore%rand%.setGreen();
	%have_for%%show_timer% 
	
</script>
