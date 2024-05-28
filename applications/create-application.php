<?php
ini_set('allow_url_fopen', 1);
header('X-Frame-Options: DENY');
$public = true;
$extra_head_html = '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
$pageTitle = "Ansøg som vikar";
require_once __DIR__.'/../master-pages/header.php';

$form_method = 'POST';
$form_action = 'create-application.php';
$vericode = randomstring(15);
$dbo = dbo::getInstance();

$form_valid = false;

if (!empty($_POST)) {
    $token = $_POST['csrf'];
    // if (!Token::check($token)) {
    //     include __DIR__.'/../um/admin/token_error.php';
    // }

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

    $validator = new Validator($dbo);

    $validator->check($_POST, [
        'fname' => [ 'display' => 'Fornavn', 'required' => true, 'min' => 1, 'max' => 30 ],
        'lname' => [ 'display' => 'Efternavn', 'required' => true, 'min' => 1, 'max' => 30 ],
        'phone' => [ 'display' => 'Telefon', 'required' => false, 'min' => 8, 'max' => 12 ],
        'email' => [ 'display' => 'Email', 'required' => true, 'valid_email' => true, 'unique' => 'applications', 'min' => 5, 'max' => 50 ],
        'experience' => [ 'display' => 'Erfaring', 'required' => true, 'min' => 1, 'max' => 20 ],
        'namePhoneReference' => [ 'display' => 'Reference data', 'required' => true, 'min' => 1, 'max' => 200 ],
    ]);

    if ($validator->passed()) {        
        
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

            $query = "INSERT applications(`fname`, `lname`, `phone`, `email`, `qualification_id`, `experience`, `namePhoneReference`)
            VALUES(?,?,?,?,?,?,?)";
        
            if($dbo->query($query, $fname, $lname, $phone, $email, $qualification_id, $experience, $namePhoneReference)) {
                $theNewId = $dbo->lastInsertID();
                $body = get_email_body('_email_new_application_notify_admins.php');
                $body = str_replace("{{fname}}", $fname, $body);
                $body = str_replace("{{lname}}", $lname, $body);
                $body = str_replace("{{application_url}}", $application_page_url, $body);
                send_email($admin_email_list, 'Ny vikar ansøgning', $body);
                if (file_exists(__DIR__.'/views/_joinThankYou.php')) {
                    include_once(__DIR__.'/views/_joinThankYou.php');
                    die();
                }
            } else {
                // Handle errors
                echo "Fejl ved at sende ansøgningen: " . $db->error;
            }

        } catch (Exception $e) {            
            die($e->getMessage());
        }
        // if ($form_valid == true) {

        //     logger($theNewId, 'User', 'Registration completed.');
        //     if (file_exists($us_url_root.'applications/views/_joinThankYou.php')) {
        //         Redirect::to($us_url_root . "um/complete.php?action=thank_you_join");
        //     } else {
        //         Redirect::to($us_url_root . "um/complete.php?action=thank_you");
        //     }
        // }
    } else {
        foreach($validator->_errors as $e){
            usError($e);
        }
        Redirect::to(currentPage());
    } //Validation
} //Input exists
else {
    $qualifications = $dbo->query("SELECT qualification_id, qualification_name FROM qualifications")->fetchAll();
}

require __DIR__.'/views/_join_form.php';
?>

<?php include_once __DIR__."/../master-pages/footer.php"?>

</body>
</html>