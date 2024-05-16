<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
ini_set('allow_url_fopen', 1);
header('X-Frame-Options: DENY');
$public = true;
$extra_head_html = '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
require_once '../header.php';

if ($user->isLoggedIn()) {
    Redirect::to($us_url_root.'index.php');
}

$form_method = 'POST';
$form_action = 'join.php';
$vericode = randomstring(15);

$act = 0;
$form_valid = false;

//If you say in email settings that you do NOT want email activation,
//new users are active in the database, otherwise they will become
//active after verifying their email.
if ($act == 1) {
    $pre = 0;
} else {
    $pre = 1;
}

if (Input::exists()) {
    $token = $_POST['csrf'];
    if (!Token::check($token)) {
        include $abs_us_root.$us_url_root.'usersc/scripts/token_error.php';
    }

    $sex = Input::get('sex');
    if($sex != '') {
        die('Registrering OK');
    }

    $fname = Input::get('fname');
    $lname = Input::get('lname');
    $email = Input::get('email');
    $phone = Input::get('phone');

    $validation = new Validate();
        if (pluginActive('userInfo', true)) {
            $is_not_email = false;
        } else {
            $is_not_email = true;
        }

        $validation->check($_POST, [
          'fname' => [
                'display' => 'Fornavn',
                'required' => true,
                'min' => 1,
                'max' => 60,
          ],
          'lname' => [
                'display' => 'Efternavn',
                'required' => true,
                'min' => 1,
                'max' => 60,
          ],
          'phone' => [
            'display' => 'Telefon',
            'required' => false,
            'min' => 8,
            'max' => 12,
          ],
          'email' => [
                'display' => 'Email',
                'required' => true,
                'valid_email' => true,
                'unique' => 'users',
                'min' => 5,
                'max' => 100,
          ],
          'password' => [
                'display' => 'Adgangskode',
                'required' => true,
                'min' => $settings->min_pw,
                'max' => $settings->max_pw,
          ],
          'confirm' => [
                'display' => 'Bekræft adgangskode',
                'required' => true,
                'matches' => 'password',
          ],
        ]);

    if ($validation->passed()) {
        $form_valid = true;
        $user = new User();
        $join_date = date('Y-m-d H:i:s');
        $params = [
                    'fname' => Input::get('fname'),
                    'email' => $email,
                    'vericode' => $vericode,
                    'join_vericode_expiry' => $settings->join_vericode_expiry,
                    ];
        
        if($act == 1){
            $vericode_expiry = date('Y-m-d H:i:s', strtotime("+$settings->join_vericode_expiry hours", strtotime(date('Y-m-d H:i:s'))));
        }else{
            $vericode_expiry = date('Y-m-d H:i:s');
        }

        try {
            if(isset($_SESSION['us_lang'])){
                $newLang = $_SESSION['us_lang'];
            }else{
                $newLang = $settings->default_language;
            }
            $fields = [
                'fname' => ucfirst(Input::get('fname')),
                'lname' => ucfirst(Input::get('lname')),
                'phoneNumber' => Input::get('phone'),
                'email' => Input::get('email'),
                'password' => password_hash(Input::get('password', true), PASSWORD_BCRYPT, ['cost' => 12]),
                'permissions' => 3,
                'join_date' => $join_date,
                'email_verified' => $pre,
                'vericode' => $vericode,
                'vericode_expiry' => $vericode_expiry,
                'oauth_tos_accepted' => true,
                'language'=>$newLang,
                'active'=>0
                ];

            $theNewId = $user->create($fields);

            // if ($act == 1) {
            //     //Verify email address settings
            //     $to = rawurlencode($email);
            //     $subject = html_entity_decode($settings->site_name, ENT_QUOTES);
            //     $body = get_email_body('mails/um/_email_adminPwReset.php', $params);                
            //     send_email($to, $subject, $body);                
            // }

            $body = get_email_body('_email_new_account_notify_admins.php');
            $body = str_replace("{{fname}}", $fname, $body);
            $body = str_replace("{{lname}}", $lname, $body);
            $body = str_replace("{{user_url}}", $http_host.$user_page_url.$theNewId, $body);
            send_email($admin_email_list, 'Ny vikar konto oprettet', $body);

            $body = get_email_body('_email_new_account_notify_user.php');
            $body = str_replace("{{fname}}", $fname, $body);
            $body = str_replace("{{lname}}", $lname, $body);
            send_email($email, 'Din LivVikar konto er oprettet', $body);
        } catch (Exception $e) {            
            die($e->getMessage());
        }
        if ($form_valid == true) {
            //this allows the plugin hook to kill the post but it must delete the created user
            include $abs_us_root.$us_url_root.'usersc/scripts/during_user_creation.php';

            if ($act == 1) {
                logger($theNewId, 'User', 'Registration completed and verification email sent.');
                Redirect::to($us_url_root . "um/complete.php?action=thank_you_verify");

            } else {
                logger($theNewId, 'User', 'Registration completed.');
                if (file_exists($abs_us_root.$us_url_root.'um/views/_joinThankYou.php')) {
                    Redirect::to($us_url_root . "um/complete.php?action=thank_you_join");
                } else {
                    Redirect::to($us_url_root . "um/complete.php?action=thank_you");
                }
            }
        }
    } else {
        foreach($validation->_errors as $e){
            usError($e);
        }
        Redirect::to(currentPage());
    } //Validation
} //Input exists



require $abs_us_root.$us_url_root.'um/views/_join.php';
?>

<?php include_once $abs_us_root.$us_url_root."footer.php"?>
<script src="<?=$us_url_root?>assets/js/um/join.js"></script>

</body>
</html>