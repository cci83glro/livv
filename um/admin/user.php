<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
//ini_set('allow_url_fopen', 1);
//header('X-Frame-Options: DENY');

$public = false;
$permissions = [1,2,3];
$pageTitle = 'Rediger bruger';
require_once __DIR__.'/../../master-pages/header.php';

$dbo = dbo::getInstance();
$validator = new Validator($dbo);

$email = $dbo->query('SELECT email_act FROM email')->fetchAll()[0];
$act = $email['email_act'];
$errors = [];
$successes = [];
$userId = (int) Input::get('id');
$forcePR = Input::get('forcePR') ? Input::get('forcePR') : false;

$options = ['id' => $userId];
$userdetailsQ = getUsers($dbo, $options);
if (count($userdetailsQ) < 1) {
  usError("Brugersiden findes ikke");
  Redirect::to($users_page_url);
}
$userdetails = $userdetailsQ[0];

$districts = $dbo->query("SELECT * FROM districts ORDER BY district_name ASC")->fetchAll();
$active_district_id = $userdetails['district_id'];

//Forms posted
if (!empty($_POST)) {

  $token = $_POST['csrf'];
  // if (!Token::check($token)) {
  //   include __DIR__.'/token_error.php';
  // } else {
    $dbo->query('UPDATE uacc SET modified=? WHERE id=?', date("Y-m-d"), $userId);

    $district_id = ucfirst(Input::get('district_id'));
    if ($userdetails['district_id'] != $district_id) {
      $fields = ['district_id' => $district_id];
      $validator->check($_POST, [
        'district_id' => [
          'display' => 'Kommune id',
          'required' => false,
          'is_numeric' => true,
          '>=' => 1,
          '<=' => 98,
        ],
      ]);
      if ($validator->passed()) {
        $dbo->query('UPDATE uacc SET district_id=? WHERE id=?', $district_id, $userId);
        $successes[] = 'Kommune opdateret';
        logger($user->data()['id'], 'User Manager', "Updated kommune for ".$userdetails['fname']." from ".$userdetails['district_id']." to ".$district_id);
      } else {
        if (!$validator->errors() == '') {
          display_errors($validator->errors());
        }
      }
    }

    $email = Input::get('email');
    if ($userdetails['email'] != $email) {
      $fields = ['email' => $email];
      $validator->check($_POST, [
        'email' => [
          'display' => 'Email',
          'required' => true,
          'valid_email' => true,
          'unique_update' => 'users,' . $userId,
          'min' => 3,
          'max' => 75,
        ],
      ]);
    }
    if ($validator->passed()) {
      $dbo->query('UPDATE uacc SET email=? WHERE id=?', $email, $userId);
      $successes[] = 'Email opdateret';
      logger($user->data()['id'], 'User Manager', "Updated email for ".$userdetails['fname']." from ".$userdetails['email']." to ".$email);
    } else {
      if (!$validator->errors() == '') {
        display_errors($validator->errors());
      }
    }

    $fname = ucfirst(Input::get('fnx'));
    if ($userdetails['fname'] != $fname) {
      $fields = ['fname' => $fname];
      $validator->check($_POST, [
        'fnx' => [
          'display' => 'Fornavn',
          'required' => true,
          'min' => 1,
          'max' => 25,
        ],
      ]);
      if ($validator->passed()) {
        $dbo->query('UPDATE uacc SET fname=? WHERE id=?', $fname, $userId);
        $successes[] = 'Fornavn opdateret';
        logger($user->data()['id'], 'User Manager', "Updated first name for ".$userdetails['fname']." from ".$userdetails['fname']." to ".$fname);
      } else {
        if (!$validator->errors() == '') {
          display_errors($validator->errors());
        }
      }
    }

    $lname = ucfirst(Input::get('lnx'));
    if ($userdetails['lname'] != $lname) {
      $fields = ['lname' => $lname];
      $validator->check($_POST, [
        'lnx' => [
          'display' => 'Efternavn',
          'required' => true,
          'min' => 1,
          'max' => 25,
        ],
      ]);
      if ($validator->passed()) {
        $dbo->query('UPDATE uacc SET lname=? WHERE id=?', $lname, $userId);
        $successes[] = 'Efternavn opdateret';
        logger($user->data()['id'], 'User Manager', "Updated last name for ".$userdetails['fname']." from ".$userdetails['lname']." to ".$lname);
      } else {
        if (!$validator->errors() == '') {
           display_errors($validator->errors());
        } 
      }
    }

    $phone = ucfirst(Input::get('phone'));
    if ($userdetails['phoneNumber'] != $phone) {
      $fields = ['phoneNumber' => $phone];
      $validator->check($_POST, [
        'phone' => [
          'display' => 'Phone',
          'required' => false,
          'is_numeric' => true,
          'min' => 8,
          'max' => 12,
        ],
      ]);
      if ($validator->passed()) {
        $dbo->query('UPDATE uacc SET phoneNumber=? WHERE id=?', $phone, $userId);
        $successes[] = 'Telefonnummer opdateret';
        logger($user->data()['id'], 'User Manager', "Updated telefonnummer for ".$userdetails['fname']." from ".$userdetails['phoneNumber']." to ".$phone);
      } else {
        if (!$validator->errors() == '') {
          display_errors($validator->errors());
        }
      } 
    }

    $active_state = '';
    $login_text = '';
    if (isset($_POST['active']) && $_POST['active'] == 'on') {
      if ($userdetails['active'] === 0)
      {
        $dbo->query('UPDATE uacc SET active=1 WHERE id=?', $userId);
        $active_state = 'aktiv';
        $login_text = ' Du kan logge på <a href="'.$login_page_url.'">her</a>';
        $successes[] = 'Brugerkontoen er opdateret til aktiv.';
        logger($user->data()['id'], 'User Manager', "Updated active status for user id ".$userdetails['id']." from Inactive to Active.");
      }
      else if ($userdetails['active'] === 1)
      {
        $dbo->query('UPDATE uacc SET active=0 WHERE id=?', $userId);
        $active_state = 'inaktiv';
        $successes[] = 'Brugerkontoen er opdateret til inaktiv.';
        logger($user->data()['id'], 'User Manager', "Updated active status for user id ".$userdetails['id']." from Active to Inactive.");
      }
    }

    if (!isNullOrEmptyString($active_state)) {
      $body = get_email_body('_email_account_active_status_change_notify_user.php');
      $body = str_replace("{{fname}}", $fname, $body);
      $body = str_replace("{{lname}}", $lname, $body);
      $body = str_replace("{{active_state}}", $active_state, $body);
      $body = str_replace("{{login_text}}", $login_text, $body);
      send_email($email, 'Din '.$site_name.' konto har skiftet status', $body);
    }

    if (!empty($_POST['pwx'])) {
      $validator->check($_POST, [
        'pwx' => [
          'display' => 'Ny adgangskode',
          'required' => true,
          'min' => 5,
          'max' => 30,
        ],
        'confirm' => [
          'display' => 'Bekræft ny adgangskode',
          'required' => true,
          'matches' => 'confirm',
        ],
      ]);

      if (!$validator->errors()) {
        $new_password_hash = password_hash(Input::get('pwx', true), PASSWORD_BCRYPT, ['cost' => 12]);
        $dbo->query('UPDATE uacc SET `password`=? WHERE id=?', $new_password_hash, $userId);
        $successes[] = 'Adgangskoden er opdateret.';
        logger($user->data()['id'], 'User Manager', "Updated password for ".$userdetails['fname']);
      } else {
        usError("Validering af adgangskode fejlede");
        Redirect::to($user_page_url . $userId);
      }
    }

    if ($forcePR) {
      $vericode_expiry = date('Y-m-d H:i:s', strtotime("+15 minutes", strtotime(date('Y-m-d H:i:s'))));
      $vericode = randomstring(15);
      $dbo->query('UPDATE uacc SET vericode=?, vericode_expiry=? WHERE id=?', $vericode, $vericode_expiry, $userId);
      if (isset($_POST['sendPwReset'])) {
        $params = [
          'sitename' => $site_name,
          'fname' => $userdetails['fname'],
          'email' => rawurlencode($userdetails['email']),
          'vericode' => $vericode,
          'reset_vericode_expiry' => 15,
        ];
        $to = rawurlencode($userdetails['email']);
        $subject = "Nulstil adgangskoden";
        $body = email_body('_email_adminPwReset.php', $params);
        email($to, $subject, $body);
        $successes[] = 'Sendt email om nulstilling af adgangskode.';
        logger($user->data()['id'], 'User Manager', "Sent password reset email to ".$userdetails['fname'].", Vericode expires at $vericode_expiry.");
      }
    }

  //}

  if(!$validator->errors() == ''){
    foreach($validator->errors()  as $key=>$e){
      $found = false;
      foreach($errors as $k=>$v){
        if($v == $e){
          $found = true;
        }
      }
      if(!$found){
        $errors[] = $e;
      }
    }
  }

  if ($errors == []) {
    usSuccess("Gemt");
    if ($user_permission == 2) {
      Redirect::to($users_page_url);
    } else {
      Redirect::to($bookings_page_url);
    }
  } else {
    Redirect::to($user_page_url . $userId);
  }

  $rsn = '';

  if (!$validator->errors() == '') {
    display_errors($validator->errors());
  }
}
?>

