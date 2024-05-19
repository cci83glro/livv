<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
//ini_set('allow_url_fopen', 1);
//header('X-Frame-Options: DENY');

$public = false;
$permissions = array(2);
$pageTitle = 'Brugere';
require_once '../../header.php';

$errors = $successes = [];
$act = $db->query('SELECT * FROM email')->first();
$act = $act->email_act;
$form_valid = true;
$permissions = $db->query('SELECT * FROM permissions')->results();

$validation = new Validate();

if (!empty($_POST)) {
  if (!Token::check(Input::get('csrf'))) {
    include $abs_us_root . $us_url_root . 'um/admin/token_error.php';
  }

  //Manually Add User
  if (!empty($_POST['addUser'])) {

    $vericode_expiry = date('Y-m-d H:i:s', strtotime("+$settings->join_vericode_expiry hours", strtotime(date('Y-m-d H:i:s'))));
    $join_date = date('Y-m-d H:i:s');
    $permission_id = Input::get('permission_id');
    $fname = Input::get('fname');
    $lname = Input::get('lname');
    $phone = Input::get('phone');
    $email = Input::get('email');
    $username = Input::get('username');
    $password = Input::get('password');
    $vericode = randomstring(15);

    $form_valid = false; // assume the worst

    $validation->check($_POST, [
      'fname' => ['display' => 'Fornavn', 'required' => true, 'min' => 1, 'max' => 200],
      'lname' => ['display' => 'Efternavn', 'required' => true, 'min' => 1, 'max' => 200],
      'phone' => ['display' => 'Telefon', 'required' => false, 'min' => 8, 'max' => 12],
      'email' => ['display' => 'Email', 'required' => true, 'valid_email' => true, 'unique' => 'users', 'min' => 4, 'max' => 200],
      'password' => ['display' => 'Adgangskode', 'required' => true, 'min' => 5, 'max' => 30],
      'confirm' => ['display' => 'Bekræft adgangskode', 'required' => true, 'matches' => 'password']
    ]);

    if ($validation->passed()) {
      $form_valid = true;
      if (isset($_SESSION['us_lang'])) {
        $newLang = $_SESSION['us_lang'];
      } else {
        $newLang = $settings->default_language;
      }

      try {
        $fields = [
          'fname' => $fname,
          'lname' => $lname,
          'phoneNumber' => $phone,
          'email' => $email,
          'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
          'permissions' => $permission_id,
          'join_date' => $join_date,
          'email_verified' => 1,
          'vericode' => $vericode,
          'force_pr' => $settings->force_pr,
          'vericode_expiry' => $vericode_expiry,
          'oauth_tos_accepted' => true,
          'language' => $newLang,
          'active' => 1,
        ];

        $db->insert('users', $fields);
        $theNewId = $db->lastId();

        // $perm = Input::get('perm');
        // $addNewPermission = ['user_id' => $theNewId, 'permission_id' => $permission_id];
        // $db->insert('user_permission_matches', $addNewPermission);

        include $abs_us_root . $us_url_root . 'usersc/scripts/during_user_creation.php';
        if (isset($_POST['sendEmail'])) {

          $params = [
            'username' => $username,
            'password' => $password,
            'sitename' => $settings->site_name,
            'force_pr' => $settings->force_pr,
            'fname' => $fname,
            'email' => rawurlencode($email),
            'vericode' => $vericode,
            'join_vericode_expiry' => $settings->join_vericode_expiry,
          ];
          $to = rawurlencode($email);
          $subject = html_entity_decode($settings->site_name, ENT_QUOTES);
          $body = email_body('_email_adminUser.php', $params);
          email($to, $subject, $body);
        }

        logger($user->data()->id, 'User Manager', "Added user $username.");
        usSuccess("$username oprettet");
        Redirect::to($user_page_url . '?action=new&id=' . $theNewId);
      } catch (Exception $e) {
        exit($e->getMessage());
      }
    }
  }
}

if($settings->uman_search == 0){
  $usernameReq = $user_id > 1 ? " AND username <> 'admin' " : "";
  $query = "SELECT
    u.*, p.name AS permission_name
    FROM users AS u
    LEFT OUTER JOIN permissions AS p ON u.permissions = p.id
    WHERE u.permissions <> 2" . $usernameReq;
  
  if(!empty($_POST['search'])){
    $search = Input::get('searchTerm');
    $query .= " AND (fname LIKE '%" . $search . "%' OR lname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%')";
  }

  $userData = $db->query($query) -> results();
}
$random_password = random_password();

