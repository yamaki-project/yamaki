<?php
class ClientTest extends \PHPUnit_Framework_TestCase
{
    private $obj = null;
    function setUp(){
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
        $this -> obj = \Yamaki\Input\Client::generate();
    }
    function testGenerate(){
        $this->assertEquals('Yamaki\Input\Client', get_class($this -> obj));
    }

    function testExists()
    {
        $this -> assertEquals($this -> obj -> accept(),array(
           'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'charset'  => 'Shift_JIS,utf-8;q=0.7,*;q=0.3',
            'encoding' => 'gzip,deflate,sdch',
            'language' => 'en'
        ));
        $this -> assertEquals($this -> obj -> connection()         ,'keep-alive');
        $this -> assertEquals($this -> obj -> referrer()           ,'http://www.yamaki.org/index.html');
        $this -> assertEquals($this -> obj -> userAgent()          ,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
        $this -> assertEquals($this -> obj -> secure()             ,false);
        $this -> assertEquals($this -> obj -> ip()                 ,'192.168.0.1');
        $this -> assertEquals($this -> obj -> host()               ,'yamaki.org');
        $this -> assertEquals($this -> obj -> port()               ,'123456');
        $this -> assertEquals($this -> obj -> user()               ,'freddiefujiwara');
        $this -> assertEquals($this -> obj -> redirectRemoteUser() ,'kashimoo');
        $this -> assertEquals($this -> obj -> authDigest()         ,'1234');
        $this -> assertEquals($this -> obj -> authUser()           ,'masuzawashun01');
        $this -> assertEquals($this -> obj -> authPassword()       ,'pass');
        $this -> assertEquals($this -> obj -> authType()           ,'Basic');
    }


    function testDetectJapaneseCarriers()
    {
        //docomo
        $_SERVER['HTTP_USER_AGENT'] = 'docomo/2.0 SH905i(c100;TB;W24H16)';
        $this -> assertTrue($this -> obj->isDocomo());
        //au
        $_SERVER['HTTP_USER_AGENT'] = 'KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0';
        $this -> assertTrue($this -> obj->isAU());
        //SoftBank
        $_SERVER['HTTP_USER_AGENT'] = 'SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1';
        $this -> assertTrue($this -> obj->isSoftBank());
        //nonJapanese
        $userAgent = 'Mozilla/3.0(DDIPOCKET;JRC/AH-J3001V,AH-J3002V/1.0/0100/c50)CNF/2.0';
        $_SERVER['HTTP_USER_AGENT'] = $userAgent;
        $this -> assertTrue($this -> obj->isNotJapanese());
    }

    function testGetEachCarrier()
    {
        //docomo
        $uid = '1234567890AB';
        $_GET['uid'] = $uid;
        $this -> assertEquals($this -> obj->docomoUserId() ,$uid);

        unset($_SERVER['HTTP_X_DCMGUID']);
        unset($_GET['uid']);
        $this -> assertEquals($this -> obj->docomoUserId() ,"");

        $_SERVER['HTTP_X_UP_SUBNO'] = $uid;
        $this -> assertEquals($this -> obj->auUserId() ,$uid);

        unset($_SERVER['HTTP_X_UP_SUBNO']);
        $this -> assertEquals($this -> obj->auUserId() ,"");

        //softbank
        $_SERVER['HTTP_X_JPHONE_UID'] = $uid;
        $this -> assertEquals($this -> obj->softBankUserId() ,$uid);

        unset($_SERVER['HTTP_X_JPHONE_UID']);
        $this -> assertEquals($this -> obj->softBankUserId() ,'');
    }

    function testGetUserId()
    {
        $beforeGETUid    = isset($_GET['uid']) ? $_GET['uid'] : "";
        $beforeUserAgent = $_SERVER['HTTP_USER_AGENT'];
        //docomo
        $uid = '1234567890AB';
        $_GET['uid'] = $uid;
        $_SERVER['HTTP_USER_AGENT'] = 'docomo/2.0 SH905i(c100;TB;W24H16)';
        $this -> assertEquals($this -> obj->userId() ,$uid);

        unset($_SERVER['HTTP_X_DCMGUID']);
        unset($_GET['uid']);
        $_SERVER['HTTP_USER_AGENT'] = 'docomo/2.0 SH905i(c100;TB;W24H16)';
        $this -> assertEquals($this -> obj->userId() ,"");

        //au
        $_SERVER['HTTP_X_UP_SUBNO'] = $uid;
        $_SERVER['HTTP_USER_AGENT'] = 'KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0';
        $this -> assertEquals($this -> obj->userId() ,$uid);

        unset($_SERVER['HTTP_X_UP_SUBNO']);
        $_SERVER['HTTP_USER_AGENT'] = 'KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0';
        $this -> assertEquals($this -> obj->userId() ,"");

        //softbank
        $_SERVER['HTTP_X_JPHONE_UID'] = $uid;
        $_SERVER['HTTP_USER_AGENT'] = 'SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1';
        $this -> assertEquals($this -> obj->userId() ,$uid);

        unset($_SERVER['HTTP_X_JPHONE_UID']);
        $_SERVER['HTTP_USER_AGENT'] = 'SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1';
        $this -> assertEquals($this -> obj->userId() ,"");

        // other
        $userAgent = 'Mozilla/3.0(DDIPOCKET;JRC/AH-J3001V,AH-J3002V/1.0/0100/c50)CNF/2.0';
        $_SERVER['HTTP_USER_AGENT'] = $userAgent;
        $this -> assertTrue($this -> obj->isNotJapanese());

        $doCoMoUid = '1234567890AB';
        $auUid = 'au';
        $softBankUid = 'sb';
        $_GET['uid'] = $doCoMoUid;
        $_SERVER['HTTP_X_UP_SUBNO'] = $auUid;
        $_SERVER['HTTP_X_JPHONE_UID'] = $softBankUid;
        $this -> assertEquals($this -> obj->userId() ,$doCoMoUid);
        unset($_GET['uid']);
        $this -> assertEquals($this -> obj->userId() ,$auUid);
        unset($_SERVER['HTTP_X_UP_SUBNO']);
        $this -> assertEquals($this -> obj->userId() ,$softBankUid);

        $_GET['uid'] = $beforeGETUid;
        $_SERVER['HTTP_USER_AGENT'] = $beforeUserAgent;
    }

    function testGetFromCookies()
    {
        $_COOKIE['hoge'] = 'fuga';
        $this -> assertEquals($this -> obj -> cookie('hoge') ,'fuga');
        $this -> assertEquals($this -> obj -> cookie('hage') ,'');
    }

    function testDetectSmartPhone() {
        $userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A403 Safari/8536.25';
        $_SERVER['HTTP_USER_AGENT']  = $userAgent;
        $this -> assertTrue($this -> obj->isSmartPhone());
 
        $userAgent = 'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A403 Safari/8536.25';
        $_SERVER['HTTP_userAgent']  = $userAgent;
        $this -> assertTrue($this -> obj->isSmartPhone());

        $userAgent = 'Mozilla/5.0 (iPod; U; CPU iPhone OS 3_0 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Version/4.0 Mobile/7A341 Safari/528.16';
        $_SERVER['HTTP_userAgent']  = $userAgent;
        $this -> assertTrue($this -> obj->isSmartPhone());

        $userAgent = 'Mozilla/5.0 (Linux; U; Android 2.2; en-us; SC-01C Build/FROYO) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1';
        $_SERVER['HTTP_userAgent']  = $userAgent;
        $this -> assertTrue($this -> obj->isSmartPhone());

        $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; KDDI-TS01; Windows Phone 6.5.3.5)';
        $_SERVER['HTTP_USER_AGENT']  = $userAgent;
        $this -> assertFalse($this -> obj->isSmartPhone());
    }
    function testFailWithBlankReferer() {
        unset($_SERVER['HTTP_REFERER']);
        $this -> assertEquals($this -> obj->referrer(),'');
    }

}
