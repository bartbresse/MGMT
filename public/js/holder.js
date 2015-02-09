var Holder=Holder||{};(function(e,t){function c(e,t,n){t=parseInt(t,10);e=parseInt(e,10);var r=Math.max(t,e);var i=Math.min(t,e);var s=1/12;var o=Math.min(i*.75,.75*r*s);return{height:Math.round(Math.max(n.size,o))}}function d(e){var t=[];for(p in e){if(e.hasOwnProperty(p)){t.push(p+":"+e[p])}}return t.join(";")}function v(e){var t=e.ctx,n=e.dimensions,r=e.template,i=e.ratio,s=e.holder,o=s.textmode=="literal",u=s.textmode=="exact";var a=c(n.width,n.height,r);var f=a.height;var l=n.width*i,h=n.height*i;var p=r.font?r.font:"Arial,Helvetica,sans-serif";canvas.width=l;canvas.height=h;t.textAlign="center";t.textBaseline="middle";t.fillStyle=r.background;t.fillRect(0,0,l,h);t.fillStyle=r.foreground;t.font="bold "+f+"px "+p;var d=r.text?r.text:Math.floor(n.width)+"x"+Math.floor(n.height);if(o){var n=s.dimensions;d=n.width+"x"+n.height}else if(u&&s.exact_dimensions){var n=s.exact_dimensions;d=Math.floor(n.width)+"x"+Math.floor(n.height)}var v=t.measureText(d).width;if(v/l>=.75){f=Math.floor(f*.75*(l/v))}t.font="bold "+f*i+"px "+p;t.fillText(d,l/2,h/2,l);return canvas.toDataURL("image/png")}function m(e){var t=e.dimensions,n=e.template,r=e.holder,i=r.textmode=="literal",s=r.textmode=="exact";var o=c(t.width,t.height,n);var u=o.height;var a=t.width,f=t.height;var l=n.font?n.font:"Arial,Helvetica,sans-serif";var p=n.text?n.text:Math.floor(t.width)+"x"+Math.floor(t.height);if(i){var t=r.dimensions;p=t.width+"x"+t.height}else if(s&&r.exact_dimensions){var t=r.exact_dimensions;p=Math.floor(t.width)+"x"+Math.floor(t.height)}var d=h({text:p,width:a,height:f,text_height:u,font:l,template:n});return"data:image/svg+xml;base64,"+btoa(unescape(encodeURIComponent(d)))}function g(e){if(r.use_canvas&&!r.use_svg){return v(e)}else{return m(e)}}function y(e,t,n,i){var s=n.dimensions,o=n.theme,l=n.text?decodeURIComponent(n.text):n.text;var c=s.width+"x"+s.height;o=l?C(o,{text:l}):o;o=n.font?C(o,{font:n.font}):o;t.setAttribute("data-src",i);n.theme=o;t.holder_data=n;if(e=="image"){t.setAttribute("alt",l?l:o.text?o.text+" ["+c+"]":c);if(r.use_fallback||!n.auto){t.style.width=s.width+"px";t.style.height=s.height+"px"}if(r.use_fallback){t.style.backgroundColor=o.background}else{t.setAttribute("src",g({ctx:a,dimensions:s,template:o,ratio:f,holder:n}));if(n.textmode&&n.textmode=="exact"){u.push(t);E(t)}}}else if(e=="background"){if(!r.use_fallback){t.style.backgroundImage="url("+g({ctx:a,dimensions:s,template:o,ratio:f,holder:n})+")";t.style.backgroundSize=s.width+"px "+s.height+"px"}}else if(e=="fluid"){t.setAttribute("alt",l?l:o.text?o.text+" ["+c+"]":c);if(s.height.slice(-1)=="%"){t.style.height=s.height}else if(n.auto==null||!n.auto){t.style.height=s.height+"px"}if(s.width.slice(-1)=="%"){t.style.width=s.width}else if(n.auto==null||!n.auto){t.style.width=s.width+"px"}if(t.style.display=="inline"||t.style.display===""||t.style.display=="none"){t.style.display="block"}w(t);if(r.use_fallback){t.style.backgroundColor=o.background}else{u.push(t);E(t)}}}function b(e,t){var n={height:e.clientHeight,width:e.clientWidth};if(!n.height&&!n.width){e.setAttribute("data-holder-invisible",true);t.call(this,e)}else{e.removeAttribute("data-holder-invisible");return n}}function w(t){if(t.holder_data){var n=b(t,e.invisible_error_fn(w));if(n){var r=t.holder_data;r.initial_dimensions=n;r.fluid_data={fluid_height:r.dimensions.height.slice(-1)=="%",fluid_width:r.dimensions.width.slice(-1)=="%",mode:null};if(r.fluid_data.fluid_width&&!r.fluid_data.fluid_height){r.fluid_data.mode="width";r.fluid_data.ratio=r.initial_dimensions.width/parseFloat(r.dimensions.height)}else if(!r.fluid_data.fluid_width&&r.fluid_data.fluid_height){r.fluid_data.mode="height";r.fluid_data.ratio=parseFloat(r.dimensions.width)/r.initial_dimensions.height}}}}function E(t){var n;if(t.nodeType==null){n=u}else{n=[t]}for(var r in n){if(!n.hasOwnProperty(r)){continue}var i=n[r];if(i.holder_data){var s=i.holder_data;var o=b(i,e.invisible_error_fn(E));if(o){if(s.fluid){if(s.auto){switch(s.fluid_data.mode){case"width":o.height=o.width/s.fluid_data.ratio;break;case"height":o.width=o.height*s.fluid_data.ratio;break}}i.setAttribute("src",g({ctx:a,dimensions:o,template:s.theme,ratio:f,holder:s}))}if(s.textmode&&s.textmode=="exact"){s.exact_dimensions=o;i.setAttribute("src",g({ctx:a,dimensions:s.dimensions,template:s.theme,ratio:f,holder:s}))}}}}}function S(t,n){var r={theme:C(l.themes.gray,{})};var i=false;for(var s=t.length,o=0;o<s;o++){var u=t[o];if(e.flags.dimensions.match(u)){i=true;r.dimensions=e.flags.dimensions.output(u)}else if(e.flags.fluid.match(u)){i=true;r.dimensions=e.flags.fluid.output(u);r.fluid=true}else if(e.flags.textmode.match(u)){r.textmode=e.flags.textmode.output(u)}else if(e.flags.colors.match(u)){r.theme=e.flags.colors.output(u)}else if(n.themes[u]){if(n.themes.hasOwnProperty(u)){r.theme=C(n.themes[u],{})}}else if(e.flags.font.match(u)){r.font=e.flags.font.output(u)}else if(e.flags.auto.match(u)){r.auto=true}else if(e.flags.text.match(u)){r.text=e.flags.text.output(u)}}return i?r:false}function T(e,t){var n="complete",r="readystatechange",i=!1,s=i,o=!0,u=e.document,a=u.documentElement,f=u.addEventListener?"addEventListener":"attachEvent",l=u.addEventListener?"removeEventListener":"detachEvent",c=u.addEventListener?"":"on",h=function(o){(o.type!=r||u.readyState==n)&&((o.type=="load"?e:u)[l](c+o.type,h,i),!s&&(s=!0)&&t.call(e,null))},p=function(){try{a.doScroll("left")}catch(e){setTimeout(p,50);return}h("poll")};if(u.readyState==n)t.call(e,"lazy");else{if(u.createEventObject&&a.doScroll){try{o=!e.frameElement}catch(d){}o&&p()}u[f](c+"DOMContentLoaded",h,i),u[f](c+r,h,i),e[f](c+"load",h,i)}}function N(e,t){var e=e.match(/^(\W)?(.*)/),t=t||document,n=t["getElement"+(e[1]?"#"==e[1]?"ById":"sByClassName":"sByTagName")],r=n.call(t,e[2]),i=[];return null!==r&&(i=r.length||0===r.length?r:[r]),i}function C(e,t){var n={};for(var r in e){if(e.hasOwnProperty(r)){n[r]=e[r]}}for(var r in t){if(t.hasOwnProperty(r)){n[r]=t[r]}}return n}var n={use_svg:false,use_canvas:false,use_fallback:false};var r={};var i=false;canvas=document.createElement("canvas");var s=1,o=1;var u=[];if(!canvas.getContext){n.use_fallback=true}else{if(canvas.toDataURL("image/png").indexOf("data:image/png")<0){n.use_fallback=true}else{var a=canvas.getContext("2d")}}if(!!document.createElementNS&&!!document.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect){n.use_svg=true;n.use_canvas=false}if(!n.use_fallback){s=window.devicePixelRatio||1,o=a.webkitBackingStorePixelRatio||a.mozBackingStorePixelRatio||a.msBackingStorePixelRatio||a.oBackingStorePixelRatio||a.backingStorePixelRatio||1}var f=s/o;var l={domain:"holder.js",images:"img",bgnodes:".holderjs",themes:{gray:{background:"#eee",foreground:"#aaa",size:12},social:{background:"#3a5a97",foreground:"#fff",size:12},industrial:{background:"#434A52",foreground:"#C2F200",size:12},sky:{background:"#0D8FDB",foreground:"#fff",size:12},vine:{background:"#39DBAC",foreground:"#1E292C",size:12},lava:{background:"#F8591A",foreground:"#1C2846",size:12}},stylesheet:""};e.flags={dimensions:{regex:/^(\d+)x(\d+)$/,output:function(e){var t=this.regex.exec(e);return{width:+t[1],height:+t[2]}}},fluid:{regex:/^([0-9%]+)x([0-9%]+)$/,output:function(e){var t=this.regex.exec(e);return{width:t[1],height:t[2]}}},colors:{regex:/#([0-9a-f]{3,})\:#([0-9a-f]{3,})/i,output:function(e){var t=this.regex.exec(e);return{size:l.themes.gray.size,foreground:"#"+t[2],background:"#"+t[1]}}},text:{regex:/text\:(.*)/,output:function(e){return this.regex.exec(e)[1]}},font:{regex:/font\:(.*)/,output:function(e){return this.regex.exec(e)[1]}},auto:{regex:/^auto$/},textmode:{regex:/textmode\:(.*)/,output:function(e){return this.regex.exec(e)[1]}}};var h=function(){if(!window.XMLSerializer)return;var e=new XMLSerializer;var t="http://www.w3.org/2000/svg";var n=document.createElementNS(t,"svg");if(n.webkitMatchesSelector){n.setAttribute("xmlns","http://www.w3.org/2000/svg")}var r=document.createElementNS(t,"rect");var i=document.createElementNS(t,"text");var s=document.createTextNode(null);i.setAttribute("text-anchor","middle");i.appendChild(s);n.appendChild(r);n.appendChild(i);return function(t){n.setAttribute("width",t.width);n.setAttribute("height",t.height);r.setAttribute("width",t.width);r.setAttribute("height",t.height);r.setAttribute("fill",t.template.background);i.setAttribute("x",t.width/2);i.setAttribute("y",t.height/2);s.nodeValue=t.text;i.setAttribute("style",d({fill:t.template.foreground,"font-weight":"bold","font-size":t.text_height+"px","font-family":t.font,"dominant-baseline":"central"}));return e.serializeToString(n)}}();for(var x in e.flags){if(!e.flags.hasOwnProperty(x))continue;e.flags[x].match=function(e){return e.match(this.regex)}}e.invisible_error_fn=function(e){return function(e){if(e.hasAttribute("data-holder-invisible")){throw new Error("Holder: invisible placeholder")}}};e.add_theme=function(t,n){t!=null&&n!=null&&(l.themes[t]=n);return e};e.add_image=function(t,n){var r=N(n);if(r.length){for(var i=0,s=r.length;i<s;i++){var o=document.createElement("img");o.setAttribute("data-src",t);r[i].appendChild(o)}}return e};e.run=function(t){r=C({},n);i=true;var s=C(l,t),o=[],u=[],a=[];if(s.use_canvas!=null&&s.use_canvas){r.use_canvas=true;r.use_svg=false}if(typeof s.images=="string"){u=N(s.images)}else if(window.NodeList&&s.images instanceof window.NodeList){u=s.images}else if(window.Node&&s.images instanceof window.Node){u=[s.images]}else if(window.HTMLCollection&&s.images instanceof window.HTMLCollection){u=s.images}if(typeof s.bgnodes=="string"){a=N(s.bgnodes)}else if(window.NodeList&&s.elements instanceof window.NodeList){a=s.bgnodes}else if(window.Node&&s.bgnodes instanceof window.Node){a=[s.bgnodes]}for(p=0,h=u.length;p<h;p++)o.push(u[p]);var f=document.getElementById("holderjs-style");if(!f){f=document.createElement("style");f.setAttribute("id","holderjs-style");f.type="text/css";document.getElementsByTagName("head")[0].appendChild(f)}if(!s.nocss){if(f.styleSheet){f.styleSheet.cssText+=s.stylesheet}else{if(s.stylesheet.length){f.appendChild(document.createTextNode(s.stylesheet))}}}var c=new RegExp(s.domain+'/(.*?)"?\\)');for(var h=a.length,p=0;p<h;p++){var d=window.getComputedStyle(a[p],null).getPropertyValue("background-image");var v=d.match(c);var m=a[p].getAttribute("data-background-src");if(v){var g=S(v[1].split("/"),s);if(g){y("background",a[p],g,d)}}else if(m!=null){var g=S(m.substr(m.lastIndexOf(s.domain)+s.domain.length+1).split("/"),s);if(g){y("background",a[p],g,d)}}}for(h=o.length,p=0;p<h;p++){var b,w;w=b=d=null;try{w=o[p].getAttribute("src");attr_datasrc=o[p].getAttribute("data-src")}catch(E){}if(attr_datasrc==null&&!!w&&w.indexOf(s.domain)>=0){d=w}else if(!!attr_datasrc&&attr_datasrc.indexOf(s.domain)>=0){d=attr_datasrc}if(d){var g=S(d.substr(d.lastIndexOf(s.domain)+s.domain.length+1).split("/"),s);if(g){if(g.fluid){y("fluid",o[p],g,d)}else{y("image",o[p],g,d)}}}}return e};T(t,function(){if(window.addEventListener){window.addEventListener("resize",E,false);window.addEventListener("orientationchange",E,false)}else{window.attachEvent("onresize",E)}i||e.run({});if(typeof window.Turbolinks==="object"){document.addEventListener("page:change",function(){e.run({})})}});if(typeof define==="function"&&define.amd){define([],function(){return e})}(function(){function e(e){this.message=e}var t="undefined"!=typeof exports?exports:this,n="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";e.prototype=Error(),e.prototype.name="InvalidCharacterError",t.btoa||(t.btoa=function(t){for(var r,i,s=0,o=n,u="";t.charAt(0|s)||(o="=",s%1);u+=o.charAt(63&r>>8-8*(s%1))){if(i=t.charCodeAt(s+=.75),i>255)throw new e("'btoa' failed");r=r<<8|i}return u}),t.atob||(t.atob=function(t){if(t=t.replace(/=+$/,""),1==t.length%4)throw new e("'atob' failed");for(var r,i,s=0,o=0,u="";i=t.charAt(o++);~i&&(r=s%4?64*r+i:i,s++%4)?u+=String.fromCharCode(255&r>>(6&-2*s)):0)i=n.indexOf(i);return u})})();document.getElementsByClassName||(document.getElementsByClassName=function(e){var t=document,n,r,i,s=[];if(t.querySelectorAll)return t.querySelectorAll("."+e);if(t.evaluate){r=".//*[contains(concat(' ', @class, ' '), ' "+e+" ')]",n=t.evaluate(r,t,null,0,null);while(i=n.iterateNext())s.push(i)}else{n=t.getElementsByTagName("*"),r=new RegExp("(^|\\s)"+e+"(\\s|$)");for(i=0;i<n.length;i++)r.test(n[i].className)&&s.push(n[i])}return s});window.getComputedStyle||(window.getComputedStyle=function(e){return this.el=e,this.getPropertyValue=function(t){var n=/(\-([a-z]){1})/g;return t=="float"&&(t="styleFloat"),n.test(t)&&(t=t.replace(n,function(){return arguments[2].toUpperCase()})),e.currentStyle[t]?e.currentStyle[t]:null},this});if(!Object.prototype.hasOwnProperty)Object.prototype.hasOwnProperty=function(e){var t=this.__proto__||this.constructor.prototype;return e in this&&(!(e in t)||t[e]!==this[e])}})(Holder,window)