<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
//ini_set('allow_url_fopen', 1);
//header('X-Frame-Options: DENY');

$public = false;
$permissions = [2];
$pageTitle = 'Brugerliste';
require_once __DIR__.'/../../master-pages/header.php';

$dbo = dbo::getInstance();

$errors = $successes = [];
$form_valid = true;
$permissions = $dbo->query('SELECT * FROM permissions')->fetchAll();

$validator = new Validator($dbo);

if (!empty($_POST)) {
  // if (!Token::check(Input::get('csrf'))) {
  //   include __DIR__ . '/token_error.php';
  // }

  //Manually Add User
  if (!empty($_POST['addUser'])) {

    $vericode_expiry = date('Y-m-d H:i:s', strtotime("+24 hours", strtotime(date('Y-m-d H:i:s'))));
    $join_date = date('Y-m-d H:i:s');
    $permission_id = Input::get('permission_id');
    $fname = Input::get('fname');
    $lname = Input::get('lname');
    $phone = Input::get('phone');
    $email = Input::get('email');
    $password = Input::get('password');
    $vericode = randomstring(15);

    $form_valid = false; // assume the worst

    $validator->check($_POST, [
      'fname' => ['display' => 'Fornavn', 'required' => true, 'min' => 1, 'max' => 200],
      'lname' => ['display' => 'Efternavn', 'required' => true, 'min' => 1, 'max' => 200],
      'phone' => ['display' => 'Telefon', 'required' => false, 'min' => 8, 'max' => 12],
      'email' => ['display' => 'Email', 'required' => true, 'valid_email' => true, 'unique' => 'users', 'min' => 4, 'max' => 200],
      'password' => ['display' => 'Adgangskode', 'required' => true, 'min' => 5, 'max' => 30],
      'confirm' => ['display' => 'Bekræft adgangskode', 'required' => true, 'matches' => 'password']
    ]);

    if ($validator->passed()) {
      $form_valid = true;
      if (isset($_SESSION['us_lang'])) {
        $newLang = $_SESSION['us_lang'];
      } else {
        $newLang = 'da-DK';
      }

      try {
        $dbo->query(
          "INSERT INTO uacc (fname, lname, phoneNumber, email, password, permissions, join_date, email_verified, vericode, force_pr, vericode_expiry, oauth_tos_accepted, language,active)
          VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $fname, $lname, $phone, $email, password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]), $permission_id, $join_date, 1, $vericode, 0, $vericode_expiry, 1, $newLang, 1);

        $theNewId = $dbo->lastInsertID();

        if (isset($_POST['sendEmail'])) {

          $params = [
            'username' => $username,
            'password' => $password,
            'sitename' => $site_name,
            'force_pr' => 0,
            'fname' => $fname,
            'email' => rawurlencode($email),
            'vericode' => $vericode,
            'join_vericode_expiry' => 24,
          ];
          $to = rawurlencode($email);
          $subject = html_entity_decode($site_name, ENT_QUOTES);
          $body = email_body('_email_adminUser.php', $params);
          email($to, $subject, $body);
        }

        logger($user->data()['id'], 'User Manager', "Added user $username.");
        usSuccess("$username oprettet");
        Redirect::to($user_page_url . '?action=new&id=' . $theNewId);
      } catch (Exception $e) {
        exit($e->getMessage());
      }
    }
  }
}

$usernameReq = $user_id > 1 ? " AND username <> 'admin' " : "";
$query = "SELECT
  u.*, p.name AS permission_name
  FROM uacc AS u
  LEFT OUTER JOIN permissions AS p ON u.permissions = p.id
  WHERE u.permissions <> 2" . $usernameReq;

if(!empty($_POST['search'])){
  $search = Input::get('searchTerm');
  $query .= " AND (fname LIKE '%" . $search . "%' OR lname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%')";
}

$userData = $dbo->query($query) -> fetchAll();
$random_password = random_password();

foreach ($validator->errors() as $error) {
  usError($error);
} ?>

