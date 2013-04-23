ColorPicker._pluginInfo={name:"colorPicker",version:"$LastChangedRevision:998 $".replace(/^[^:]*:\s*(.*)\s*\$$/,"$1"),developer:"James Sleeman",developer_url:"http://www.gogo.co.nz/",c_owner:"Gogo Internet Services",license:"htmlArea",sponsor:"Gogo Internet Services",sponsor_url:"http://www.gogo.co.nz/"};function ColorPicker(){}try{if(window.opener&&window.opener.Xinha){var openerColorPicker=window.opener.Xinha.colorPicker;Xinha._addEvent(window,"unload",function(){Xinha.colorPicker=openerColorPicker})}}catch(e){}Xinha.colorPicker=function(M){if(Xinha.colorPicker.savedColors.length===0){Xinha.colorPicker.loadColors()}this.is_ie_6=(Xinha.is_ie&&Xinha.ie_version<7);var L=this;var K=false;var I=false;var H=0;var G=0;this.callback=M.callback?M.callback:function(W){alert("You picked "+W)};this.websafe=M.websafe?M.websafe:false;this.savecolors=M.savecolors?M.savecolors:20;this.cellsize=parseInt(M.cellsize?M.cellsize:"10px",10);this.side=M.granularity?M.granularity:18;var F=this.side+1;var D=this.side-1;this.value=1;this.saved_cells=null;this.table=document.createElement("table");this.table.className="dialog";this.table.cellSpacing=this.table.cellPadding=0;this.table.onmouseup=function(){K=false;I=false};this.tbody=document.createElement("tbody");this.table.appendChild(this.tbody);this.table.style.border="1px solid WindowFrame";this.table.style.zIndex="1050";var B=document.createElement("tr");var N=document.createElement("td");N.colSpan=this.side;N.className="title";N.style.fontFamily="small-caption,caption,sans-serif";N.style.fontSize="x-small";N.unselectable="on";N.style.MozUserSelect="none";N.style.cursor="default";N.appendChild(document.createTextNode(Xinha._lc("Click a color...")));N.style.borderBottom="1px solid WindowFrame";B.appendChild(N);N=null;var N=document.createElement("td");N.className="title";N.colSpan=2;N.style.fontFamily="Tahoma,Verdana,sans-serif";N.style.borderBottom="1px solid WindowFrame";N.style.paddingRight="0";B.appendChild(N);var J=document.createElement("div");J.title=Xinha._lc("Close");J.className="buttonColor";J.style.height="11px";J.style.width="11px";J.style.cursor="pointer";J.onclick=function(){L.close()};J.appendChild(document.createTextNode("\xd7"));J.align="center";J.style.verticalAlign="top";J.style.position="relative";J.style.cssFloat="right";J.style.styleFloat="right";J.style.padding="0";J.style.margin="2px";J.style.backgroundColor="transparent";J.style.fontSize="11px";if(!Xinha.is_ie){J.style.lineHeight="9px"}J.style.letterSpacing="0";N.appendChild(J);this.tbody.appendChild(B);J=B=N=null;this.constrain_cb=document.createElement("input");this.constrain_cb.type="checkbox";this.chosenColor=document.createElement("input");this.chosenColor.type="text";this.chosenColor.maxLength=7;this.chosenColor.style.width="50px";this.chosenColor.style.fontSize="11px";this.chosenColor.onchange=function(){if(/#[0-9a-f]{6,6}/i.test(this.value)){L.backSample.style.backgroundColor=this.value;L.foreSample.style.color=this.value}};this.backSample=document.createElement("div");this.backSample.appendChild(document.createTextNode("\xa0"));this.backSample.style.fontWeight="bold";this.backSample.style.fontFamily="small-caption,caption,sans-serif";this.backSample.fontSize="x-small";this.foreSample=document.createElement("div");this.foreSample.appendChild(document.createTextNode(Xinha._lc("Sample")));this.foreSample.style.fontWeight="bold";this.foreSample.style.fontFamily="small-caption,caption,sans-serif";this.foreSample.fontSize="x-small";function S(W){var X=W.toString(16);if(X.length<2){X="0"+X}return X}function R(W){return"#"+S(W.red)+S(W.green)+S(W.blue)}function V(W,X){return Math.round(Math.round(W/X)*X)}function E(W){return parseInt(W.toString(16)+W.toString(16),16)}function U(W){W.red=E(V(parseInt(S(W.red).charAt(0),16),3));W.blue=E(V(parseInt(S(W.blue).charAt(0),16),3));W.green=E(V(parseInt(S(W.green).charAt(0),16),3));return W}function P(a,g,c){var Y;if(g===0){Y={red:c,green:c,blue:c}}else{a/=60;var Z=Math.floor(a);var b=a-Z;var X=c*(1-g);var W=c*(1-g*b);var d=c*(1-g*(1-b));switch(Z){case 0:Y={red:c,green:d,blue:X};break;case 1:Y={red:W,green:c,blue:X};break;case 2:Y={red:X,green:c,blue:d};break;case 3:Y={red:X,green:W,blue:c};break;case 4:Y={red:d,green:X,blue:c};break;default:Y={red:c,green:X,blue:W};break}}Y.red=Math.ceil(Y.red*255);Y.green=Math.ceil(Y.green*255);Y.blue=Math.ceil(Y.blue*255);return Y}var C=this;function A(W){W=W?W:window.event;el=W.target?W.target:W.srcElement;do{if(el==C.table){return}}while(el=el.parentNode);C.close()}this.open=function(Y,Z,X){this.table.style.display="";this.pick_color();if(X&&/#[0-9a-f]{6,6}/i.test(X)){this.chosenColor.value=X;this.backSample.style.backgroundColor=X;this.foreSample.style.color=X}Xinha._addEvent(document.body,"mousedown",A);this.table.style.position="absolute";var c=Z;var b=0;var a=0;do{if(c.style.position=="fixed"){this.table.style.position="fixed"}b+=c.offsetTop;a+=c.offsetLeft;c=c.offsetParent}while(c);var W,d;if(/top/.test(Y)||(b+this.table.offsetHeight>document.body.offsetHeight)){if(b-this.table.offsetHeight>0){this.table.style.top=(b-this.table.offsetHeight)+"px"}else{this.table.style.top=0}}else{this.table.style.top=(b+Z.offsetHeight)+"px"}if(/left/.test(Y)||(a+this.table.offsetWidth>document.body.offsetWidth)){if(a-(this.table.offsetWidth-Z.offsetWidth)>0){this.table.style.left=(a-(this.table.offsetWidth-Z.offsetWidth))+"px"}else{this.table.style.left=0}}else{this.table.style.left=a+"px"}if(this.is_ie_6){this.iframe.style.top=this.table.style.top;this.iframe.style.left=this.table.style.left}};function Q(W){L.chosenColor.value=W.colorCode;L.backSample.style.backgroundColor=W.colorCode;L.foreSample.style.color=W.colorCode;if((W.hue>=195&&W.saturation>0.5)||(W.hue===0&&W.saturation===0&&W.value<0.5)||(W.hue!==0&&L.value<0.75)){W.style.borderColor="#fff"}else{W.style.borderColor="#000"}H=W.thisrow;G=W.thiscol}function O(W){if(L.value<0.5){W.style.borderColor="#fff"}else{W.style.borderColor="#000"}D=W.thisrow;F=W.thiscol;L.chosenColor.value=L.saved_cells[H][G].colorCode;L.backSample.style.backgroundColor=L.saved_cells[H][G].colorCode;L.foreSample.style.color=L.saved_cells[H][G].colorCode}function T(X,W){L.saved_cells[X][W].style.borderColor=L.saved_cells[X][W].colorCode}this.pick_color=function(){var k,i;var j=this;var h=359/(this.side);var g=1/(this.side-1);var f=1/(this.side-1);var d=this.constrain_cb.checked;if(this.saved_cells===null){this.saved_cells=[];for(var a=0;a<this.side;a++){var W=document.createElement("tr");this.saved_cells[a]=[];for(var Z=0;Z<this.side;Z++){var c=document.createElement("td");if(d){c.colorCode=R(U(P(h*a,g*Z,this.value)))}else{c.colorCode=R(P(h*a,g*Z,this.value))}this.saved_cells[a][Z]=c;c.style.height=this.cellsize+"px";c.style.width=this.cellsize-2+"px";c.style.borderWidth="1px";c.style.borderStyle="solid";c.style.borderColor=c.colorCode;c.style.backgroundColor=c.colorCode;if(a==H&&Z==G){c.style.borderColor="#000";this.chosenColor.value=c.colorCode;this.backSample.style.backgroundColor=c.colorCode;this.foreSample.style.color=c.colorCode}c.hue=h*a;c.saturation=g*Z;c.thisrow=a;c.thiscol=Z;c.onmousedown=function(){K=true;j.saved_cells[H][G].style.borderColor=j.saved_cells[H][G].colorCode;Q(this)};c.onmouseover=function(){if(K){Q(this)}};c.onmouseout=function(){if(K){this.style.borderColor=this.colorCode}};c.ondblclick=function(){Xinha.colorPicker.remember(this.colorCode,j.savecolors);j.callback(this.colorCode);j.close()};c.appendChild(document.createTextNode(" "));c.style.cursor="pointer";W.appendChild(c);c=null}var c=document.createElement("td");c.appendChild(document.createTextNode(" "));c.style.width=this.cellsize+"px";W.appendChild(c);c=null;var c=document.createElement("td");this.saved_cells[a][Z+1]=c;c.appendChild(document.createTextNode(" "));c.style.width=this.cellsize-2+"px";c.style.height=this.cellsize+"px";c.constrainedColorCode=R(U(P(0,0,f*a)));c.style.backgroundColor=c.colorCode=R(P(0,0,f*a));c.style.borderWidth="1px";c.style.borderStyle="solid";c.style.borderColor=c.colorCode;if(a==D){c.style.borderColor="black"}c.hue=h*a;c.saturation=g*Z;c.hsv_value=f*a;c.thisrow=a;c.thiscol=Z+1;c.onmousedown=function(){I=true;j.saved_cells[D][F].style.borderColor=j.saved_cells[D][F].colorCode;j.value=this.hsv_value;j.pick_color();O(this)};c.onmouseover=function(){if(I){j.value=this.hsv_value;j.pick_color();O(this)}};c.onmouseout=function(){if(I){this.style.borderColor=this.colorCode}};c.style.cursor="pointer";W.appendChild(c);c=null;this.tbody.appendChild(W);W=null}var W=document.createElement("tr");this.saved_cells[a]=[];for(var Z=0;Z<this.side;Z++){var c=document.createElement("td");if(d){c.colorCode=R(U(P(0,0,f*(this.side-Z-1))))}else{c.colorCode=R(P(0,0,f*(this.side-Z-1)))}this.saved_cells[a][Z]=c;c.style.height=this.cellsize+"px";c.style.width=this.cellsize-2+"px";c.style.borderWidth="1px";c.style.borderStyle="solid";c.style.borderColor=c.colorCode;c.style.backgroundColor=c.colorCode;c.hue=0;c.saturation=0;c.value=f*(this.side-Z-1);c.thisrow=a;c.thiscol=Z;c.onmousedown=function(){K=true;j.saved_cells[H][G].style.borderColor=j.saved_cells[H][G].colorCode;Q(this)};c.onmouseover=function(){if(K){Q(this)}};c.onmouseout=function(){if(K){this.style.borderColor=this.colorCode}};c.ondblclick=function(){Xinha.colorPicker.remember(this.colorCode,j.savecolors);j.callback(this.colorCode);j.close()};c.appendChild(document.createTextNode(" "));c.style.cursor="pointer";W.appendChild(c);c=null}this.tbody.appendChild(W);W=null;var W=document.createElement("tr");var c=document.createElement("td");W.appendChild(c);c.colSpan=this.side+2;c.style.padding="3px";if(this.websafe){var l=document.createElement("div");var q=document.createElement("label");q.appendChild(document.createTextNode(Xinha._lc("Web Safe: ")));this.constrain_cb.onclick=function(){j.pick_color()};q.appendChild(this.constrain_cb);q.style.fontFamily="small-caption,caption,sans-serif";q.style.fontSize="x-small";l.appendChild(q);c.appendChild(l);l=null}var l=document.createElement("div");var q=document.createElement("label");q.style.fontFamily="small-caption,caption,sans-serif";q.style.fontSize="x-small";q.appendChild(document.createTextNode(Xinha._lc("Color: ")));q.appendChild(this.chosenColor);l.appendChild(q);var m=document.createElement("span");m.className="buttonColor ";m.style.fontSize="13px";m.style.width="24px";m.style.marginLeft="2px";m.style.padding="0px 4px";m.style.cursor="pointer";m.onclick=function(){Xinha.colorPicker.remember(j.chosenColor.value,j.savecolors);j.callback(j.chosenColor.value);j.close()};m.appendChild(document.createTextNode(Xinha._lc("OK")));m.align="center";l.appendChild(m);c.appendChild(l);var p=document.createElement("table");p.style.width="100%";var o=document.createElement("tbody");p.appendChild(o);var n=document.createElement("tr");o.appendChild(n);var t=document.createElement("td");n.appendChild(t);t.appendChild(this.backSample);t.style.width="50%";var s=document.createElement("td");n.appendChild(s);s.appendChild(this.foreSample);s.style.width="50%";c.appendChild(p);var r=document.createElement("div");r.style.clear="both";function b(v){var u=Xinha.is_ie;var w=document.createElement("div");w.style.width=j.cellsize+"px";w.style.height=j.cellsize+"px";w.style.margin="1px";w.style.border="1px solid black";w.style.cursor="pointer";w.style.backgroundColor=v;w.style[u?"styleFloat":"cssFloat"]="left";w.ondblclick=function(){j.callback(v);j.close()};w.onclick=function(){j.chosenColor.value=v;j.backSample.style.backgroundColor=v;j.foreSample.style.color=v};r.appendChild(w)}for(var Y=0;Y<Xinha.colorPicker.savedColors.length;Y++){b(Xinha.colorPicker.savedColors[Y])}c.appendChild(r);this.tbody.appendChild(W);document.body.appendChild(this.table);if(this.is_ie_6){if(!this.iframe){this.iframe=document.createElement("iframe");this.iframe.frameBorder=0;this.iframe.src="javascript:;";this.iframe.style.position="absolute";this.iframe.style.width=this.table.offsetWidth;this.iframe.style.height=this.table.offsetHeight;document.body.insertBefore(this.iframe,this.table)}this.iframe.style.display=""}}else{for(var a=0;a<this.side;a++){for(var Z=0;Z<this.side;Z++){if(d){this.saved_cells[a][Z].colorCode=R(U(P(h*a,g*Z,this.value)))}else{this.saved_cells[a][Z].colorCode=R(P(h*a,g*Z,this.value))}this.saved_cells[a][Z].style.backgroundColor=this.saved_cells[a][Z].colorCode;this.saved_cells[a][Z].style.borderColor=this.saved_cells[a][Z].colorCode}}var X=this.saved_cells[H][G];this.chosenColor.value=X.colorCode;this.backSample.style.backgroundColor=X.colorCode;this.foreSample.style.color=X.colorCode;if((X.hue>=195&&X.saturation>0.5)||(X.hue===0&&X.saturation===0&&X.value<0.5)||(X.hue!==0&&j.value<0.75)){X.style.borderColor="#fff"}else{X.style.borderColor="#000"}}};this.close=function(){Xinha._removeEvent(document.body,"mousedown",A);this.table.style.display="none";if(this.is_ie_6){if(this.iframe){this.iframe.style.display="none"}}}};Xinha.colorPicker.savedColors=[];Xinha.colorPicker.remember=function(C,B){for(var D=Xinha.colorPicker.savedColors.length;D--;){if(Xinha.colorPicker.savedColors[D]==C){return false}}Xinha.colorPicker.savedColors.splice(0,0,C);Xinha.colorPicker.savedColors=Xinha.colorPicker.savedColors.slice(0,B);var A=new Date();A.setMonth(A.getMonth()+1);document.cookie="XinhaColorPicker="+escape(Xinha.colorPicker.savedColors.join("-"))+";expires="+A.toGMTString();return true};Xinha.colorPicker.loadColors=function(){var C=document.cookie.indexOf("XinhaColorPicker");if(C!=-1){var B=(document.cookie.indexOf("=",C)+1);var A=document.cookie.indexOf(";",C);if(A==-1){A=document.cookie.length}Xinha.colorPicker.savedColors=unescape(document.cookie.substring(B,A)).split("-")}};Xinha.colorPicker.InputBinding=function(C,A){var E=C.ownerDocument;var F=E.createElement("span");F.className="buttonColor";var D=this.chooser=E.createElement("span");D.className="chooser";if(C.value){D.style.backgroundColor=C.value}D.onmouseover=function(){D.className="chooser buttonColor-hilite"};D.onmouseout=function(){D.className="chooser"};D.appendChild(E.createTextNode("\xa0"));F.appendChild(D);var B=E.createElement("span");B.className="nocolor";B.onmouseover=function(){B.className="nocolor buttonColor-hilite";B.style.color="#f00"};B.onmouseout=function(){B.className="nocolor";B.style.color="#000"};B.onclick=function(){C.value="";D.style.backgroundColor=""};B.appendChild(E.createTextNode("\xd7"));F.appendChild(B);C.parentNode.insertBefore(F,C.nextSibling);Xinha._addEvent(C,"change",function(){D.style.backgroundColor=this.value});A=(A)?Xinha.cloneObject(A):{cellsize:"5px"};A.callback=(A.callback)?A.callback:function(G){D.style.backgroundColor=G;C.value=G};D.onclick=function(){var G=new Xinha.colorPicker(A);G.open("",D,C.value)};Xinha.freeLater(this,"chooser")};Xinha.colorPicker.InputBinding.prototype.setColor=function(A){this.chooser.style.backgroundColor=A};