foreach ($validation->errors() as $error) {
  usError($error);
} ?>

<section id="users-list">
    <div class="row">
        <div class="col-12 mb-2">
            <h2>Brugere</h2>
            <div class="row" style="margin-top:1vw;">            
              <div class="form-actions">
                <div class="buttons-wrapper">
                  <button class="save" data-bs-toggle="modal" data-bs-target="#adduser"><i class="fa fa-plus"></i> Tilføj bruger</button>
                </div>
              </div>
            </div>
        <?php
        if($settings->uman_search == 1){ ?>
            <div class="row">
            <div class="col-12 col-sm-6 offset-sm-3">
                <form class="" action="" method="post">
                <?=tokenHere();?>
                <div class="input-group">
                    <input type="text" name="searchTerm" value="" class="form-control" placeholder="Søg ...">
                    <input type="submit" name="search" value="Search" class="btn btn-outline-primary">
                </div>
                <small>Søg efter fornavn, efternavn eller email</small>
                </form>
            </div>
            </div>
        <?php } ?>
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
                            $user_url = $us_url_root . "um/admin/user.php?id=" . $v1->id;
                        ?>
                        <tr>
                            <td> <a class="nounderline text-dark" href='<?=$user_url ?>'><?php echo $v1->fname; ?> <?php echo $v1->lname; ?></a> </td>
                            <td> <a class="nounderline text-dark" href='<?=$user_url ?>'><?php echo $v1->phoneNumber; ?></a> </td>
                            <td> <a class="nounderline text-dark" href='<?=$user_url ?>'><?php echo $v1->email; ?></a> </td>
                            <td>
                              <?php if ($v1->last_login != "0000-00-00 00:00:00") {
                                echo $v1->last_login;
                              } else { ?>
                                  <i>Aldrig</i>
                              <?php } ?>
                            </td>
                            <td><?= $v1->permission_name ?></td>
                            <td>
                              <?php if($v1->active == 0){ ?> Inaktiv <?php } else { ?> Aktiv <?php } ?>
                              <?php
                                if ($act == 1 && $v1->email_verified == 1) { ?>
                                  <i class='fa fa-envelope' data-bs-toggle="tooltip" title="User email is verified"></i>
                              <?php } ?>
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
                    <option value="<?=$permission -> id ?>"><?=$permission -> name ?></option>
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

              <input class="form-control" type="password" name="password" id="password" <?php if ($settings->force_pr == 1) { ?> value="<?php echo $random_password; ?>" readonly<?php } ?> placeholder="Password" required autocomplete="off" aria-describedby="passwordhelp">

              <?php if ($settings->force_pr == 1) { ?>
                <div class="input-group-append">
                  <span class="input-group-text" id="addon2">
                    <a class="nounderline pwpopover" data-container="body" data-toggle="popover" data-placement="top" title="Why can't I edit this?" data-bs-toggle="tooltip" title="The Administrator has manual creation password resets enabled. If you choose to send an email to this user, it will supply them with the password reset link and let them know they have an account. If you choose to not, you should manually supply them with this password (discouraged).">
                      <i class="fa fa-question"></i>
                    </a>
                  </span>
                </div>
              <?php } ?>
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

              <input type="password" id="confirm" name="confirm" <?php if ($settings->force_pr == 1) { ?> value="<?php echo $random_password; ?>" readonly <?php } ?> class="form-control" autocomplete="off" placeholder="Confirm Password" required>

              <?php if ($settings->force_pr == 1) { ?>
                <div class="input-group-append">
                  <span class="input-group-text" id="addon2">
                    <a class="nounderline pwpopover" data-container="body" data-toggle="popover" data-placement="top" title="Why can't I edit this?" data-bs-toggle="tooltip" title="The Administrator has manual creation password resets enabled. If you choose to send an email to this user, it will supply them with the password reset link and let them know they have an account. If you choose to not, you should manually supply them with this password (discouraged).">
                      <i class="fa fa-question"></i>
                    </a>
                  </span>
                </div>
              <?php } ?>
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

<?php include_once $abs_us_root.$us_url_root."footer.php"?>

<script type="text/javascript" src="<?= $us_url_root ?>users/js/pagination/datatables.min.js"></script>
<script src="<?=$us_url_root?>assets/js/um/admin/users.js"></script>

</body>
</html>