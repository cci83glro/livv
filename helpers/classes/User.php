<?php

class User
{
    private $_db;
    private $_data;
    private $_sessionName;
    private $_isLoggedIn;
    private $_cookieName;
    private $_isNewAccount;
    public $tableName = 'users';

    public function __construct($user = null, $loginHandler = null)
    {
        $this->_db = dbo::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if ($this->find($user,$loginHandler)) {
                    $this->_isLoggedIn = true;
                } else {
                    //process Logout
                }
            }
        } else {
            $this->find($user,$loginHandler);
        }
    }

    public function create($fields = [])
    {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception($this->_db->errorString());
        } else {
            $user_id = $this->_db->lastId();
        }
        $query = $this->_db->insert('user_permission_matches', ['user_id' => $user_id, 'permission_id' => 1]);

        return $user_id;
    }

    public function find($user = null, $loginHandler = null)
    {
        if (isset($_SESSION['cloak_to'])) {
            $user = $_SESSION['cloak_to'];
        }

        $query = "";

        if ($user) {
            // $field = 'email';
            if ($loginHandler !== null) {                
                if($loginHandler == "forceEmail"){
                    $query = "SELECT * FROM users WHERE email = '" . $user . "'";
                }  elseif (!filter_var($user, FILTER_VALIDATE_EMAIL) === false) {
                    $query = "SELECT * FROM users WHERE email = '" . $user . "'";
                } else {
                    $query = "SELECT * FROM users WHERE username = '" . $user . "'";
                }
            } else {
                if (is_numeric($user)) {
                    $query = "SELECT * FROM users WHERE id = " . $user;
                } elseif (!filter_var($user, FILTER_VALIDATE_EMAIL) === false) {
                    $query = "SELECT * FROM users WHERE email = '" . $user . "'";
                } else {
                    $query = "SELECT * FROM users WHERE username = '" . $user . "'";
                }
            }

            //$data = $this->_db->get('users', [$field, '=', $user], ['active', '=', '1']);
            $data = $this->_db->query($query)->fetchAll();

            if (sizeof($data) > 0) {
                $this->_data = $data[0];
                return $this->_data['active'] > 0;
            }
        }

        return false;
    }

    public function login($username = null, $password = null, $remember = false)
    {
        if (!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()['id']);
        } else {
            $user = $this->find($username);
            if ($user) {
                if (password_verify($password, $this->data()['password'])) {
                    Session::put($this->_sessionName, $this->data()['id']);
                    if ($remember) {
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('users_session', ['user_id', '=', $this->data()['id']]);

                        $this->_db->insert('users_session', [
                            'user_id' => $this->data()['id'],
                            'hash' => $hash,
                            'uagent' => Session::uagent_no_version(),
                        ]);

                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }
                    $date = date('Y-m-d H:i:s');
                    $this->_db->query('UPDATE users SET last_login = ?, logins = logins + 1 WHERE id = ?', [$date, $this->data()['id']]);
                    $_SESSION['last_confirm'] = date('Y-m-d H:i:s');
                    $this->_db->insert('logs', ['logdate' => $date, 'user_id' => $this->data()['id'], 'logtype' => 'Login', 'lognote' => 'User logged in.', 'ip' => ipCheck()]);
                    $ip = ipCheck();
                    $q = $this->_db->query('SELECT id FROM us_ip_list WHERE ip = ?', [$ip]);
                    $c = $q->count();
                    if ($c < 1) {
                        $this->_db->insert('us_ip_list', [
                            'user_id' => $this->data()['id'],
                            'ip' => $ip,
                        ]);
                    } else {
                        $f = $q->first();
                        $this->_db->update('us_ip_list', $f['id'], [
                            'user_id' => $this->data()['id'],
                            'ip' => $ip,
                        ]);
                    }

                    return true;
                }
            }
        }

        return false;
    }

    public function loginEmail($email = null, $password = null, $remember = false)
    {
        if (!$email && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()['id']);
        } else {
            $user = $this->find($email, 1);

            if ($user) {
                if (password_verify($password, $this->data()['password'])) {
                    Session::put($this->_sessionName, $this->data()['id']);

                    if ($remember) {
                        $hash = Hash::unique();
                        // $hashCheck = $this->_db->get('users_session', ['user_id', '=', $this->data()['id']]);

                        $this->_db->query('INSERT INTO users_session (`user_id`, `hash`, `uagent`) VALUES(?,?,?)', $this->data()['id'], $hash, Session::uagent_no_version());
                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }
                    $date = date('Y-m-d H:i:s');
                    $this->_db->query('UPDATE users SET last_login = ?, logins = logins + 1 WHERE id = ?', [$date, $this->data()['id']]);
                    $_SESSION['last_confirm'] = date('Y-m-d H:i:s');                    
                    logger($this->data()['id'], 'login', 'User logged in.');

                    $ip = ipCheck();
                    $q = $this->_db->query('SELECT id FROM us_ip_list WHERE ip = ?', [$ip])->fetchAll();
                    $c = sizeof($q);
                    if ($c < 1) {
                        $this->_db->query('INSERT INTO us_ip_list(`user_id`, `ip`) VALUES(?,?)', $this->data()['id'], $ip);
                    } else {
                        $f = $q[0];
                        $this->_db->query('UPDATE us_ip_list SET `user_id`=?, `ip`=? WHERE id=?', $this->data()['id'], $ip, $f['id']);
                    }

                    return true;
                }
            }
        }

        return false;
    }

    //Google oAuth Login Stuff
    public function checkUser($oauth_provider, $oauth_uid, $fname, $lname, $email, $gender, $locale, $link, $picture)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');
        $fakeUN = $email;
        $active = 1;
        //Check to see if a user has Google oAuth
        $prevQuery = $this->_db->query("SELECT * FROM users WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'") or die('Google oAuth Error');

        //If a user is already setup with oAuth, get the latest info
        if ($prevQuery->count() > 0) {
            // die("user already has oauth");
            $update = $this->_db->query("UPDATE $this->tableName SET oauth_provider = '".$oauth_provider."', oauth_uid = '".$oauth_uid."', fname = '".$fname."', lname = '".$lname."', email = '".$email."', username = '".$fakeUN."',permissions = '".$active."',email_verfied = '".$active."',active = '".$active."',picture = '".$picture."', gpluslink = '".$link."', modified = '".date('Y-m-d H:i:s')."' WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'") or die('Google oAuth Error');
        } else {
            //Check to see if the user has a regular UserSpice account that matches the google email.
            $findExistingUS = $this->_db->query('SELECT * FROM users WHERE email = ?', [$email]);
            $foundUS = $findExistingUS->count();
            $found = $findExistingUS->count();

            if ($foundUS == 1) {
                //Found an existing UserSpice user with the same email
                // die("user already has userspice");
            } else {
                //If a user has neither UserSpice nor oAuth creds
                //die("user has neither");
                //$password = password_hash(Token::generate(),PASSWORD_BCRYPT,array('cost' => 12));
                $settings = $this->_db->query('SELECT * FROM settings')->first();
                $username = $email;

                $insert = $this->_db->query("INSERT INTO $this->tableName SET `password` = NULL,username = '".$username."',active = '".$active."',oauth_provider = '".$oauth_provider."', oauth_uid = '".$oauth_uid."',permissions = '".$active."', email_verified = '".$active."', fname = '".$fname."', lname = '".$lname."', email = '".$email."', picture = '".$picture."', gpluslink = '".$link."', join_date = '".date('Y-m-d H:i:s')."',created = '".date('Y-m-d H:i:s')."', modified = '".date('Y-m-d H:i:s')."'") or die('Google oAuth Error');
                $lastID = $insert->lastId();

                $insert2 = $this->_db->query("INSERT INTO user_permission_matches SET user_id = $lastID, permission_id = 1");
                $this->_isNewAccount = true;
            }
        }

        $query = $this->_db->query("SELECT * FROM $this->tableName WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'") or die('Google oAuth Error');
        $result = $query->first();
        if ($this->_isNewAccount) {
            $result->isNewAccount = true;
        } else {
            $result->isNewAccount = false;
        }

        return $result;
    }

    // End of Google Section

    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }

    public function data()
    {
        return $this->_data;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }

    public function notLoggedInRedirect($location)
    {
        if ($this->_isLoggedIn) {
            return true;
        } else {
            Redirect::to($location);
        }
    }

    public function logout()
    {
        if ($this->_isLoggedIn) {
            $this->_db->query('DELETE FROM users_session WHERE user_id = ? AND uagent = ?', [$this->data()['id'], Session::uagent_no_version()]);
        }
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
        session_unset();
        session_destroy();
    }

    public function update($fields = [], $id = null)
    {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()['id'];
        }

        if (!$this->_db->update('users', $id, $fields)) {
            throw new Exception('There was a problem updating.');
        }
    }
}