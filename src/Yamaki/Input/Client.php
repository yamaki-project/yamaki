<?php

namespace Yamaki\Input;

class Client
{
    private static $instance;

    public static function generate()
    {
        return isset(self::$instance) ? 
            self::$instance : 
            self::$instance = new self();
    }

    public function accept()
    {
        return array(
            $_SERVER['HTTP_ACCEPT'],
            'charset' => $_SERVER['HTTP_ACCEPT_CHARSET'],
            'encoding' => $_SERVER['HTTP_ACCEPT_ENCODING'],
            'language' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
        );
    }

    public function connection()
    {
            return $_SERVER['HTTP_CONNECTION'];
    }

    public function referrer()
    {
            return $_SERVER['HTTP_REFERER'];
    }

    public function userAgent()
    {
            return $_SERVER['HTTP_USER_AGENT'];
    }

    public function secure()
    {
            return empty($_SERVER['HTTPS']);
    }

    public function ip()
    {
            return $_SERVER['REMOTE_ADDR'];
    }

    public function host()
    {
            return $_SERVER['REMOTE_HOST'];
    }

    public function port()
    {
            return $_SERVER['REMOTE_PORT'];
    }

    public function user()
    {
            return $_SERVER['REMOTE_USER'];
    }

    public function redirectRemoteUser()
    {
            return $_SERVER['REDIRECT_REMOTE_USER'];
    }

    public function authDigest()
    {
            return $_SERVER['PHP_AUTH_DIGEST'];
    }

    public function authUser()
    {
            return $_SERVER['PHP_AUTH_USER'];
    }

    public function authPassword()
    {
            return $_SERVER['PHP_AUTH_PW'];
    }

    public function authType()
    {
            return $_SERVER['AUTH_TYPE'];
    }

    public function userId()
    {
        //3 major carriers
        if ($this->isDocomo()) {
            return $this->docomoUserId();
        }elseif ($this->isAU()) {
            return $this->auUserId();
        }elseif ($this->isSoftBank()) {
            return $this->softBankUserId();
        }   

        //other
        if( "" !== ($uid = $this->docomoUserId()) || 
            "" !== ($uid = $this->auUserId()) || 
            "" !== ($uid = $this->softBankUserId())){
            return $uid;
        }
        return ""; 
    }

    public function isDocomo()
    {
        return 1 === preg_match('/docomo/i', $this-> userAgent());
    }

    public function isAU()
    {
        return 1 === preg_match('/up\.browser/i', $this->userAgent());
    }

    public function isSoftBank()
    {
        return 1 === preg_match('/(?:j-phone|vodafone|mot-|softbank)/i', $this->userAgent());
    }

    public function isNotJapanese()
    {
        return !$this->isDocomo() && !$this->isAU() && !$this->isSoftBank();
    }

    public function docomoUserId()
    {
        return isset($_GET['uid']) && !preg_match('/^NULLGWDOCOMO$/i', $_GET['uid']) && preg_match('/^[0-9a-zA-Z]{12}$/', $_GET['uid']) ?
        $_GET['uid'] : "";
    }

    public function auUserId()
    {
        return isset($_SERVER['HTTP_X_UP_SUBNO']) ? $_SERVER['HTTP_X_UP_SUBNO'] : "";
    }

    public function softBankUserId()
    {
        return isset($_SERVER['HTTP_X_JPHONE_UID']) ? $_SERVER['HTTP_X_JPHONE_UID'] : "";
    }

    public function cookie($key)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : "";
    }

    public function isSmartPhone()
    {
        if ( preg_match('/(iPad|iPod|iPhone|Android)/', $this->userAgent()) ) {
            return true;
        }
        return false;
    }
}
