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
    $qualification_id = $_POST['qualification'];
    $experience = $_POST['experience'];
    $namePhoneReference = $_POST['namePhoneReference'];

    $validation = new Validate();

    $validation->check($_POST, [
        'fname' => [ 'display' => 'Fornavn', 'required' => true, 'min' => 1, 'max' => 30 ],
        'lname' => [ 'display' => 'Efternavn', 'required' => true, 'min' => 1, 'max' => 30 ],
        'phone' => [ 'display' => 'Telefon', 'required' => false, 'min' => 8, 'max' => 12 ],
        'email' => [ 'display' => 'Email', 'required' => true, 'valid_email' => true, 'unique' => 'applications', 'min' => 5, 'max' => 50 ],
        'experience' => [ 'display' => 'Erfaring', 'required' => true, 'min' => 1, 'max' => 20 ],
        'namePhoneReference' => [ 'display' => 'Reference data', 'required' => true, 'min' => 1, 'max' => 200 ],
    ]);

    if ($validation->passed()) {        
        
        try {
            $fields = [
                'fname' => $fname,
                'lname' => $lname,
                'phone' => $phone,
                'email' => $email,
                'qualification_id' => $qualification_id,
                'experience' => $experience,
                'namePhoneReference' => $namePhoneReference
            ];
        
            if($db->insert('applications', $fields)) {
                $theNewId = $db->lastId();
                $body = get_email_body('_email_new_application_notify_admins.php');
                $body = str_replace("{{fname}}", $fname, $body);
                $body = str_replace("{{lname}}", $lname, $body);
                $body = str_replace("{{application_url}}", $url_host.$application_page_url.$theNewId, $body);
                send_email($admin_email_list, 'Ny vikar ansøgning', $body);
                echo "success";
            } else {
                // Handle errors
                echo "Fejl ved at sende ansøgningen: " . $db->error;
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
else {
    $qualifications = $dbo->query("SELECT qualification_id, qualification_name FROM Qualifications")->fetchAll();
}

require __DIR__.'/views/_join_form.php';
?>

<?php include_once __DIR__."/../master-pages/footer.php"?>

</body>
</html>