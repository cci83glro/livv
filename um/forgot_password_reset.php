<?php

	$public = true;
	$pageTitle = 'Nulstil adgangskode';
	require_once __DIR__.'/../master-pages/header.php';

	//if (!securePage($_SERVER['PHP_SELF'])){die();}

	if(isset($user) && $user->isLoggedIn()){
		Redirect::to($us_url_root."um/admin/user.php?id=".$user_id);
	}

	$error_message = null;
	$errors = array();
	$reset_password_success=FALSE;
	$password_change_form=FALSE;


	$token = Input::get('csrf');
	if(Input::exists()){
		// if (!Token::check(Input::get('csrf'))) {
		// 	include __DIR__ . '/token_error.php';
		// }
	}

	if(Input::get('reset') == 1){

		$dbo = dbo::getInstance();
		$email = Input::get('email');
		$vericode = Input::get('vericode');
		$ruser = new User($email);
		//$eventhooks = getMyHooks(['page'=>'forgotPasswordResponse']);
		//includeHook($eventhooks,'body');
		if(isset($hookData['ruser'])){
			$ruser = $hookData['ruser'];
		}

		if (Input::get('resetPassword')) {
			$newPw = 'Ny adgangskode';
			$confPw = 'Bekræft ny adgangskode';
			$validator = new Validator($dbo);
			$validation = $validator->check($_POST,array(
					'password' => array(
						'display' => $newPw,
						'required' => true,
						'min' => 5,
						'max' => 50,
					),
					'confirm' => array(
						'display' => $confPw,
						'required' => true,
						'matches' => 'password',
					),
			));
			if($validation->passed()){
				if($ruser->data()['vericode'] != $vericode || (strtotime($ruser->data()['vericode_expiry']) - strtotime(date("Y-m-d H:i:s")) <= 0)){
					$msg = str_replace("+"," ","Noget+gik+galt.+Prøv+venligst+igen.");
					usError($msg);
					Redirect::to($us_url_root.'um/forgot_password_reset.php');
				}

				$password_hash = password_hash(Input::get('password'), PASSWORD_BCRYPT, array('cost' => 12));
				$vericode = randomstring(15);
				$vericode_expiry = date("Y-m-d H:i:s");
				$query = "UPDATE uacc SET password=?, vericode=?, vericode_expiry=?, email_verified=?, force_pr=? WHERE id=?";
				
				if (!$dbo->query($query, $password_hash, $vericode, $vericode_expiry, 1, 0, $ruser->data()['id']))
				{
					echo "Fejl ved opdateringen af adgangskoden. Prøv venligst igen!";
					die();
				}

				$reset_password_success=TRUE;
				logger($ruser->data()['id'],"User","Reset password.");
			}else{
				$reset_password_success=FALSE;
				$errors = $validation->errors();
		//$eventhooks =  getMyHooks(['page'=>'passwordResetFail']);
		//includeHook($eventhooks,'body');
			}
		}
		if ($ruser->exists() && $ruser->data()['vericode'] == $vericode) {
			//if the user email is in DB and verification code is correct, show the form
			$password_change_form=TRUE;
		}

		if($reset_password_success){
			require __DIR__.'/views/_forgot_password_reset_success.php';
		}elseif((!Input::get('resetPassword') || !$reset_password_success) && $password_change_form){
			require __DIR__.'/views/_forgot_password_reset.php';
		}else{
			require __DIR__.'/views/_forgot_password_reset_error.php';
		}
	}else{
		require __DIR__.'/views/_forgot_password_reset_error.php';
	}

	include_once __DIR__."/../master-pages/footer.php";
?>
