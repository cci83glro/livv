<?php

class Token {
	public static function generate($force=false){
		$tokenName = Config::get('session/token_name');
		if($force) {
			return Session::put($tokenName, md5(uniqid()));
		} else {
			if(Session::exists($tokenName)) {
				return Session::get($tokenName);
			} else {
				return Session::put($tokenName, md5(uniqid()));
			}
		}
	}

	public static function check($token){
		$tokenName = Config::get('session/token_name');

		if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
			//Session::delete($tokenName);
			return true;
		}
		return false;
	}
}
