/*****************************************************************
 * file: csl.js
 *
 * A centralized javascript code to be compatible with all CSL plugins so we can virtually save cats from trees.
 *
 *****************************************************************/

/***************************
  * Cyber Sprocket Labs Namespace
  *
  * For stuff to do awesome stuff like save lobby jones if he got stuck in a tree.
  *
  */
var csl = {
	
	/***************************
  	 * Class: Ajax
  	 * usage:
	 * 		Sends an ajax request (use Ajax.Send())
  	 * parameters:
  	 * 		action: A usable action { action: 'csl_ajax_search', lat: 'start lat', long: 'start long', dist:'distance to search' }
  	 *		callback: will be of the form: { success: true, response: {marker list}}
  	 * returns: none
  	 */
	Ajax: function() {
		/***************************
		 * function: Ajax.send
		 * usage:
		 * 		Sends an ajax request
		 * parameters:
		 * 		action: A usable action { action: 'csl_ajax_search', lat: 'start lat', long: 'start long', dist:'distance to search' }
		 *		callback: will be of the form: { success: true, response: {marker list}}
		 * returns: none
		 */
		this.send = function(action, callback) {
			jQuery.post(csl_ajax.ajaxurl, action,
			function (response) {
				callback(response);
			});
		}
		
		this.GetXmlHttpObject = function() {
			var objXMLHttp=null;
			if (window.XMLHttpRequest) {
				objXMLHttp=new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			return objXMLHttp;
		}
		
		this.stateChanged = function() {
			if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") { 
				document.getElementById("ajaxMsg").innerHTML="Submission Successful.";
			} 
		}
		
		this.xmlHttp;
		
		this.showArticles = function(start) {
			xmlHttp=GetXmlHttpObject();
			if (xmlHttp==null)
			{
				alert ("Browser does not support HTTP Request");
				return false;
			} 
			var url="/display_document_info.php";
			url=url+"?start="+start;
			url=url+"&sid="+Math.random();
			xmlHttp.onreadystatechange=stateChanged;
			xmlHttp.open("GET",url,true);
			xmlHttp.send(null);
		}
		
		this.doc_counter = function(the_loc) {
			if (the_loc.search.indexOf('u=')!=-1) {
				parts=the_loc.href.split('u=');
				u_part=parts[1].split('&')[0];
			} 
			else {
				dirs=the_loc.href.split('/');
				u_part=dirs[dirs.length-1];
				u_part=u_part.split('?')[0].split('.')[0];
			}
	
			xmlHttp=GetXmlHttpObject();
			if (xmlHttp==null)
			{
				alert ("Browser does not support HTTP Request");
				return false;
			} 
			var url="/scripts/doc_counter.php";
			url=url+"?u="+u_part;
			xmlHttp.open("GET",url,true);
			xmlHttp.send(null);
		}
	},
	
	MouseAnimation: function()
	{
		this.anim2 = function(imgObj, url) {
			imgObj.src=url;
		}
		
		this.anim = function(name, type) {
			if (type==0)
				document.images[name].src="/core/images/"+name+".gif";
			if (type==1)
				document.images[name].src="/core/images/"+name+"_over.gif";
			if (type==2)
				document.images[name].src="/core/images/"+name+"_down.gif";
		}
	},
  	  
	/***************************
  	* Animation enum technically
  	* usage:
  	* 		Animation enumeration 
  	*/
  	Animation: { Bounce: 1, Drop: 2, None: 0 },
  	  
	/***************************
  	 * Marker for google maps
  	 * usage:
  	 * create a google maps marker
  	 * parameters:
  	 * 	animationType: The Animation type to do the animation
	 *		map: the csl.Map type to put it on
	 *		title: the title of the marker for mouse over
	 *		iconUrl: todo: load a custom icon, null for default
	 *		position: the lat/long to put the marker at
  	 */
  	Marker: function (animationType, map, title, position, iconUrl, iconSizeW, iconSizeH) {
		this.__animationType = animationType;
  	  	this.__map = map;
  	  	this.__title = title;
  	  	this.__iconUrl = iconUrl;
  	  	this.__position = position;
  	  	this.__gmarker = null;
		this.__iconHeight = iconSizeH;
		this.__iconWidth = iconSizeW;
		this.__iconImage = null;
		this.__shadowImage = null;
  	  	
  	  	this.__init = function() {
			if (this.__iconUrl != null) {
				this.__iconImage = this.__iconUrl;
			}
			//this.__iconImage = null;
			if (this.__iconImage == null)
			{
				this.__gmarker = new google.maps.Marker(
				{
					position: this.__position,
					map: this.__map.gmap,
					animation: this.__animationType,
					position: this.__position,
					title: this.__title
				});
			}		
			//find the shadow icon
			else {
				// var http = new XMLHttpRequest();
				// http.onreadystatechange = function() { };
				// http.open("HEAD", this.__iconUrl.replace('.png', '_shadow.png'), false);
				// http.send(null);
				
				// if (http.status == 404) {
					// this.noShadow();
				// }
				// else {
					this.useShadow();
				//}
			}
			
  	  	}
		
		this.buildMarker = function() {
			this.__gmarker = new google.maps.Marker(
  	  	  	{
				position: this.__position,
  	  	  	  	map: this.__map.gmap,
  	  	  	  	animation: this.__animationType,
				shadow: this.__shadowImage,
				icon: this.__iconImage,
				zIndex: 0,
  	  	  	  	position: this.__position,
  	  	  	  	title: this.__title
  	  	  	});
		}
		
		this.noShadow = function() {
			var parts = this.__iconUrl.split('/');
			var shadow = this.__iconUrl.replace(parts[parts.length - 1], 'blank.png');
			this.__shadowImage = new google.maps.MarkerImage(shadow,
				//set the size
				new google.maps.Size(this.__iconWidth, this.__iconHeight));
			this.buildMarker();
			
		}
		
		this.useShadow = function() {
			var shadow = this.__iconUrl.replace('.png', '_shadow.png');
			this.__shadowImage = shadow;
				//set the size
				//new google.maps.Size(this.__iconWidth, this.__iconHeight));
			this.buildMarker();
		}
  	  	 
  	  	this.__init();
  	},
	
	Utils: function() {
		/*****************************************************************************
		* File: store-locator-emailform.js
		* 
		* Create the lightbox email form.
		*
		*****************************************************************************/
		this.show_email_form = function(to) {
			var allScripts=document.getElementsByTagName('script');
			var add_base=allScripts[allScripts.length -2].src.replace(/\/js\/csl.js(.*)$/,'');
			emailWin=window.open("about:blank","",
				"height=220,width=310,scrollbars=no,top=50,left=50,status=0,toolbar=0,location=0,menubar=0,directories=0,resizable=0");
			with (emailWin.document) {
				writeln("<html><head><title>Send Email To " + to + "</title></head>");
                
				writeln("<body scroll='no' onload='self.focus()' onblur='close()'>");
        
				writeln("<style>");
				writeln(".form_entry{ width: 300px; clear: both;} ");
				writeln(".form_submit{ width: 300px; text-align: center; padding: 12px;} ");
				writeln(".to{ float: left; font-size: 12px; color: #444444; } ");
				writeln("LABEL{ float: left; width: 75px;  text-align:right; ");
				writeln(      " font-size: 11px; color: #888888; margin: 3px 3px 0px 0px;} ");
				writeln("INPUT type=['text']{ float: left; width: 225px; text-align:left; } ");
				writeln("INPUT type=['submit']{ padding-left: 120px; } ");
				writeln("TEXTAREA { width: 185px; clear: both; padding-left: 120px; } ");
				writeln("</style>");
        
				writeln("<form id='emailForm' method='GET'");
				writeln(    " action='"+add_base+"/send-email.php'>");
        
				writeln("    <div id='email_form_content'>");

				writeln("        <div class='form_entry'>");
				writeln("            <label for='email_to'>To:</label>");
				writeln("            <input type='hidden' name='email_to' value='"+to+"'/>");
				writeln("            <div class='to'>"+to+"</div>");
				writeln("        </div>");           
					
        
				writeln("        <div class='form_entry'>");
				writeln("            <label for='email_name'>Your Name:</label>");
				writeln("            <input name='email_name' value='' />");
				writeln("        </div>");
        
				writeln("        <div class='form_entry'>");
				writeln("            <label for='email_from'>Your Email:</label>");
				writeln("            <input name='email_from' value='' />");
				writeln("        </div>");             
					
				writeln("        <div class='form_entry'>");
				writeln("            <label for='email_subject'>Subject:</label>");
				writeln("            <input name='email_subject'  value='' />");
				writeln("        </div>");        
					
				writeln("        <div class='form_entry'>");
				writeln("            <label for='email_message'>Message:</label>");
				writeln("            <textarea name='email_message'></textarea>");
				writeln("        </div>");                
				writeln("    </div>");    
		
				writeln("    <div class='form_submit'>");
				writeln("        <input type='submit' value='Send Message'>");
				writeln("    </div>");
				writeln("</form>");
				writeln("</body></html>");
				close();
			}     
		}
	
		/**************************************
		 * function: escapeExtended()
		 *
		 * Escape any extended characters, such as ü in für.
		 * Standard US ASCII characters (< char #128) are unchanged
		 *
		 */ 
		this.escapeExtended = function(string)
		{
			string = string.replace(/\r\n/g,"\n");
			var utftext = "";
 
			for (var n = 0; n < string.length; n++) {
 
				var c = string.charCodeAt(n);
 
				if (c < 128) {
					utftext += string.charAt(n);
				}
				else
				{
					utftext += escape(string.charAt(n));
				}
 
			}
 
			return utftext; 
		}
	},
  	  
	/***************************
  	 * Popup info window Object
  	 * usage:
  	 * create a google info window
  	 * parameters:
  	 * 	content: the content to show by default
  	 */
  	Info: function (content) {
		this.__content = content;
  	  	this.__position = position;
  	  	
  	  	this.__anchor = null;
  	  	this.__gwindow = null;
  	  	this.__gmap = null;
  	  	
  	  	this.openWithNewContent = function(map, object, content) {
			this.__content = content;
  	  		this.__gwindow = setContent = this.__content;
  	  	  	this.open(map, object);
  	  	}
  	  	  
  	  	this.open = function(map, object) {
			this.__gmap = map.gmap;
  	  	  	this.__anchor = object;
  	  	  	this.__gwindow.open(this.__gmap, this.__anchor);
  	  	}
  	  	  
  	  	this.close = function() {
			this.__gwindow.close();
  	  	}
  	  	  
  	  	this.__init = function() {
			this.__gwindow = new google.maps.InfoWindow(
  	  	  	{
				content: this.__content
  	  	  	});
  	  	}
  	  	  
  	  	this.__init();
  	},
  	  
  	/***************************
  	 * Map Object
  	 * usage:
  	 * create a google maps object linked to a map/canvas id
  	 * parameters:
  	 * 	aMapNumber: the id/canvas of the map object to load from php side
  	 */
  	Map: function(aMapCanvas) {
		//private: map number to look up at init
  	  	this.__mapCanvas = aMapCanvas;
		
		//function callbacks
		this.tilesLoaded = null;
  	  	
  	  	//php passed vars set in init
		this.debug_mode = null;
  	  	this.address = null; //y
  	  	this.canvasID = null;
  	  	this.draggable = true; 
  	  	this.tilt = 45; //n
  	  	this.zoomStyle = 0; // 0 = default, 1 = small, 2 = large
		this.markers = null;
		
		//slplus options
		this.debugMode = null;
		this.disableScroll = null;
		this.disableDir = null;
		this.distanceUnit = null;
		this.load_locations = null;
		this.map3dControl = null;
		this.mapCountry = null;
		this.mapDomain = null;
		this.mapHomeIconUrl = null;
		this.mapHomeIconWidth = null;
		this.mapHomeIconHeight = null;
		this.mapEndIconUrl = null;
		this.mapEndIconWidth = null;
		this.mapEndIconHeight = null;
		this.mapScaleControl = null;
		this.mapType = null;
		this.mapTypeControl = null;
		this.showTags = null;
		this.overviewControl = null;
		this.useEmailForm = null;
		this.usePagesLinks = null;
		this.useSameWindow = null;
		this.websiteLabel = null;
		this.zoomLevel = null;
  	  	
  	  	//gmap set variables
  	  	this.options = null;
  	  	this.gmap = null;
  	  	this.centerMarker = null;
		this.marker = null;
		this.infowindow = new google.maps.InfoWindow();
		this.bounds = null;
		this.homeAddress = null;
		this.homePoint = null;
		this.forceAll = false;
		this.lastCenter = null;
		this.lastRadius = null;
		this.loadedOnce = false;
        this.centerLoad = false;
		
		/***************************
  	  	 * function: __init()
  	  	 * usage:
  	  	 * Called at the end of the 'class' due to some browser's quirks
  	  	 * parameters: none
  	  	 * returns: none
  	  	 */
  	  	this.__init = function() {
			this.address = slplus.map_country;
  	  	  	this.zoom = slplus.zoom_level;
  	  	  	this.mapType = slplus.map_type;
			this.disableScroll = !!slplus.disable_scroll;
			this.debugMode = !!slplus.debug_mode;
			this.disableDir = !!slplus.disable_dir;
			this.distanceUnit = slplus.distance_unit;
			this.load_locations = !!slplus.load_locations;
			this.mapCountry = slplus.map_country;
			this.mapDomain = slplus.map_domain;
			this.mapHomeIconUrl = slplus.map_home_icon;
			this.mapHomeIconWidth = slplus.map_home_icon_sizew;
			this.mapHomeIconHeight = slplus.map_home_icon_sizeh;
			this.mapEndIconUrl = slplus.map_end_icon;
			this.mapEndIconWidth = slplus.map_end_sizew;
			this.mapEndIconHeight = slplus.map_end_sizeh;
			this.mapScaleControl = !!slplus.map_scalectrl;
			this.mapTypeControl = !!slplus.map_typectrl;
			this.showTags = slplus.show_tags;
			this.overviewControl = !!(parseInt(slplus.overview_ctrl));
			this.useEmailForm = !!slplus.use_email_form;
			this.usePagesLink = !!slplus.use_pages_link;
			this.useSameWindow = !!slplus.use_same_window;
			this.websiteLabel = slplus.website_label;
			this.zoomLevel = slplus.zoom_level;
  	  	  	this.disableDefaultUI = false;
			
			if (!this.disableDir) {
				this.loadedOnce = true;
			}
  	  	}
  	  	
  	  	/***************************
  	  	 * function: __geocodeResult
  	  	 * usage:
		 * Called when the geocode is complete
  	  	 * parameters:
  	  	 * 	results: some usable results (see google api reference)
  	  	 *		status:  the status of the geocode (ok means g2g)
  	  	 * returns: none
  	  	 */
  	  	this.__geocodeResult = function(results, status) {
			if (status == 'OK' && results.length > 0)
  	  	  	{
				this.debugSearch('building map');
				
					this.debugSearch(results[0]);
				// if the map hasn't been created, then create one
				if (this.gmap == null)
				{
					this.options = {
						mapTypeControl: this.mapTypeControl,
						mapTypeId: this.mapType,
						overviewMapControl: this.overviewControl,
						scrollwheel: !this.disableScroll,
						center: results[0].geometry.location,
						zoom: parseInt(this.zoom),
						scaleControl: this.mapScaleControl,
						overviewMapControl: this.overviewControl,
						overviewMapControlOptions: { opened: this.overviewControl }
					};
					this.debugSearch(this.options);
					this.gmap = new google.maps.Map(document.getElementById('map'), this.options);
					this.debugSearch(this.gmap);
					//this forces any bad css from themes to fix the "gray bar" issue by setting the css max-width to none
					var _this = this;
					google.maps.event.addListener(this.gmap, 'bounds_changed', function() {
						_this.__waitForTileLoad.call(_this);
					});
				  
					//load all the markers
                    if (this.load_locations == '1') {
					    if (this.saneValue('addressInput', null) == null || this.saneValue('addressInput', null) == '') {
						    this.forceAll = true;
						
						    this.loadMarkers();
					    }
					    else {
						    this.homePoint = results[0].geometry.location;
						    this.homeAddress = results[0].formatted_address;
						    this.addMarkerAtCenter();
						    var tag_to_search_for = this.saneValue('tag_to_search_for', '');
						    var radius = this.saneValue('radiusSelect');
						    this.loadMarkers(results[0].geometry.location, radius, tag_to_search_for);
					    }
                    }
                    else {
                        this.load_locations = '0';
                    }
				}
				//the map has been created so shift the center of the map
				else {
					//move the center of the map
					//this.gmap.panTo(results[0].geometry.location);
					this.homePoint = results[0].geometry.location;
					this.homeAdress = results[0].formatted_address;
					
					this.addMarkerAtCenter();
					var tag_to_search_for = this.saneValue('tag_to_search_for', '');
					//do a search based on settings
					var radius = this.saneValue('radiusSelect');
					this.loadMarkers(results[0].geometry.location, radius, tag_to_search_for);
				}
				//if the user entered an address, replace it with a formatted one
				var addressInput = this.saneValue('addressInput','');
				if (addressInput != '') {
					addressInput = results[0].formatted_address;
				}
  	  	  	} else {
				//address couldn't be processed, so use the center of the map
				var tag_to_search_for = this.saneValue('tag_to_search_for', '');
				var radius = this.saneValue('radiusSelect');
				this.loadMarkers(null, radius, tag_to_search_for);
  	  	  	}
  	  	}
  	  	  
		/***************************
  	  	 * function: __waitForTileLoad
  	  	 * usage:
		 * Notifies as the map changes that we'd like to be nofified when the tiles are completely loaded
  	  	 * parameters:
  	  	 * 	none
  	  	 * returns: none
  	  	 */
		this.__waitForTileLoad = function() {
			var _this = this;
			if (this.__tilesLoaded == null)
			{
				this.__tilesLoaded = google.maps.event.addListener(this.gmap, 'tilesloaded', function() {
					_this.__tilesAreLoaded.call(_this);
				});
			}
		}
		  
		/***************************
  	  	 * function: __tilesAreLoaded
  	  	 * usage:
		 * All the tiles are loaded, so fix their css
  	  	 * parameters:
  	  	 * 	none
  	  	 * returns: none
  	  	 */
		this.__tilesAreLoaded = function() {
			jQuery('#map').find('img').css({'max-width': 'none'});
			google.maps.event.removeListener(this.__tilesLoaded);
			this.__tilesLoaded = null;
		}
		  
  	  	/***************************
  	  	 * function: addMarkerAtCenter
  	  	 * usage:
  	  	 * Puts a pretty marker right smack in the middle
  	  	 * parameters:
  	  	 * 	none
  	  	 * returns: none
  	  	 */
  	  	this.addMarkerAtCenter = function() {
			if (this.centerMarker) {
				this.centerMarker.__gmarker.setMap(null);
			}
			if (this.homePoint) {
				this.centerMarker = new csl.Marker(csl.Animation.None, this, "", this.homePoint, this.mapHomeIconUrl, this.mapHomeIconWidth, this.mapHomeIconHeight);
			}
  	  	}
		
		/***************************
  	  	 * function: clearMarkers
  	  	 * usage:
  	  	 * 		Clears all the markers from the map and releases it for GC
  	  	 * parameters:
  	  	 * 	none
  	  	 * returns: none
  	  	 */
		this.clearMarkers = function() {
			if (this.markers) {
				for (markerNumber in this.markers) {
					this.markers[markerNumber].__gmarker.setMap(null);
				}
				this.markers.length = 0;
			}
		}
		
		/***************************
  	  	 * function: putMarkers
  	  	 * usage:
  	  	 * 		Puts an array of markers on the map with the given animation set
  	  	 * parameters:
  	  	 * 		markerList:
		 *			a list of csl.Markers
		 *		animation:
		 *			the csl.Animation type
  	  	 * returns: none
  	  	 */
		this.putMarkers = function(markerList, animation) {
			this.markers = [];
			if (this.loadedOnce) {
				var sidebar = document.getElementById('map_sidebar');
				sidebar.innerHTML = '';
			}
			
			//don't animate for a large set of results
			if (markerList.length > 25) animation = csl.Animation.None;
			
			this.debugSearch('create latlng bounds for shifts');
			var bounds;
			this.debugSearch('number results ' + markerList.length);
			for (markerNumber in markerList) {
				this.debugSearch(markerList[markerNumber]);
				var position = new google.maps.LatLng(markerList[markerNumber].lat, markerList[markerNumber].lng);
				
				if (markerNumber == 0)
				{
					this.debugSearch('create initial bounds');
					bounds = new google.maps.LatLngBounds();
					if (this.homePoint) { bounds.extend(this.homePoint); } else {
                        if (this.centerLoad) {
                            bounds.extend(this.gmap.getCenter());
                        }
                        else {
                            this.centerLoad = true;
                        }
                    }
					bounds.extend(position);
				}
				else  if (markerNumber > 0) {
					bounds.extend(position);
				}
				
				this.debugSearch(position);
				this.markers.push(new csl.Marker(animation, this, "", position, this.mapEndIconUrl, this.mapEndIconWidth, this.mapEndIconHeight ));
				_this = this;
				
				//create a sidebar entry
				if (this.loadedOnce) {
					var sidebarEntry = this.createSidebar(markerList[markerNumber]);
					sidebar.appendChild(sidebarEntry);
				}
				
				//create info windows
				google.maps.event.addListener(this.markers[markerNumber].__gmarker, 'click', 
				(function (infoData, marker) {
					return function() {
						_this.__handleInfoClicks.call(_this, infoData, marker);
					}
				})(markerList[markerNumber], this.markers[markerNumber]));
				
				if (this.loadedOnce) {
					google.maps.event.addDomListener(sidebarEntry, 'click', 
					(function(infoData, marker) {
						return function() {
							_this.__handleInfoClicks.call(_this, infoData, marker);
						}
					})(markerList[markerNumber], this.markers[markerNumber]));
				}
			}
			
			this.loadedOnce = true;
			
			//check for results
			if (markerList.length == 0) {
				this.gmap.panTo(this.homePoint);
                var sidebar = document.getElementById('map_sidebar');
				sidebar.innerHTML = '<div class="no_results_found"><h2>No results found.</h2></div>';
			}
			
			if (bounds != null) {
				this.debugSearch('rebounded');
				this.bounds = bounds;
				//if (this.homePoint) { 
				//	this.gmap.panTo(this.homePoint); }
				this.gmap.fitBounds(this.bounds);
				//this.gmap.panTo(this.bounds.getCenter());
			}
		}
		
		/***************************
  	  	 * function: bounceMarkers
  	  	 * usage:
  	  	 * 		Puts a list of markers on the screen and makes them bounce
  	  	 * parameters:
  	  	 * 		markerlist:
		 *			the list of csl.Markers to add to the map
  	  	 * returns: none
  	  	 */
		this.bounceMarkers = function(markerList) {
			this.clearMarkers();
			this.debugSearch('bounce');
			this.putMarkers(markerList, csl.Animation.None);
		}
		
		/***************************
  	  	 * function: dropMarkers
  	  	 * usage:
  	  	 * 		drops a list of csl.Markers on the map
  	  	 * parameters:
  	  	 * 		markerList:
		 *			the list of csl.Markers to drop
  	  	 * returns: none
  	  	 */
		this.dropMarkers = function(markerList) {
			this.clearMarkers();
			this.debugSearch('dropping');
			this.putMarkers(markerList, csl.Animation.Drop);
		}
		
		/***************************
  	  	 * function: private handleInfoClicks
  	  	 * usage:
  	  	 * 		Sets the content to the info window and builds the sidebar when a user clicks a marker
  	  	 * parameters:
  	  	 * 		infoData:
		 *			the information to build the info window from (ajax result)
		 *		marker:
		 *			the csl.Marker to add the information to
  	  	 * returns: none
  	  	 */
		this.__handleInfoClicks = function(infoData, marker) {
			this.debugSearch(infoData);
			this.debugSearch(marker);
			this.debugSearch(this);
			this.infowindow.setContent(this.createMarkerContent(infoData));
			//this.infowindow.setContent('hi');
			this.infowindow.open(this.gmap, marker.__gmarker);
		}
  	  	  
  	  	/***************************
  	  	 * function doGeocode()
  	  	 * usage:
  	  	 * Call to start the geocode of the address and display it on the map if possible
  	  	 * make sure to call init first
  	  	 * parameters: none
  	  	 * returns: none
  	  	 */
  	  	this.doGeocode = function() {
			var geocoder = new google.maps.Geocoder();
  	  	  	var _this = this;
  	  	  	geocoder.geocode(
				{
					'address': this.address
  	  	  	  	},
  	  	  	  	function (result, status) {							// This is a little complicated, 
  	  	  	  	_this.__geocodeResult.call(_this, result, status); }	// but it forces the callback to keep its scope
  	  	  	);
  	  	}
        
        /***************************
  	  	 * function: __getMarkerUrl
  	  	 * usage:
  	  	 * 		Builds the url for store pages
  	  	 * parameters:
  	  	 * 		aMarker:
		 *			the ajax result to build the information from
  	  	 * returns: an url
        */
        this.__getMarkerUrl = function(aMarker) {
            var url = '';
            //add an http to the url
            if (aMarker.sl_pages_url != '') {
                url = aMarker.sl_pages_url;
            }
            else if (aMarker.url != '') {
                if (aMarker.url.indexOf("http://") == -1) {
                    aMarker.url = "http://" + aMarker.url;
                }
                
                if (aMarker.url.indexOf(".") != -1) {
                    url = aMarker.url;
                }
            }
            
            return url;
        }
		
		/***************************
  	  	 * function: createMarkerContent
  	  	 * usage:
  	  	 * 		Builds the html div for the info window
  	  	 * parameters:
  	  	 * 		aMarker:
					the ajax result to build the information from
  	  	 * returns: an html <div>
  	  	 */
		this.createMarkerContent = function(aMarker) {
			var html = '';
            
            var url = this.__getMarkerUrl(aMarker);
			
			if (url != '') { 
				html += "| <a href='"+url+"' target='"+(slplus.use_same_window?'_self':'_blank')+"' class='storelocatorlink'><nobr>" + slplus.website_label +" </nobr></a>";
			} 
			
			if (aMarker.email.indexOf("@") != -1 && aMarker.email.indexOf(".") != -1) {
				if (!this.useEmailForm) {
					html += "| <a href='mailto:"+aMarker.email+"' target='_blank' class='storelocatorlink'><nobr>" + aMarker.email +"</nobr></a>";
				} else {
					html += "| <a href='javascript:cslutils.show_email_form("+'"'+aMarker.email+'"'+");' class='storelocatorlink'><nobr>" + aMarker.email +"</nobr></a><br/>";
				}
			}
			
			if (aMarker.image.indexOf(".") != -1) {
				html+="<br/><img src='"+aMarker.image+"' class='sl_info_bubble_main_image'>";
			} else {
				aMarker.image = "";
			}
			
			if (aMarker.description != '') {
				html+="<br/>"+aMarker.description+"";
			} else {
				aMarker.description = '';
			}
			
			if (aMarker.hours != '') {
				html+="<br/><span class='location_detail_label'>Hours:</span> "+aMarker.hours;
			} else {
				aMarker.hours = "";
			}
			
			if (aMarker.phone != '') {
				html+="<br/><span class='location_detail_label'>Phone:</span> "+aMarker.phone;
			}

			var address = aMarker.address;
			if (aMarker.address == '') { aMarker.address = ""; } else address += ', ';
			address += aMarker.address2;
			if (aMarker.address2 == '') { aMarker.address2 = ""; } else address += ', ';
			address += aMarker.city;
			if (aMarker.city == '') { aMarker.city = ""; } else address += ', ';
			address += aMarker.state;
			if (aMarker.state == '') { aMarker.state = ""; } else address += ', ';
			address += aMarker.zip;
			if (aMarker.zip == '') { aMarker.zip = ""; }
			
			if (slplus.show_tags) {
				if (jQuery.trim(aMarker.tags) != '') {
					html += '<br/>'+aMarker.tags;
				}
			}
			var complete_html = '<div id="sl_info_bubble"><!--tr><td--><strong>' + aMarker.name + '</strong><br>' + address + '<br/> <a href="http://' + slplus.map_domain + '/maps?saddr=' + /*todo: searched address goes here*/ encodeURIComponent(this.address) + '&daddr=' + encodeURIComponent(aMarker.street + ', ' + aMarker.street2 + ', ' + aMarker.city + ', ' + aMarker.state + ', ' + aMarker.zip) + '" target="_blank" class="storelocatorlink">Directions</a> ' + html + '<br/><!--/td></tr--></div>';
			
			return complete_html;
		}
		
		this.debugSearch = function(toLog) {
			if (slplus.debug_mode == 1)
			{
				//console.log(toLog);
			}
		}
		
		/***************************
  	  	 * function: saneValue
  	  	 * usage:
  	  	 * 		Gets a sane value from the document
  	  	 * parameters:
  	  	 * 		id:
		 *			the id of the control to look up
		 *		defaultValue:
		 *			the default value to return if it doesn't exist
  	  	 * returns: none
  	  	 */
		this.saneValue = function(id, defaultValue) {
			var name = document.getElementById(id);
			if (name == null) {
				name = defaultValue;
			}
			else {
				name = name.value;
			}
			return name;
		}
		
		/***************************
  	  	 * function: loadMarkers
  	  	 * usage:
  	  	 * 		Sends an ajax request and drops the markers on the map
  	  	 * parameters:
  	  	 * 		center:
		 *			the center of the map (where to center to)
  	  	 * returns: none
  	  	 */
		this.loadMarkers = function(center, radius, tags) {
			//determines if we need to invent real variables (usually only done at the beginning)
			var realsearch = true;
			if (this.forceAll) {
				realsearch = false;
				radius = null;
				center = null;
				this.forceAll = false;
			}
			this.debugSearch('doing search@' + center + ' for radius of ' + radius);
			if (center == null) {
				var center = this.gmap.getCenter();
			}
			if (radius == null) {
				var radius = 40000;
			}
			this.lastCenter = center;
			this.lastRadius = radius;
			if (tags == null) { tags = ''; }
			this.debugSearch('searching: ' + center.lat() +','+ center.lng());
			var name = this.saneValue('nameSearch', '');
			var action = null;
			if (realsearch) {
				action = {action:'csl_ajax_search',lat:center.lat(),lng:center.lng(),radius:radius, tags: tags, name:name, address:this.saneValue('addressInput', 'no address entered')};
			}
			else {
				action = {action:'csl_ajax_onload',lat:center.lat(),lng:center.lng(),tags:tags };
			}
			this.debugSearch(action);
			var _this = this;
			var ajax = new csl.Ajax();
			if (!realsearch) {
				ajax.send(action, function (response) {
					_this.dropMarkers.call(_this, response.response);
				});
			}
			else {
				ajax.send(action, function (response) {
					_this.bounceMarkers.call(_this, response.response);
				});
			}
		}
		
		/***************************
  	  	 * function: tagFilter
  	  	 * usage:
  	  	 * 		Sends an ajax request to only get the tags in the current search results
  	  	 * parameters:
		 *		none
  	  	 * returns: none
  	  	 */
		 this.tagFilter = function() {
			
			//repeat last search passing tags
			var tag_to_search_for = this.saneValue('tag_to_search_for', '');
			this.loadMarkers(this.lastCenter, this.lastRadius, tag_to_search_for);
			jQuery('#map_box_image').hide();
			jQuery('#map_box_map').show();
		 }
		
		/***************************
  	  	 * function: searchLocations
  	  	 * usage:
  	  	 * 		begins the process of returning search results
  	  	 * parameters:
  	  	 * 		none
  	  	 * returns: none
  	  	 */
		this.searchLocations = function() {
			var address = this.saneValue('addressInput', '');
            jQuery('#map_box_image').hide();
			jQuery('#map_box_map').show();
            google.maps.event.trigger(this.gmap, 'resize');
                
			// Address was given, use it...
			// 
			if (address != '') {
				this.address = cslutils.escapeExtended(address);
				this.doGeocode();
				
			}
			else {
				var tag_to_search_for = this.saneValue('tag_to_search_for', '');
				var radius = this.saneValue('radiusSelect');
				this.loadMarkers(this.gmap.getCenter(), radius, tag_to_search_for);
			}
		}
		
		/***************************
  	  	 * function: createSidebar
  	  	 * usage:
  	  	 * 		Builds to side bar
  	  	 * parameters:
  	  	 * 		aMarker: the marker data
  	  	 * returns: a html div with the data properly displayed
  	  	 */
		this.createSidebar = function(aMarker) { 
			document.getElementById('map_sidebar_td').style.display='block';
			var div = document.createElement('div');
			var link = '';
			var street = aMarker.address;
			var street2 = aMarker.address2;
			var city = aMarker.city;
			var state = aMarker.state;
			var zip = aMarker.zip;
			
            var url = this.__getMarkerUrl(aMarker);
            
			if (url != '') {
				link = link = "<a href='"+url+"' target='"+(slplus.use_same_window?'_self':'_blank')+"' class='storelocatorlink'><nobr>" + slplus.website_label +"</nobr></a><br/>"; 
			}
			
			var elink = '';
			if (aMarker.email.indexOf('@') != -1 && aMarker.email.indexOf('.') != -1) {
				if (!slplus.use_email_form) {
					elink = "<a href='mailto:"+aMarker.email+"' target='_blank' class='storelocatorlink'><nobr>" + aMarker.email +"</nobr></a><br/>";
				}
				else {
					elink = "<a href='javascript:cslutils.show_email_form("+'"'+aMarker.email+'"'+");' class='storelocatorlink'><nobr>" + aMarker.email +"</nobr></a><br/>";
				}
			}
			
			//if we are showing tags in the table
			//
			var tagInfo = '';
			if (slplus.show_tags) {
				if (jQuery.trim(aMarker.tags) != '') {
					var tagclass = aMarker.tags.replace(/\W/g,'_');
					tagInfo = '<br/><div class="'+tagclass+'"><span class="tagtext">'+aMarker.tags+'</span></div>';
				}
			}
			
			//keep empty data lines out of the final result
			//
			if (jQuery.trim(street) != '') { street = street + '<br/>'; }
			if (jQuery.trim(street2) != '') { street2 = street2 + '<br/>'; }
            var city_state_zip = '';
            if (jQuery.trim(city) != '') {
                city_state_zip += city;
                if (jQuery.trim(state) != '') {
                    city_state_zip += ', ';
                }
            }
            if (jQuery.trim(state) != '') {
                city_state_zip += state;
                if (jQuery.trim(zip) != '') {
                    city_state_zip += ', ';
                }
            }
            if (jQuery.trim(zip) != '') {
                city_state_zip += zip;
            }
            if (jQuery.trim(city_state_zip) != '') {
                city_state_zip += '<br/>';
            }
			
			var html =  '<center><table width="96%" cellpadding="4px" cellspacing="0" class="searchResultsTable">' +
					'<tr>' +
                    '<td class="results_row_left_column">' +
                        '<span class="location_name">' + aMarker.name + '</span><br>' + 
                        parseFloat(aMarker.distance).toFixed(1) + ' ' + slplus.distance_unit + '</td>' +
                    '<td class="results_row_center_column">' + 
                        street +  
                        street2 + 
                        city_state_zip +
                        aMarker.phone +
                    '</td>' +
                    '<td class="results_row_right_column">' + 
                        link + 
                        elink +
                        '<a href="http://' + slplus.map_domain + 
                        '/maps?saddr=' + encodeURIComponent(this.address) + 
                        '&daddr=' + encodeURIComponent(aMarker.address) + 
                        '" target="_blank" class="storelocatorlink">Directions</a>'+
                        tagInfo +
                        '</td>' +
                        '</tr></table></center>';
			div.innerHTML = html;
			div.className = 'results_entry';

			return div;
		}
  	  	  
  	  	//dumb browser quirk trick ... wasted two hours on that one
  	  	this.__init();
	}
}
 
//global vars
var cslmap;
var cslutils;
 
/***************************
 * function InitializeTheMap()
 *
 * Setup the map settings and get id rendered.
 *
 */
function InitializeTheMap() {
	cslutils = new csl.Utils();
	cslmap = new csl.Map();
	cslmap.doGeocode();
}

/* 
 * When the document has been loaded...
 *
 */
jQuery(document).ready(function(){
	InitializeTheMap();
});

