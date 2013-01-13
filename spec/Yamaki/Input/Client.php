<?php

namespace spec\Yamaki\Input;

use PHPSpec2\ObjectBehavior;

class Client extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Yamaki\Input\Client');
    }

    function let()
    {
        $_SERVER = array(
            'HTTP_ACCEPT'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_ACCEPT_CHARSET'  => 'Shift_JIS,utf-8;q=0.7,*;q=0.3',
            'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
            'HTTP_ACCEPT_LANGUAGE' => 'en',
            'HTTP_CONNECTION'      => 'keep-alive',                                                                                                   
            'HTTP_REFERER'         => 'http://www.yamaki.org/index.html',                                                                             
            'HTTP_USER_AGENT'      => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17', 
            'HTTPS'                => 'off',
            'REMOTE_ADDR'          => '192.168.0.1',
            'REMOTE_HOST'          => 'yamaki.org',
            'REMOTE_PORT'          => '123456',
            'REMOTE_USER'          => 'freddiefujiwara',
            'REDIRECT_REMOTE_USER' => 'kashimoo',
            'PHP_AUTH_DIGEST'      => '1234',
            'PHP_AUTH_USER'        => 'masuzawashun01',
            'PHP_AUTH_PW'          => 'pass',
            'AUTH_TYPE'            => 'Basic'
        );
    }

    function it_should_exists()
    {
        $this -> accept()    -> shouldBe(array(
            'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'charset'  => 'Shift_JIS,utf-8;q=0.7,*;q=0.3',
            'encoding' => 'gzip,deflate,sdch',
            'language' => 'en'
        ));
        $this -> connection()         -> shouldBe('keep-alive');
        $this -> referrer()           -> shouldBe('http://www.yamaki.org/index.html');
        $this -> userAgent()          -> shouldBe('Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
        $this -> secure()             -> shouldBe(false);
        $this -> ip()                 -> shouldBe('192.168.0.1');
        $this -> host()               -> shouldBe('yamaki.org');
        $this -> port()               -> shouldBe('123456');
        $this -> user()               -> shouldBe('freddiefujiwara');
        $this -> redirectRemoteUser() -> shouldBe('kashimoo');
        $this -> authDigest()         -> shouldBe('1234');
        $this -> authUser()           -> shouldBe('masuzawashun01');
        $this -> authPassword()       -> shouldBe('pass');
        $this -> authType()           -> shouldBe('Basic');
    }


    function it_should_detect_japanese_carriers()
    {
        //docomo
        $_SERVER['HTTP_USER_AGENT'] = 'docomo/2.0 SH905i(c100;TB;W24H16)';
        $this->isDocomo()->shouldReturn(true);
        //au
        $_SERVER['HTTP_USER_AGENT'] = 'KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0';
        $this->isAU()->shouldReturn(true);
        //SoftBank
        $_SERVER['HTTP_USER_AGENT'] = 'SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1';
        $this->isSoftBank()->shouldReturn(true);
        //nonJapanese
        $userAgent = 'Mozilla/3.0(DDIPOCKET;JRC/AH-J3001V,AH-J3002V/1.0/0100/c50)CNF/2.0';
        $_SERVER['HTTP_USER_AGENT'] = $userAgent;
        $this->isNotJapanese()->shouldReturn(true);
    }

    function it_should_get_each_carrier()
    {
        //docomo
        $uid = '1234567890AB';
        $_GET['uid'] = $uid;
        $this->docomoUserId() -> shouldReturn($uid);

        unset($_SERVER['HTTP_X_DCMGUID']);
        unset($_GET['uid']);
        $this->docomoUserId() -> shouldReturn("");

        $_SERVER['HTTP_X_UP_SUBNO'] = $uid;
        $this->auUserId() -> shouldReturn($uid);

        unset($_SERVER['HTTP_X_UP_SUBNO']);
        $this->auUserId() -> shouldReturn("");

        //softbank
        $_SERVER['HTTP_X_JPHONE_UID'] = $uid;
        $this->softBankUserId() -> shouldReturn($uid);

        unset($_SERVER['HTTP_X_JPHONE_UID']);
        $this->softBankUserId() -> shouldReturn('');
    }

    function it_should_get_user_id()
    {
        $beforeGETUid    = isset($_GET['uid']) ? $_GET['uid'] : "";
        $beforeUserAgent = $_SERVER['HTTP_USER_AGENT'];
        //docomo
        $uid = '1234567890AB';
        $_GET['uid'] = $uid;
        $_SERVER['HTTP_USER_AGENT'] = 'docomo/2.0 SH905i(c100;TB;W24H16)';
        $this->userId() -> shouldReturn($uid);

        unset($_SERVER['HTTP_X_DCMGUID']);
        unset($_GET['uid']);
        $_SERVER['HTTP_USER_AGENT'] = 'docomo/2.0 SH905i(c100;TB;W24H16)';
        $this->userId() -> shouldReturn("");

        //au
        $_SERVER['HTTP_X_UP_SUBNO'] = $uid;
        $_SERVER['HTTP_USER_AGENT'] = 'KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0';
        $this->userId() -> shouldReturn($uid);

        unset($_SERVER['HTTP_X_UP_SUBNO']);
        $_SERVER['HTTP_USER_AGENT'] = 'KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0';
        $this->userId() -> shouldReturn("");

        //softbank
        $_SERVER['HTTP_X_JPHONE_UID'] = $uid;
        $_SERVER['HTTP_USER_AGENT'] = 'SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1';
        $this->userId() -> shouldReturn($uid);

        unset($_SERVER['HTTP_X_JPHONE_UID']);
        $_SERVER['HTTP_USER_AGENT'] = 'SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1';
        $this->userId() -> shouldReturn("");

        // other
        $userAgent = 'Mozilla/3.0(DDIPOCKET;JRC/AH-J3001V,AH-J3002V/1.0/0100/c50)CNF/2.0';
        $_SERVER['HTTP_USER_AGENT'] = $userAgent;
        $this->isNotJapanese()->shouldReturn(true);

        $doCoMoUid = '1234567890AB';
        $auUid = 'au';
        $softBankUid = 'sb';
        $_GET['uid'] = $doCoMoUid;
        $_SERVER['HTTP_X_UP_SUBNO'] = $auUid;
        $_SERVER['HTTP_X_JPHONE_UID'] = $softBankUid;
        $this->userId() -> shouldReturn($doCoMoUid);
        unset($_GET['uid']);
        $this->userId() -> shouldReturn($auUid);
        unset($_SERVER['HTTP_X_UP_SUBNO']);
        $this->userId() -> shouldReturn($softBankUid);

        $_GET['uid'] = $beforeGETUid;
        $_SERVER['HTTP_USER_AGENT'] = $beforeUserAgent;
    }

    function it_should_get_from_cookies()
    {
        $_COOKIE['hoge'] = 'fuga';
        $this -> cookie('hoge') -> shouldBe('fuga');
        $this -> cookie('hage') -> shouldBe('');
    }

}
