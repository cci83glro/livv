<?php

  $public = true;
  $pageTitle = 'Glemt adgangskode';
  require_once __DIR__.'/../master-pages/header.php';

  //if (!securePage($_SERVER['PHP_SELF'])){die();}

  if(isset($user) && $user->isLoggedIn()){
    Redirect::to($us_url_root."um/admin/user.php?id=".$user_id);
  }

  $token = Input::get('csrf');
	if(Input::exists()){
		// if (!Token::check(Input::get('csrf'))) {
		// 	include __DIR__ . '/token_error.php';
		// }
	}

  $dbo = dbo::getInstance();

  // $email = Input::get('email');
  // $em = ($dbo->query("SELECT * FROM email")->fetchAll())[0];

  // if($em->email_login == "yourEmail@gmail.com"){
  //   echo "<br><h3 align='center'>".lang("ERR_EM_VER")."</h3>";
  //   die();
  // }
  $error_message = null;
  $errors = array();
  $email_sent=FALSE;

  // $eventhooks =  getMyHooks(['page'=>'forgotPassword']);
  // includeHook($eventhooks,'body');

  if (Input::get('forgotten_password')) {

      $fuser = new User($email);
      $validate = new Validate();
      $msg1 = "Email";

      $validation = $validate->check($_POST,array('email' => array('display' => $msg1,'valid_email' => true,'required' => true,),));

      //includeHook($hooks,'post');
      // if(isset($hookData['validation'])){
      //   $validation = $hookData['validation'];
      // }
      // if(isset($hookData['fuser'])){
      //   $fuser = $hookData['fuser'];
      // }

      if($validation->passed()){
          if($fuser->exists()){
              $vericode=randomstring(15);
              $vericode_expiry=date("Y-m-d H:i:s",strtotime("+15 minutes",strtotime(date("Y-m-d H:i:s"))));
              $dbo->update('users',$fuser->data()['id'],['vericode' => $vericode,'vericode_expiry' => $vericode_expiry]);
              
              $options = array(
                'fname' => $fuser->data()['fname'],
                'email' => rawurlencode($email),
                'vericode' => $vericode,
                'reset_vericode_expiry' => 15
              );
              $subject = "Nulstil adgangskode";
              $encoded_email=rawurlencode($email);
              $body = get_email_body('_email_template_forgot_password.php',$options);
              $email_sent=send_email($email,$subject,$body);
    
              logger($fuser->data()['id'],"User","Requested password reset.");
              if(!$email_sent){
                  $errors[] = "E-mail er IKKE sendt på grund af fejl. Skriv venligst til kontakt@livvikar.dk.";
              }
          }else{
              sleep(2); //pretend to send
              logger("","Password Reset","Attempted password reset on ".$email);
              $email_sent = true;
          }
      }else{
          //display the errors
          $errors = $validation->errors();
      }
  }

  if($email_sent){
      require __DIR__.'/views/_forgot_password_sent.php';
  }else{
      require __DIR__.'/views/_forgot_password.php';
  }

  include_once __DIR__."/../master-pages/footer.php";
?>