<section id="users-list">
    <div class="row">
        <div class="col-12 mb-2">
            <h2>Brugere</h2>
            <div class="row" style="margin-top:1vw;">            
              <div class="form-actions">
                <div class="buttons-wrapper">
                  <button class="save w-40p no-margin" data-bs-toggle="modal" data-bs-target="#adduser" id="add-user-button"><i class="fa fa-plus"></i> Tilføj bruger</button>
                </div>
              </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
              <div class="card-body">
                  <table id="userstable" class='table table-hover table-list-search'>
                    <thead>
                        <tr>
                        <th>Navn</th>
                        <th>Telefon</th>
                        <th>Email</th>
                        <th>Sidste login</th>
                        <th>Brugertype</th>
                        <th>Tilstand</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userData as $v1) { 
                            $user_url = $us_url_root . "um/admin/user.php?id=" . $v1['id'];
                        ?>
                        <tr>
                            <td> <a class="nounderline text-dark" href='<?=$user_url ?>'><?php echo $v1['fname']; ?> <?php echo $v1['lname']; ?></a> </td>
                            <td> <a class="nounderline text-dark" href='<?=$user_url ?>'><?php echo $v1['phoneNumber']; ?></a> </td>
                            <td> <a class="nounderline text-dark" href='<?=$user_url ?>'><?php echo $v1['email']; ?></a> </td>
                            <td>
                              <?php if ($v1['last_login'] != "0000-00-00 00:00:00") {
                                echo $v1['last_login'];
                              } else { ?>
                                  <i>Aldrig</i>
                              <?php } ?>
                            </td>
                            <td><?= $v1['permission_name'] ?></td>
                            <td>
                              <?php if($v1['active'] == 0){ ?> Inaktiv <?php } else { ?> Aktiv <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                  </table>
              </div>
            </div>
        </div>
    </div>
</section>
<div id="adduser" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Ny brugerkonto</h4>
        <button type="button" class="btn btn-outline-secondary float-right" data-bs-dismiss="modal">&times;</button>
      </div>

      <form class="form-signup mb-0" action="" method="POST">
        <div class="modal-body">

          <div class="form-group" id="username-group">
            <label for="permission_id">Brugertype</label>
            <select class="district-list dropdown form-control" id="permission_id" name="permission_id">
                  <?php foreach($permissions as $permission) { ?>
                    <option value="<?=$permission['id']?>"><?=$permission['name']?></option>
                  <?php } ?>                  
            </select>
          </div>

          <div class="form-group" id="fname-group">
            <label>Fornavn</label>
            <input type="search" class="form-control" id="fname" name="fname" 
                value="<?php if (!$form_valid && !empty($_POST)) { echo $fname; } ?>" required autocomplete="off">
          </div>

          <div class="form-group" id="lname-group">
            <label>Efternavn</label>
            <input type="search" class="form-control" id="lname" name="lname" 
                value="<?php if (!$form_valid && !empty($_POST)) { echo $lname; } ?>" required autocomplete="off">
          </div>

          <div class="form-group" id="email-group">
            <label>Telefon</label>
            <input class="form-control" type="search" name="phone" id="phone" 
                value="<?php if (!$form_valid && !empty($_POST)) { echo $phone; } ?>" autocomplete="off">
          </div>

          <div class="form-group" id="email-group">
            <label>Email</label>
            <input class="form-control" type="search" name="email" id="email" 
                value="<?php if (!$form_valid && !empty($_POST)) { echo $email; } ?>" required autocomplete="off">
          </div>

          <div class="form-group">
            <label> Adgangskode (5 - 30 chars) </label>

            <div class="input-group" data-container="body">
              <div class="input-group-append">
                <span class="input-group-text password_view_control" id="addon1">
                  <span class="fa fa-eye"></span>
                </span>
              </div>
              <input class="form-control" type="password" name="password" id="password" placeholder="Password" required autocomplete="off" aria-describedby="passwordhelp">

            </div>
          </div>
          <div class="form-group">
            <label>Bekræft adgangskode</label>
            <div class="input-group" data-container="body">
              <div class="input-group-prepend">
                <span class="input-group-text password_view_control" id="addon1">
                  <span class="fa fa-eye"></span>
                </span>
              </div>
              <input type="password" id="confirm" name="confirm" class="form-control" autocomplete="off" placeholder="Confirm Password" required>

            </div>
          </div>
        </div>
        <div class="modal-footer form-actions">
          <input type="hidden" name="csrf" value="<?php echo Token::generate(); ?>" />
          <button type="button" class="cancel" data-bs-dismiss="modal">Fortryd</button>
          <input class='save' type='submit' id="addUser" name="addUser" value='Tilføj' />
        </div>
      </form>
    </div>
  </div>
</div>

<?php include_once __DIR__."/../../master-pages/footer.php"?>

<script src="<?=$us_url_root?>assets/js/um/admin/users.js"></script>

</body>
</html>