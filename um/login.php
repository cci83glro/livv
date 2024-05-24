<?php

$public = true;

require_once __DIR__.'/../master-pages/header.php';

$errors = $successes = [];
if (Input::get('err') != '') {
  $errors[] = Input::get('err');
}

if (isset($user) && $user->isLoggedIn()) {
  Redirect::to($us_url_root . $settings->redirect_uri_after_login);
}

if (!empty($_POST)) {
  $token = Input::get('csrf');
  if (!Token::check($token)) {
    include($abs_us_root . $us_url_root . 'usersc/scripts/token_error.php');
  }

  $validate = new Validate();
  $validation = $validate->check(
    $_POST,
    array(
      'email' => array('display' => 'Email', 'required' => true),
      'password' => array('display' => 'Adgangskode', 'required' => true)
    )
  );
  $validated = $validation->passed();
  // Set $validated to False to kill validation, or run additional checks, in your post hook
  $email = Input::get('email');
  $password = trim(Input::get('password'));
  $remember = false;

  if ($validated) {
    //Log user in
    $user = new User();
    $login = $user->loginEmail($email, $password, $remember);
    if ($login) {
      # if user was attempting to get to a page before login, go there
      $_SESSION['last_confirm'] = date("Y-m-d H:i:s");

      $redirect = Input::get('redirect');
      if (!empty($redirect) || $redirect !== '') {
        Redirect::to(html_entity_decode($redirect));
      } else {
        Redirect::to($us_url_root);
      }
    } else {
      logger("0", "Login Fail", "A failed login on login.php");
      $msg = 'Log ind fejlede';
      $msg2 = 'Tjek venligst email og adgangskode og prøv igen!';
      $errors[] = '<strong>' . $msg . '</strong>' . $msg2;
    }
  } else {
    $errors = $validation->errors();
  }
  sessionValMessages($errors, $successes, NULL);
}
?>
<style media="screen">
  .img-responsive {
    width: 100% !important;
  }
</style>
<div class="container p-2 h-100 alternate-background">

  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <b>Log ind</b>
          <a href="<?= $us_url_root ?>" aria-label="Close" class="close btn-close" style="top: 1rem!important;"></a>

        </div>
        <div class="modal-body p-4 py-5 p-md-5">
          <form name="login" id="login-form" class="form-signin" action="" method="post">
            <?= tokenHere(); ?>
            <div class="form-outline mb-4">
              <label class="form-label" for="email">Email</label>
              <input type="email" id="email" name="email" class="form-control form-control-lg" required autocomplete="email" />

            </div>

            <div class="form-outline mb-4">
              <label class="form-label" for="password">Adgangskode</label>
              <div class="input-group">
                <input type="password" id="password" name="password" class="form-control form-control-lg" />
                <span class="input-group-addon input-group-text see-pw" id="togglePassword">
                  <i class="fa fa-eye" id="togglePasswordIcon"></i>
                </span>
              </div>
            </div>

            <input type="hidden" name="redirect" value="<?= Input::get('redirect') ?>" />
            <button class="submit form-control btn btn-primary rounded submit px-3 bg-secondary-color primary-color" id="next_button" type="submit"><i class="fa fa-sign-in"></i> Log ind</button>
          </form>
          <div class="row">
              <div class="col-12 text-center"><br>
                <a class="secondary-color" href='<?= $us_url_root ?>users/forgot_password.php'><i class="fa fa-wrench"></i> Glemt adgangskode</a>
                <br>
              </div>
              <div class="col-12 text-center"><br>
                <a class="secondary-color" href='<?= $us_url_root ?>um/join.php'><i class="fa fa-plus-square"></i> Registrer som vikar</a><br><br>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__.'/../master-pages/footer.php';?>
<script src="<?=$us_url_root?>assets/js/um/login.js"></script>

</body>
</html>