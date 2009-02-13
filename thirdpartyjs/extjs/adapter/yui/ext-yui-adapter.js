/*
 * Ext JS Library 1.1 Beta 1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://www.extjs.com/license
 */


Ext={};window["undefined"]=window["undefined"];Ext.apply=function(o,c,_3){if(_3){Ext.apply(o,_3);}if(o&&c&&typeof c=="object"){for(var p in c){o[p]=c[p];}}return o;};(function(){var _5=0;var ua=navigator.userAgent.toLowerCase();var _7=document.compatMode=="CSS1Compat",_8=ua.indexOf("opera")>-1,_9=(/webkit|khtml/).test(ua),_a=ua.indexOf("msie")>-1,_b=ua.indexOf("msie 7")>-1,_c=!_9&&ua.indexOf("gecko")>-1,_d=_a&&!_7,_e=(ua.indexOf("windows")!=-1||ua.indexOf("win32")!=-1),_f=(ua.indexOf("macintosh")!=-1||ua.indexOf("mac os x")!=-1),_10=window.location.href.toLowerCase().indexOf("https")===0;if(_a&&!_b){try{document.execCommand("BackgroundImageCache",false,true);}catch(e){}}Ext.apply(Ext,{isStrict:_7,isSecure:_10,isReady:false,enableGarbageCollector:true,enableListenerCollection:false,SSL_SECURE_URL:"javascript:false",BLANK_IMAGE_URL:"http:/"+"/extjs.com/s.gif",emptyFn:function(){},applyIf:function(o,c){if(o&&c){for(var p in c){if(typeof o[p]=="undefined"){o[p]=c[p];}}}return o;},addBehaviors:function(o){if(!Ext.isReady){Ext.onReady(function(){Ext.addBehaviors(o);});return;}var _15={};for(var b in o){var _17=b.split("@");if(_17[1]){var s=_17[0];if(!_15[s]){_15[s]=Ext.select(s);}_15[s].on(_17[1],o[b]);}}_15=null;},id:function(el,_1a){_1a=_1a||"ext-gen";el=Ext.getDom(el);var id=_1a+(++_5);return el?(el.id?el.id:(el.id=id)):id;},extend:function(){var io=function(o){for(var m in o){this[m]=o[m];}};return function(sb,sp,_21){if(typeof sp=="object"){_21=sp;sp=sb;sb=function(){sp.apply(this,arguments);};}var F=function(){},sbp,spp=sp.prototype;F.prototype=spp;sbp=sb.prototype=new F();sbp.constructor=sb;sb.superclass=spp;if(spp.constructor==Object.prototype.constructor){spp.constructor=sp;}sb.override=function(o){Ext.override(sb,o);};sbp.override=io;sbp.__extcls=sb;Ext.override(sb,_21);return sb;};}(),override:function(_26,_27){if(_27){var p=_26.prototype;for(var _29 in _27){p[_29]=_27[_29];}}},namespace:function(){var a=arguments,o=null,i,j,d,rt;for(i=0;i<a.length;++i){d=a[i].split(".");rt=d[0];eval("if (typeof "+rt+" == \"undefined\"){"+rt+" = {};} o = "+rt+";");for(j=1;j<d.length;++j){o[d[j]]=o[d[j]]||{};o=o[d[j]];}}},urlEncode:function(o){if(!o){return"";}var buf=[];for(var key in o){var ov=o[key];var _34=typeof ov;if(_34=="undefined"){buf.push(encodeURIComponent(key),"=&");}else{if(_34!="function"&&_34!="object"){buf.push(encodeURIComponent(key),"=",encodeURIComponent(ov),"&");}else{if(ov instanceof Array){for(var i=0,len=ov.length;i<len;i++){buf.push(encodeURIComponent(key),"=",encodeURIComponent(ov[i]===undefined?"":ov[i]),"&");}}}}}buf.pop();return buf.join("");},urlDecode:function(_37,_38){if(!_37||!_37.length){return{};}var obj={};var _3a=_37.split("&");var _3b,_3c,_3d;for(var i=0,len=_3a.length;i<len;i++){_3b=_3a[i].split("=");_3c=decodeURIComponent(_3b[0]);_3d=decodeURIComponent(_3b[1]);if(_38!==true){if(typeof obj[_3c]=="undefined"){obj[_3c]=_3d;}else{if(typeof obj[_3c]=="string"){obj[_3c]=[obj[_3c]];obj[_3c].push(_3d);}else{obj[_3c].push(_3d);}}}else{obj[_3c]=_3d;}}return obj;},each:function(_40,fn,_42){if(typeof _40.length=="undefined"||typeof _40=="string"){_40=[_40];}for(var i=0,len=_40.length;i<len;i++){if(fn.call(_42||_40[i],_40[i],i,_40)===false){return i;}}},combine:function(){var as=arguments,l=as.length,r=[];for(var i=0;i<l;i++){var a=as[i];if(a instanceof Array){r=r.concat(a);}else{if(a.length!==undefined&&!a.substr){r=r.concat(Array.prototype.slice.call(a,0));}else{r.push(a);}}}return r;},escapeRe:function(s){return s.replace(/([.*+?^${}()|[\]\/\\])/g,"\\$1");},callback:function(cb,_4c,_4d,_4e){if(typeof cb=="function"){if(_4e){cb.defer(_4e,_4c,_4d||[]);}else{cb.apply(_4c,_4d||[]);}}},getDom:function(el){if(!el){return null;}return el.dom?el.dom:(typeof el=="string"?document.getElementById(el):el);},getCmp:function(id){return Ext.ComponentMgr.get(id);},num:function(v,_52){if(typeof v!="number"){return _52;}return v;},destroy:function(){for(var i=0,a=arguments,len=a.length;i<len;i++){var as=a[i];if(as){if(as.dom){as.removeAllListeners();as.remove();continue;}if(typeof as.purgeListeners=="function"){as.purgeListeners();}if(typeof as.destroy=="function"){as.destroy();}}}},isOpera:_8,isSafari:_9,isIE:_a,isIE7:_b,isGecko:_c,isBorderBox:_d,isWindows:_e,isMac:_f,useShims:((_a&&!_b)||(_c&&_f))});})();Ext.namespace("Ext","Ext.util","Ext.grid","Ext.dd","Ext.tree","Ext.data","Ext.form","Ext.menu","Ext.state","Ext.lib","Ext.layout","Ext.app");Ext.apply(Function.prototype,{createCallback:function(){var _57=arguments;var _58=this;return function(){return _58.apply(window,_57);};},createDelegate:function(obj,_5a,_5b){var _5c=this;return function(){var _5d=_5a||arguments;if(_5b===true){_5d=Array.prototype.slice.call(arguments,0);_5d=_5d.concat(_5a);}else{if(typeof _5b=="number"){_5d=Array.prototype.slice.call(arguments,0);var _5e=[_5b,0].concat(_5a);Array.prototype.splice.apply(_5d,_5e);}}return _5c.apply(obj||window,_5d);};},defer:function(_5f,obj,_61,_62){var fn=this.createDelegate(obj,_61,_62);if(_5f){return setTimeout(fn,_5f);}fn();return 0;},createSequence:function(fcn,_65){if(typeof fcn!="function"){return this;}var _66=this;return function(){var _67=_66.apply(this||window,arguments);fcn.apply(_65||this||window,arguments);return _67;};},createInterceptor:function(fcn,_69){if(typeof fcn!="function"){return this;}var _6a=this;return function(){fcn.target=this;fcn.method=_6a;if(fcn.apply(_69||this||window,arguments)===false){return;}return _6a.apply(this||window,arguments);};}});Ext.applyIf(String,{escape:function(_6b){return _6b.replace(/('|\\)/g,"\\$1");},leftPad:function(val,_6d,ch){var _6f=new String(val);if(ch===null||ch===undefined||ch===""){ch=" ";}while(_6f.length<_6d){_6f=ch+_6f;}return _6f;},format:function(_70){var _71=Array.prototype.slice.call(arguments,1);return _70.replace(/\{(\d+)\}/g,function(m,i){return _71[i];});}});String.prototype.toggle=function(_74,_75){return this==_74?_75:_74;};Ext.applyIf(Number.prototype,{constrain:function(min,max){return Math.min(Math.max(this,min),max);}});Ext.applyIf(Array.prototype,{indexOf:function(o){for(var i=0,len=this.length;i<len;i++){if(this[i]==o){return i;}}return-1;},remove:function(o){var _7c=this.indexOf(o);if(_7c!=-1){this.splice(_7c,1);}}});Date.prototype.getElapsed=function(_7d){return Math.abs((_7d||new Date()).getTime()-this.getTime());};

if(typeof YAHOO=="undefined"){throw"Unable to load Ext, core YUI utilities (yahoo, dom, event) not found.";}(function(){var E=YAHOO.util.Event;var D=YAHOO.util.Dom;var CN=YAHOO.util.Connect;var ES=YAHOO.util.Easing;var A=YAHOO.util.Anim;var _6;Ext.lib.Dom={getViewWidth:function(_7){return _7?D.getDocumentWidth():D.getViewportWidth();},getViewHeight:function(_8){return _8?D.getDocumentHeight():D.getViewportHeight();},isAncestor:function(_9,_a){return D.isAncestor(_9,_a);},getRegion:function(el){return D.getRegion(el);},getY:function(el){return this.getXY(el)[1];},getX:function(el){return this.getXY(el)[0];},getXY:function(el){var p,pe,b,_12,bd=document.body;el=Ext.getDom(el);if(el.getBoundingClientRect){b=el.getBoundingClientRect();_12=fly(document).getScroll();return[b.left+_12.left,b.top+_12.top];}else{var x=el.offsetLeft,y=el.offsetTop;p=el.offsetParent;var _16=false;if(p!=el){while(p){x+=p.offsetLeft;y+=p.offsetTop;if(Ext.isSafari&&!_16&&fly(p).getStyle("position")=="absolute"){_16=true;}if(Ext.isGecko){pe=fly(p);var bt=parseInt(pe.getStyle("borderTopWidth"),10)||0;var bl=parseInt(pe.getStyle("borderLeftWidth"),10)||0;x+=bl;y+=bt;if(p!=el&&pe.getStyle("overflow")!="visible"){x+=bl;y+=bt;}}p=p.offsetParent;}}if(Ext.isSafari&&(_16||fly(el).getStyle("position")=="absolute")){x-=bd.offsetLeft;y-=bd.offsetTop;}}p=el.parentNode;while(p&&p!=bd){if(!Ext.isOpera||(Ext.isOpera&&p.tagName!="TR"&&fly(p).getStyle("display")!="inline")){x-=p.scrollLeft;y-=p.scrollTop;}if(Ext.isGecko){pe=fly(p);if(pe.getStyle("overflow")!="visible"){x+=parseInt(pe.getStyle("borderLeftWidth"),10)||0;y+=parseInt(pe.getStyle("borderTopWidth"),10)||0;}}p=p.parentNode;}return[x,y];},setXY:function(el,xy){el=Ext.fly(el,"_setXY");el.position();var pts=el.translatePoints(xy);if(xy[0]!==false){el.dom.style.left=pts.left+"px";}if(xy[1]!==false){el.dom.style.top=pts.top+"px";}},setX:function(el,x){this.setXY(el,[x,false]);},setY:function(el,y){this.setXY(el,[false,y]);}};Ext.lib.Event={getPageX:function(e){return E.getPageX(e.browserEvent||e);},getPageY:function(e){return E.getPageY(e.browserEvent||e);},getXY:function(e){return E.getXY(e.browserEvent||e);},getTarget:function(e){return E.getTarget(e.browserEvent||e);},getRelatedTarget:function(e){return E.getRelatedTarget(e.browserEvent||e);},on:function(el,_26,fn,_28,_29){E.on(el,_26,fn,_28,_29);},un:function(el,_2b,fn){E.removeListener(el,_2b,fn);},purgeElement:function(el){E.purgeElement(el);},preventDefault:function(e){E.preventDefault(e.browserEvent||e);},stopPropagation:function(e){E.stopPropagation(e.browserEvent||e);},stopEvent:function(e){E.stopEvent(e.browserEvent||e);},onAvailable:function(el,fn,_33,_34){return E.onAvailable(el,fn,_33,_34);}};Ext.lib.Ajax={request:function(_35,uri,cb,_38,_39){if(_39&&_39.headers){var hs=_39.headers;for(var h in hs){if(hs.hasOwnProperty(h)){CN.initHeader(h,hs[h],false);}}}return CN.asyncRequest(_35,uri,cb,_38);},formRequest:function(_3c,uri,cb,_3f,_40,_41){CN.setForm(_3c,_40,_41);return CN.asyncRequest(Ext.getDom(_3c).method||"POST",uri,cb,_3f);},isCallInProgress:function(_42){return CN.isCallInProgress(_42);},abort:function(_43){return CN.abort(_43);},serializeForm:function(_44){var d=CN.setForm(_44.dom||_44);CN.resetFormState();return d;}};Ext.lib.Region=YAHOO.util.Region;Ext.lib.Point=YAHOO.util.Point;Ext.lib.Anim={scroll:function(el,_47,_48,_49,cb,_4b){this.run(el,_47,_48,_49,cb,_4b,YAHOO.util.Scroll);},motion:function(el,_4d,_4e,_4f,cb,_51){this.run(el,_4d,_4e,_4f,cb,_51,YAHOO.util.Motion);},color:function(el,_53,_54,_55,cb,_57){this.run(el,_53,_54,_55,cb,_57,YAHOO.util.ColorAnim);},run:function(el,_59,_5a,_5b,cb,_5d,_5e){_5e=_5e||YAHOO.util.Anim;if(typeof _5b=="string"){_5b=YAHOO.util.Easing[_5b];}var _5f=new _5e(el,_59,_5a,_5b);_5f.animateX(function(){Ext.callback(cb,_5d);});return _5f;}};function fly(el){if(!_6){_6=new Ext.Element.Flyweight();}_6.dom=el;return _6;}if(Ext.isIE){YAHOO.util.Event.on(window,"unload",function(){var p=Function.prototype;delete p.createSequence;delete p.defer;delete p.createDelegate;delete p.createCallback;delete p.createInterceptor;});}if(YAHOO.util.Anim){YAHOO.util.Anim.prototype.animateX=function(_62,_63){var f=function(){this.onComplete.unsubscribe(f);if(typeof _62=="function"){_62.call(_63||this,this);}};this.onComplete.subscribe(f,this,true);this.animate();};}if(YAHOO.util.DragDrop&&Ext.dd.DragDrop){YAHOO.util.DragDrop.defaultPadding=Ext.dd.DragDrop.defaultPadding;YAHOO.util.DragDrop.constrainTo=Ext.dd.DragDrop.constrainTo;}YAHOO.util.Dom.getXY=function(el){var f=function(el){return Ext.lib.Dom.getXY(el);};return YAHOO.util.Dom.batch(el,f,YAHOO.util.Dom,true);};if(YAHOO.util.AnimMgr){YAHOO.util.AnimMgr.fps=1000;}YAHOO.util.Region.prototype.adjust=function(t,l,b,r){this.top+=t;this.left+=l;this.right+=r;this.bottom+=b;return this;};})();