<section id="user-details">
  <div class="row mb-1">
    <h3>
      <span id="fname"><?= $userdetails['fname']; ?></span>
      <span id="lname"><?= $userdetails['lname']; ?></span>
      <span id="permission">(<?= $userdetails['permission_name']; ?>)</span>
    </h3>

    <?php if($userdetails['permissions'] == 3) { ?>
      <p><label>Lønnummer: <?= $userdetails['id']; ?>
    <?php } ?>
    <?php if ($act == 1) { ?>
      <?php if ($userdetails['email_verified'] == 1) { ?>
        (Email Verified)</label> <input type="hidden" name="email_verified" value="1" />
      <?php } elseif ($userdetails['email_verified'] == 0) { ?>
        (Email Unverified)</label>
    <?php } } ?>
    </p>
    <p><label>Oprettet: </label> <?= $userdetails['join_date']; ?></p>

    <p><label>Sidste login: </label>
      <?php if ($userdetails['last_login'] != 0) {
        echo $userdetails['last_login'];
      } else {
        echo "<i>Aldrig</i>";
      }?>
  </div>

  <form class="form" id='adminUser' name='adminUser' action='' method='post'>
    <div class="row">
      <div class="col-12 col-sm-6">
        
        <?php if($userdetails['permissions'] == 1) { ?>
          <div class="form-group" id="fname-group">
            <label for="district_id">Kommune</label>
            <select class="district-list form-control" id="district_id" name="district_id">
                  <option value="0">Vælg kommune</option>
                  <?php foreach($districts as $district) { ?>
                    <option value="<?=$district['district_id'] ?>" 
                    <?php echo($district['district_id'] == $active_district_id ? 'selected="selected"' : ''); ?>><?=$district['district_name'] ?></option>
                  <?php } ?>
                  
            </select>
          </div>
        <?php } ?>

        <div class="form-group" id="fname-group">
          <label for='fnx'>Fornavn</label>
          <input class='form-control' type='search' name='fnx' id='fnx' value='<?= $userdetails['fname']; ?>' autocomplete="off" />
        </div>

        <div class="form-group" id="lname-group">
          <label for='lnx'>Efternavn</label>
          <input class='form-control' type='search' name='lnx' id='lnx' value='<?= $userdetails['lname']; ?>' autocomplete="off" />
        </div>

        <div class="form-group" id="phone-group">
          <label for='phone'>Telefon</label>
          <input class='form-control' type='search' name='phone' id='phone' value='<?= $userdetails['phoneNumber']; ?>' autocomplete="off" />
        </div>
      </div>
      <div class="col-12 col-sm-6">
        <div class="form-group" id="email-group">
          <label for='email'>Email</label>
          <input class='form-control' type='search' name='email' id='email' value='<?= $userdetails['email']; ?>' autocomplete="off" />
        </div>

        <div class="form-group">
          <label for='pwx'>Ny adgangskode (Min 5 char, max 30)</label>
          <input class='form-control' type='password' autocomplete="off" name='pwx' id='pwx'/>
        </div>

        <div class="form-group">
          <label for='confirm'>Bekræft adgangskode</label>
          <input class='form-control' type='password' autocomplete="off" name='confirm' id='confirm'/>
        </div>

        <?php if($user_permission == 2) { ?>
        <div class="form-group active">
          <label for='active'>Aktiv: </label>
          <label class="toggle-switch">
            <input class='form-control' id="active" name="active" type="checkbox" <?php echo ($userdetails['active'] > 0) ? 'checked' : ''; ?>>
            <span class="slider round"></span>
          </label>
        </div>
        <?php } ?>

        <input type="hidden" name="csrf" value="<?= Token::generate(); ?>" />
        <div class="form-actions">
          <a class='cancel' href="<?= $users_page_url; ?>">Fortryd</a>
          <input class='save' type='submit' value='Gem' class='submit' />
        </div>
      </div>
    </div>
  </form>
</section>
<?php include_once __DIR__."/../../master-pages/footer.php"?>

</body>
</html>