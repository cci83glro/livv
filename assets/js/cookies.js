(function (window, undefined){
	"use strict";
	
	var document = window.document;
	var cookie_name = "livVikarCookieConsent";
	
	function log() {
		if (window.console && window.console.log) {
			for (var x in arguments) {
				if (arguments.hasOwnProperty(x)) {
					window.console.log(arguments[x]);
				}
			}
		}
	}
	
	function AcceptCookie() {
		if (!(this instanceof AcceptCookie)) {
			return new AcceptCookie();
		}
				
		this.init.call(this);
		
		return this;
	}
	
	AcceptCookie.prototype = {
		
		init: function () {
			var self = this;
			
			if(self.readCookie(cookie_name) == null)
			{
				self.addCookieBar();
			}
			
			var clear_cookie_arr = self.getElementsByClass("livVikarClearCookie", null, "a");
			if(clear_cookie_arr.length > 0)
			{
				self.addEvent(clear_cookie_arr[0], "click", function (e) {
					if (e.preventDefault) {
						e.preventDefault();
					}
					self.eraseCookie(cookie_name);
					document.location.reload();
					return false;
				});
			}
		},
		getElementsByClass: function (searchClass, node, tag) {
			var classElements = new Array();
			if (node == null) {
				node = document;
			}
			if (tag == null) {
				tag = '*';
			}
			var els = node.getElementsByTagName(tag);
			var elsLen = els.length;
			var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
			for (var i = 0, j = 0; i < elsLen; i++) {
				if (pattern.test(els[i].className)) {
					classElements[j] = els[i];
					j++;
				}
			}
			return classElements;
		},
		addEvent: function (obj, type, fn) {
			if (obj.addEventListener) {
				obj.addEventListener(type, fn, false);
			} else if (obj.attachEvent) {
				obj["e" + type + fn] = fn;
				obj[type + fn] = function() { obj["e" + type + fn](window.event); };
				obj.attachEvent("on" + type, obj[type + fn]);
			} else {
				obj["on" + type] = obj["e" + type + fn];
			}
		},
		createCookie: function (name, value, days){
			var expires;
		    if (days) {
		        var date = new Date();
		        date.setTime(date.getTime()+(days*24*60*60*1000));
		        expires = ", expires="+date.toGMTString();
		    } else {
		        expires = "";
		    }
		    document.cookie = name+"="+value+expires+", path=/";
		},
		readCookie: function (name) {
		    var nameEQ = name + "=";
		    var ca = document.cookie.split(';');
		    for(var i=0;i < ca.length;i++) {
		        var c = ca[i];
		        while (c.charAt(0) === ' ') {
		            c = c.substring(1,c.length);
		        }
		        if (c.indexOf(nameEQ) === 0) {
		            return c.substring(nameEQ.length,c.length);
		        }
		    }
		    return null;
		},
		eraseCookie: function (name) {
			var self = this;
			self.createCookie(name,"",-1);
		},
		addCookieBar: function(){
			var self = this;
			var htmlBar = '';
			
			htmlBar += '<div class="livVikarCookieBarShell">';
			htmlBar += '<form action="#" method="post">';
			htmlBar += '<h2>Du bestemmer over dine data</h2>';
			htmlBar += '<p>Denne hjemmeside bruger cookies. <br />Ved at fortsætte er du enig med vores Cookiepolitik,<br/> som du kan læse mere om <a href="cookies-politik" target=_blank>her</a>.</p>';
			htmlBar += '<div class="livVikarCookieBarActions">';
			htmlBar += '<button type="button" class="livVikarCookieBarBtn theme-btn">Tillad nødvendige</button>';
			htmlBar += '<button type="button" class="livVikarCookieBarBtn theme-btn">Tillad alle</button>';
			htmlBar += '</div>';
			htmlBar += '</form>';
			htmlBar += '</div>';
			
			var barDiv = document.createElement('div');
			barDiv.id = "livVikarCookieBar";
			document.body.appendChild(barDiv);
			barDiv.innerHTML = htmlBar;
			
			self.bindCookieBar();
		},
		bindCookieBar: function(){
			var self = this;
			var btn_arr = self.getElementsByClass("livVikarCookieBarBtn", null, "button");
			var buttonClick = function (e) {
				if (e.preventDefault) {
					e.preventDefault();
				}
				self.createCookie(cookie_name, 'YES', 365);
				
				document.getElementById("livVikarCookieBar").remove();
				return false;
			};

			btn_arr.forEach(function(b) {
				self.addEvent(b, "click", buttonClick);
			});
		}
	};
	
	window.AcceptCookie = AcceptCookie;	
})(window);

window.onload = function() {
	AcceptCookie = AcceptCookie();
}