<?php

namespace spec\Yamaki\Input;

use PHPSpec2\ObjectBehavior;

class Client extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Yamaki\Input\Client');
    }

    function it_should_be_singleton()
    {
        $instance = $this->generate();
        $instance -> shouldHaveType('Yamaki\Input\Client');
        $this->generate() -> shouldBe($instance);
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

    function it_should_detect_smart_phone() {
        $userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A403 Safari/8536.25';
        $_SERVER['HTTP_USER_AGENT']  = $userAgent;
        $this->isSmartPhone()->shouldReturn(true);
 
        $userAgent = 'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A403 Safari/8536.25';
        $_SERVER['HTTP_userAgent']  = $userAgent;
        $this->isSmartPhone()->shouldReturn(true);

        $userAgent = 'Mozilla/5.0 (iPod; U; CPU iPhone OS 3_0 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Version/4.0 Mobile/7A341 Safari/528.16';
        $_SERVER['HTTP_userAgent']  = $userAgent;
        $this->isSmartPhone()->shouldReturn(true);

        $userAgent = 'Mozilla/5.0 (Linux; U; Android 2.2; en-us; SC-01C Build/FROYO) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1';
        $_SERVER['HTTP_userAgent']  = $userAgent;
        $this->isSmartPhone()->shouldReturn(true);

        $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; KDDI-TS01; Windows Phone 6.5.3.5)';
        $_SERVER['HTTP_USER_AGENT']  = $userAgent;
        $this->isSmartPhone()->shouldReturn(false);
    }

}
