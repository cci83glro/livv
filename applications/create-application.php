<?php
ini_set('allow_url_fopen', 1);
header('X-Frame-Options: DENY');
$public = true;
$extra_head_html = '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
require_once __DIR__.'/../master-pages/header.php';

$form_method = 'POST';
$form_action = 'create-application.php';
$vericode = randomstring(15);

$form_valid = false;

if (!empty($_POST)) {
    $token = $_POST['csrf'];
    if (!Token::check($token)) {
        include $abs_us_root.$us_url_root.'usersc/scripts/token_error.php';
    }

    $sex = Input::get('sex');
    if($sex != '') {
        die('Registrering OK');
    }

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $experience = $_POST['experience'];

    $validation = new Validate();

    $validation->check($_POST, [
        'fname' => [ 'display' => 'Fornavn', 'required' => true, 'min' => 1, 'max' => 30 ],
        'lname' => [ 'display' => 'Efternavn', 'required' => true, 'min' => 1, 'max' => 30 ],
        'phone' => [ 'display' => 'Telefon', 'required' => false, 'min' => 8, 'max' => 12 ],
        'email' => [ 'display' => 'Email', 'required' => true, 'valid_email' => true, 'unique' => 'users', 'min' => 5, 'max' => 50 ],
        'experience' => [ 'display' => 'Erfaring', 'required' => true, 'min' => 1, 'max' => 200 ],
    ]);

    if ($validation->passed()) {        
        
        try {
            $fields = [
                'fname' => $fname,
                'lname' => $lname,
                'phone' => $phone,
                'email' => $email,
                'experience' => $experience,
            ];
        
            if($db->insert('applications', $fields)) {
                $body = get_email_body('_email_new_application_notify_admins.php');
                $body = str_replace("{{fname}}", $fname, $body);
                $body = str_replace("{{lname}}", $lname, $body);
                $body = str_replace("{{user_url}}", $url_host.$user_page_url.$theNewId, $body);
                send_email($admin_email_list, 'Ny vikar konto oprettet', $body);
                echo "success";
            } else {
                // Handle errors
                echo "Error creating booking: " . $db->error;
            }

        } catch (Exception $e) {            
            die($e->getMessage());
        }
        if ($form_valid == true) {

            logger($theNewId, 'User', 'Registration completed.');
            if (file_exists($abs_us_root.$us_url_root.'um/views/_joinThankYou.php')) {
                Redirect::to($us_url_root . "um/complete.php?action=thank_you_join");
            } else {
                Redirect::to($us_url_root . "um/complete.php?action=thank_you");
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

<?php include_once $abs_us_root.$us_url_root."master-pages/footer.php"?>
<script src="<?=$us_url_root?>assets/js/um/join.js"></script>

</body>
</html>