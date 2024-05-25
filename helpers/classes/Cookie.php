<?php

class Cookie {
	public static function exists($name){
		return (isset($_COOKIE[$name])) ? true : false;
	}

	public static function get($name){
		return $_COOKIE[$name];
	}

	public static function put($name, $value, $expiry, $path="/", $domain="", $secure=true, $httponly=true, $samesite = "Strict"){
		if (PHP_VERSION_ID < 70300) {
		setcookie($name, $value, time() + $expiry, "$path; samesite=$samesite", $domain, $secure, $httponly);
		return true;
	}else{
		setcookie($name, $value, [
		'expires' => time() + $expiry,
		'path' => $path,
		'domain' => $domain,
		'samesite' => $samesite,
		'secure' => $secure,
		'httponly' => $httponly,
]);
		return true;
	}

		return false;
	}

	public static function delete($name){
		self::put($name, '', time() - 1);
	}
}
