<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
//ini_set('allow_url_fopen', 1);
//header('X-Frame-Options: DENY');

$public = false;
$permissions = array(2);
$pageTitle = 'Rediger bruger';
require_once '../../header.php';

$validation = new Validate();

$email = $db->query('SELECT email_act FROM email')->first();
$act = $email->email_act;
$errors = [];
$successes = [];
$userId = (int) Input::get('id');

$userdetailsQ = $db->query(
  "SELECT u.*, p.name as permission_name, d.district_name
  FROM users u 
  LEFT JOIN permissions p on u.permissions = p.id 
  LEFT JOIN districts d on u.district_id = d.district_id 
  WHERE u.id =  ?", [$userId]);
$userdetailsC = $userdetailsQ->count();
if ($userdetailsC < 1) {
  usError("Brugersiden findes ikke");
  Redirect::to($users_page_url);
}
$userdetails = $userdetailsQ->first();

//Forms posted
if (!empty($_POST)) {

  $token = $_POST['csrf'];
  if (!Token::check($token)) {
    include $abs_us_root . $us_url_root . 'um/admin/token_error.php';
  } else {
    $db->update('users', $userdetails->id, ['modified' => date("Y-m-d")]);

    $district_id = ucfirst(Input::get('district_id'));
    if ($userdetails->district_id != $district_id) {
      $fields = ['district_id' => $district_id];
      $validation->check($_POST, [
        'district_id' => [
          'display' => 'Kommune id',
          'required' => false,
          'is_numeric' => true,
          '>=' => 1,
          '<=' => 98,
        ],
      ]);
      if ($validation->passed()) {
        $db->update('users', $userId, $fields);
        $successes[] = 'Kommune opdateret';
        logger($user->data()->id, 'User Manager', "Updated kommune for $userdetails->fname from $userdetails->district_id to $district_id.");
      } else {
      ?><?php if (!$validation->errors() == '') {
          display_errors($validation->errors());
        } ?>
      <?php } }

    $fname = ucfirst(Input::get('fnx'));
    if ($userdetails->fname != $fname) {
      $fields = ['fname' => $fname];
      $validation->check($_POST, [
        'fnx' => [
          'display' => 'Fornavn',
          'required' => true,
          'min' => 1,
          'max' => 25,
        ],
      ]);
      if ($validation->passed()) {
        $db->update('users', $userId, $fields);
        $successes[] = 'Fornavn opdateret';
        logger($user->data()->id, 'User Manager', "Updated first name for $userdetails->fname from $userdetails->fname to $fname.");
      } else {
      ?><?php if (!$validation->errors() == '') {
          display_errors($validation->errors());
        } ?>
      <?php } }

    $lname = ucfirst(Input::get('lnx'));
    if ($userdetails->lname != $lname) {
      $fields = ['lname' => $lname];
      $validation->check($_POST, [
        'lnx' => [
          'display' => 'Efternavn',
          'required' => true,
          'min' => 1,
          'max' => 25,
        ],
      ]);
      if ($validation->passed()) {
        $db->update('users', $userId, $fields);
        $successes[] = 'Efternavn opdateret';
        logger($user->data()->id, 'User Manager', "Updated last name for $userdetails->fname from $userdetails->lname to $lname.");
      } else {
        if (!$validation->errors() == '') {
           display_errors($validation->errors());
        } 
      }
    }

    $phone = ucfirst(Input::get('phone'));
    if ($userdetails->phoneNumber != $phone) {
      $fields = ['phoneNumber' => $phone];
      $validation->check($_POST, [
        'phone' => [
          'display' => 'Phone',
          'required' => false,
          'is_numeric' => true,
          'min' => 8,
          'max' => 12,
        ],
      ]);
      if ($validation->passed()) {
          $db->update('users', $userId, $fields);
          $successes[] = 'Telefonnummer opdateret';
          logger($user->data()->id, 'User Manager', "Updated telefonnummer for $userdetails->fname from $userdetails->phoneNumber to $phone.");
        } else {
        ?><?php if (!$validation->errors() == '') {
          display_errors($validation->errors());
        } ?>
      <?php } }

    if (isset($_POST['active']) && $_POST['active'] == 'on') {
      if ($userdetails->active === 0)
      {
        $user->update(['active' => 1], $userId);
        $successes[] = 'Brugerkontoen er opdateret til aktiv.';
        logger($user->data()->id, 'User Manager', "Updated active status for user id $userdetails->id from Inactive to Active.");
      }
    } else {
      if ($userdetails->active === 1)
      {
        $user->update(['active' => 0], $userId);
        $successes[] = 'Brugerkontoen er opdateret til inaktiv.';
        logger($user->data()->id, 'User Manager', "Updated active status for user id $userdetails->id from Active to Inactive.");
      }
    }

    if (!empty($_POST['pwx'])) {
      $validation->check($_POST, [
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

      if (!$validation->errors()) {
        $new_password_hash = password_hash(Input::get('pwx', true), PASSWORD_BCRYPT, ['cost' => 12]);
        $user->update(['password' => $new_password_hash], $userId);
        $successes[] = 'Adgangskoden er opdateret.';
        logger($user->data()->id, 'User Manager', "Updated password for $userdetails->fname.");
        if ($settings->session_manager == 1) {
          if ($userId == $user->data()->id) {
            $passwordResetKillSessions = passwordResetKillSessions();
          } else {
            $passwordResetKillSessions = passwordResetKillSessions($userId);
          }
          if (is_numeric($passwordResetKillSessions)) {
            if ($passwordResetKillSessions == 1) {
              $successes[] = 'Successfully Killed 1 Session';
            }
            if ($passwordResetKillSessions > 1) {
              $successes[] = "Successfully Killed $passwordResetKillSessions Session";
            }
          } else {
            $errors[] = 'Failed to kill active sessions, Error: ' . $passwordResetKillSessions;
          }
        }
      } else {
        usError("Validering af adgangskode fejlede");
        Redirect::to($user_page_url . $userId);
      }
    }
    $vericode_expiry = date('Y-m-d H:i:s', strtotime("+$settings->reset_vericode_expiry minutes", strtotime(date('Y-m-d H:i:s'))));
    $vericode = randomstring(15);
    $db->update('users', $userdetails->id, ['vericode' => $vericode, 'vericode_expiry' => $vericode_expiry]);
    if (isset($_POST['sendPwReset'])) {
      $params = [
        'sitename' => $settings->site_name,
        'fname' => $userdetails->fname,
        'email' => rawurlencode($userdetails->email),
        'vericode' => $vericode,
        'reset_vericode_expiry' => $settings->reset_vericode_expiry,
      ];
      $to = rawurlencode($userdetails->email);
      $subject = "Nulstil adgangskoden";
      $body = email_body('_email_adminPwReset.php', $params);
      email($to, $subject, $body);
      $successes[] = 'Sendt email om nulstilling af adgangskode.';
      logger($user->data()->id, 'User Manager', "Sent password reset email to $userdetails->fname, Vericode expires at $vericode_expiry.");
    }

    $email = Input::get('email');
    if ($userdetails->email != $email) {
      $fields = ['email' => $email];
      $validation->check($_POST, [
        'email' => [
          'display' => 'Email',
          'required' => true,
          'valid_email' => true,
          'unique_update' => 'users,' . $userId,
          'min' => 3,
          'max' => 75,
        ],
      ]);

      if ($validation->passed()) {
        $db->update('users', $userId, $fields);
        $successes[] = 'Email opdateret';
        logger($user->data()->id, 'User Manager', "Updated email for $userdetails->fname from $userdetails->email to $email.");
      } else {
       
?>
  <?php if (!$validation->errors() == '') {
          display_errors($validation->errors());
        } ?>
<?php
      }
    }
  }

  if(!$validation->errors() == ''){
    foreach($validation->errors()  as $key=>$e){
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
    Redirect::to($users_page_url);
  } else {
    Redirect::to($user_page_url . $userId);
  }
}

$userPermission = fetchUserPermissions($userId);
$permissionData = fetchAllPermissions();

$rsn = '';

if (!$validation->errors() == '') {
  display_errors($validation->errors());
}

$districts = $db->query("SELECT * FROM districts ORDER BY district_name ASC")->results();
$active_district_id = $userdetails->district_id;

?>

<section id="user-details">
  <div class="row mb-1">
    <h3>
      <span id="fname"><?= $userdetails->fname; ?></span>
      <span id="lname"><?= $userdetails->lname; ?></span>
      <span id="permission">(<?= $userdetails->permission_name; ?>)</span>
    </h3>

    <p><label>ID: <?= $userdetails->id; ?>
        <?php if ($act == 1) { ?>
          <?php if ($userdetails->email_verified == 1) { ?>
            (Email Verified)</label> <input type="hidden" name="email_verified" value="1" />

    <?php } elseif ($userdetails->email_verified == 0) { ?>
      (Email Unverified)</label>
    <?php } } ?>
    </p>
    <p><label>Oprettet: </label> <?= $userdetails->join_date; ?></p>

    <p><label>Sidste login: </label>
      <?php if ($userdetails->last_login != 0) {
        echo $userdetails->last_login;
      } else {
        echo "<i>Aldrig</i>";
      }?>
  </div>

  <form class="form" id='adminUser' name='adminUser' action='' method='post'>
    <div class="row">
      <div class="col-12 col-sm-6">
        
        <?php if($userdetails->permissions == 1) { ?>
          <div class="form-group" id="fname-group">
            <label for="district_id">Kommune</label>
            <select class="district-list form-control" id="district_id" name="district_id">
                  <option value="0">Vælg kommune</option>
                  <?php foreach($districts as $district) { ?>
                    <option value="<?=$district -> district_id ?>" 
                    <?php echo($district -> district_id == $active_district_id ? 'selected="selected"' : ''); ?>><?=$district -> district_name ?></option>
                  <?php } ?>
                  
            </select>
          </div>
        <?php } ?>

        <div class="form-group" id="fname-group">
          <label for='fnx'>Fornavn</label>
          <input class='form-control' type='search' name='fnx' id='fnx' value='<?= $userdetails->fname; ?>' autocomplete="off" />
        </div>

        <div class="form-group" id="lname-group">
          <label for='lnx'>Efternavn</label>
          <input class='form-control' type='search' name='lnx' id='lnx' value='<?= $userdetails->lname; ?>' autocomplete="off" />
        </div>

        <div class="form-group" id="phone-group">
          <label for='phone'>Telefon</label>
          <input class='form-control' type='search' name='phone' id='phone' value='<?= $userdetails->phoneNumber; ?>' autocomplete="off" />
        </div>
      </div>
      <div class="col-12 col-sm-6">
        <div class="form-group" id="email-group">
          <label for='email'>Email</label>
          <input class='form-control' type='search' name='email' id='email' value='<?= $userdetails->email; ?>' autocomplete="off" />
        </div>

        <div class="form-group">
          <label for='pwx'>Ny adgangskode (Min 5 char, max 30)</label>
          <input class='form-control' type='password' autocomplete="off" name='pwx' id='pwx' <?php if ((!in_array($user->data()->id, $master_account) && in_array($userId, $master_account) || !in_array($user->data()->id, $master_account) && $userdetails->protected == 1) && $userId != $user->data()->id) { ?>disabled<?php } ?> />
        </div>

        <div class="form-group">
          <label for='confirm'>Bekræft adgangskode</label>
          <input class='form-control' type='password' autocomplete="off" name='confirm' id='confirm' <?php if ((!in_array($user->data()->id, $master_account) && in_array($userId, $master_account) || !in_array($user->data()->id, $master_account) && $userdetails->protected == 1) && $userId != $user->data()->id) { ?>disabled<?php } ?> />
        </div>

        <div class="form-group active">
          <label for='active'>Aktiv: </label>
          <label class="toggle-switch">
            <input class='form-control' id="active" name="active" type="checkbox" <?php echo ($userdetails->active > 0) ? 'checked' : ''; ?>>
            <span class="slider round"></span>
          </label>
        </div>

        <input type="hidden" name="csrf" value="<?= Token::generate(); ?>" />
        <div class="form-actions">
          <a class='cancel' href="<?= $users_page_url; ?>">Fortryd</a>
          <input class='save' type='submit' value='Gem' class='submit' />
        </div>
      </div>
    </div>
  </form>
</section>
<?php include_once $abs_us_root.$us_url_root."footer.php"?>

</body>
</html>