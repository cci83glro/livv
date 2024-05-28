<?php

class User
{
    private $_db;
    private $_data;
    private $_sessionName;
    private $_isLoggedIn;
    private $_cookieName;
    private $_isNewAccount;
    private $tableName = 'uacc';

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
            if ($this->find($user,$loginHandler)) {
                $this->_isLoggedIn = true;
            }
            
        }
    }

    public function find($user = null, $loginHandler = null)
    {
        if (isset($_SESSION['cloak_to'])) {
            $user = $_SESSION['cloak_to'];
        }

        $query = "";

        if ($user) {
            if ($loginHandler !== null) {                
                if($loginHandler == "forceEmail"){
                    $query = "SELECT * FROM " . $this->tableName . " WHERE email = '" . $user . "'";
                }  elseif (!filter_var($user, FILTER_VALIDATE_EMAIL) === false) {
                    $query = "SELECT * FROM " . $this->tableName . " WHERE email = '" . $user . "'";
                } else {
                    $query = "SELECT * FROM " . $this->tableName . " WHERE username = '" . $user . "'";
                }
            } else {
                if (is_numeric($user)) {
                    $query = "SELECT * FROM " . $this->tableName . " WHERE id = " . $user;
                } elseif (!filter_var($user, FILTER_VALIDATE_EMAIL) === false) {
                    $query = "SELECT * FROM " . $this->tableName . " WHERE email = '" . $user . "'";
                } else {
                    $query = "SELECT * FROM " . $this->tableName . " WHERE username = '" . $user . "'";
                }
            }

            //$data = $this->_db->get($tableName, [$field, '=', $user], ['active', '=', '1']);
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
                        //$hashCheck = $this->_db->get('users_session', ['user_id', '=', $this->data()['id']]);

                        $this->_db->query('INSERT users_session(user_id, hash, uagent) VALUES(?,?,?)', $this->data()['id'], $hash, Session::uagent_no_version());

                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }
                    $date = date('Y-m-d H:i:s');
                    $this->_db->query('UPDATE ' . $this->tableName . ' SET last_login = ?, logins = logins + 1 WHERE id = ?', [$date, $this->data()['id']]);
                    $_SESSION['last_confirm'] = date('Y-m-d H:i:s');
                    $this->_db->query('INSERT logs(logdate, user_id, logtype, lognote, ip) VALUES(?,?,?,?,?)',  $date, $this->data()['id'], 'Login', 'User logged in.', ipCheck());
                    $ip = ipCheck();
                    $q = $this->_db->query('SELECT id FROM us_ip_list WHERE ip = ?', [$ip]);
                    $c = sizeof($q->fetchAll());
                    if ($c < 1) {
                        $this->_db->query('INSERT us_ip_list(user_id, ip) VALUES(?,?)', $this->data()['id'], $ip);
                    } else {
                        $f = ($q->fetchAll())[0];
                        $this->_db->query('UPDATE us_ip_list SET user_id=?, ip=? WHERE id=?', $this->data()['id'], $ip, $f['id']);
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
                    $this->_db->query('UPDATE ' . $this->tableName . ' SET last_login = ?, logins = logins + 1 WHERE id = ?', [$date, $this->data()['id']]);
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

}
