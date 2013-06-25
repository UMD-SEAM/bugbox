//Soundmanager
soundManager.url = sk_url+'/wp-content/plugins/schreikasten/libs/';
	soundManager.useFlashBlock = false;
	soundManager.debugMode = false;
	soundManager.onready(function() {
		if (soundManager.supported()) {
			skSound = soundManager.createSound({
				id: 'aSound',
				url: sk_url+'/wp-content/plugins/schreikasten/img/drop.mp3',
				volume: 70
			});
		}
	});


//Change title
function sk_alternateTitle(title1, title2) {
	if(!sk_hasFocus) {
		document.title = title1;
		setTimeout( 'sk_alternateTitle(\''+title2+'\', \''+title1+'\');' , 1500 );
	} else {
		document.title = sk_old_title;
	}
}

skBeep = function() {
	skSound.play();
}


//Semaphore class
	function sk_Semaphore() {
		var me = this; //Just to use me instead of this if a recursived function is used
		
		var status = true; //The green or red light
		me.using = 0;
	
		//Function to set green
		me.setGreen = function() { status = true; };
	
		//Is green?
		me.isGreen = function() { return status; };
	
		//Function to set red
		me.setRed = function() { status = false; };
	
		//Is red?
		me.isRed = function() { return !status; };
		
	}
	
	var loading_sk_img = new Image(); 
	loading_sk_img.src = sk_url+'/wp-content/plugins/schreikasten/img/loading.gif';
	var sk_sack = new sack(sk_url+'/wp-admin/admin-ajax.php' );
	var sk_sack_action = new sack(sk_url+'/wp-admin/admin-ajax.php' );
	
	function sk_feed( page, rand, semaphore, force, timer )
	{
		var force = (force == null) ? false : force;
		var timer = (timer == null) ? false : timer;
		if( semaphore.isGreen() ) {
			
			semaphore.setRed();
			
			//Our plugin sack configuration
			sk_sack.execute = 0;
			sk_sack.method = 'POST';
			sk_sack.setVar( 'action', 'sk_ajax' );
			//sk_sack.element = 'sk_content'+rand;
			
			//The ajax call data
			sk_sack.setVar( 'page', page );
			sk_sack.setVar( 'rand', rand );
			sk_sack.setVar( 'force', force );
			sk_sack.setVar( 'size' , document.getElementById('sk_size'+rand).value );
			sk_sack.setVar( 'count', document.getElementById('sk_count'+rand).value );
			
			sk_sack.onCompletion = function() {
				if(sk_sack.response=='none') {
					semaphore.setGreen();
				} else {
					var page = sk_sack.vars['page'][0];
					var rand = sk_sack.vars['rand'][0];
					var doc = document.getElementById('sk_content'+rand);
					doc.innerHTML = sk_sack.response;
					semaphore.setGreen();
					if(timer) {
						var count = document.getElementById('sk_count'+rand).value;
						var int_count = document.getElementById('sk_int_count'+rand).value;
						var last = document.getElementById('sk_int_last'+rand).value;
						if(count != int_count) {
							if(int_count > count) {
								if(!sk_hasFocus) sk_alternateTitle(sk_title_message + last, document.title);
								skBeep();
							}
							document.getElementById('sk_count'+rand).value = int_count;
						}
					}
					if(document.getElementById('sk_page'+rand).value!=page) {
						var aux = document.getElementById('throbber-page'+rand);
						aux.setAttribute('class', 'throbber-page-on');
						aux.setAttribute('className', 'throbber-page-on'); //IE sucks
					} else {
						sk_sack.xmlhttp.abort();
						sk_sack.reset();
					}
					var sk_submit=document.getElementById('sk_submit_text'+rand);
					var sk_button=document.getElementById('sk_button'+rand);
					sk_button.value = sk_submit.value; 
				}
			};
			
			sk_sack.runAJAX();
			
		} else {
			setTimeout(function (){ sk_feed( page, rand, semaphore, force, timer ); }, 300);
		}
		return true;
	}
	// end of JavaScript function sk_feed
	
	function sk_add( alias, email, text, skfor, rand, semaphore)
	{
		sk_sack.xmlhttp.abort();
		sk_sack.reset();
		semaphore.setRed();
		
		//Our plugin sack configuration
		sk_sack_action.execute = 0;
		sk_sack_action.method = 'POST';
		sk_sack_action.setVar( 'action', 'sk_ajax_add' );
		sk_sack_action.element = 'sk_content'+rand;
		
		//Encode text
		//Encoder.EncodeType = "numeric";
		alias = Encoder.htmlEncode(alias);
		email = Encoder.htmlEncode(email);
		text = Encoder.htmlEncode(text);
		
		//The ajax call data
		sk_sack_action.setVar( 'alias', alias );
		sk_sack_action.setVar( 'email', email );
		sk_sack_action.setVar( 'text', text );
		sk_sack_action.setVar( 'skfor', skfor );
		sk_sack_action.setVar( 'rand', rand );
		sk_sack_action.setVar( 'size' , document.getElementById('sk_size'+rand).value );
		
		sk_sack_action.onCompletion = function() {
			var rand = sk_sack_action.vars['rand'][0];
			var doc = document.getElementById('sk_content'+rand);
			doc.innerHTML = sk_sack_action.response;
			sk_sack.xmlhttp.abort();
			sk_sack.reset();
			semaphore.setGreen();
			document.getElementById('sk_count'+rand).value = document.getElementById('sk_int_count'+rand).value;
			
			var sk_submit=document.getElementById('sk_submit_text'+rand);
			var sk_button=document.getElementById('sk_button'+rand);
			sk_button.disabled = false;
			sk_button.value = sk_submit.value; 
		
		};
		
		sk_sack_action.runAJAX();
			
		return true;
		
	}
	
	function sk_action( id, sk_action, rand, semaphore)
	{
		sk_sack.xmlhttp.abort();
		sk_sack.reset();
		semaphore.setRed();
		
		//Our plugin sack configuration
		sk_sack_action.execute = 0;
		sk_sack_action.method = 'POST';
		sk_sack_action.setVar( 'action', 'sk_ajax_action' );
		sk_sack_action.element = 'sk_content'+rand;
		
		//The ajax call data
		sk_sack_action.setVar( 'id', id );
		sk_sack_action.setVar( 'sk_action', sk_action );
		sk_sack_action.setVar( 'rand', rand );
		sk_sack_action.setVar( 'page', document.getElementById('sk_page'+rand).value );
		sk_sack_action.setVar( 'size', document.getElementById('sk_size'+rand).value );
		
		sk_sack_action.onCompletion = function() {
			var rand = sk_sack_action.vars['rand'][0];
			var doc = document.getElementById('sk_content'+rand);
			doc.innerHTML = sk_sack_action.response;
			sk_sack.xmlhttp.abort();
			sk_sack.reset();
			semaphore.setGreen();
			document.getElementById('sk_count'+rand).value = document.getElementById('sk_int_count'+rand).value;
			
			var sk_submit=document.getElementById('sk_submit_text'+rand);
			var sk_button=document.getElementById('sk_button'+rand);
			sk_button.value = sk_submit.value; 
		};
		
		sk_sack_action.runAJAX();
			
		return true;
		
	}
	
	function sk_confirm_action(action, rand, id, semaphore) {
		var aux = document.getElementById('sk-'+rand+'-'+id);
		aux.setAttribute('class', 'sk-comment-spam sk-user-admin');
		aux.setAttribute('className', 'sk-comment-spam sk-user-admin');
		text = false;
		if(action == 'delete') text = sk_text_del;
		if(action == 'set_black') text = sk_text_blk;
		if(action == 'set_spam') text = sk_text_spm;
		if(text && confirm(text)) {
			sk_action(id, action, rand, semaphore);
		} else {
			aux.setAttribute('class', 'sk-comment sk-user-admin');
			aux.setAttribute('className', 'sk-comment sk-user-admin'); //IE sucks
		}
	}
	
