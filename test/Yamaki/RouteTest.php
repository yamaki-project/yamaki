<?php
class RouteTest extends \PHPUnit_Framework_TestCase
{
    private $obj = null;
    function setUp(){
        $this -> obj = \Yamaki\Route::generate();
    }
    function testGenerate(){
        $this->assertEquals('Yamaki\Route', get_class($this -> obj));
    }

    function testARule()
    {
        $rule = "/yamaki/:ichiban/:niban";
        $this -> assertEquals($rule,$this -> obj -> rule($rule)
              -> rule());
        try{
            $this -> obj -> callback('test');
        }catch(\InvalidArgumentException $e){
            $this -> assertEquals("callback must be callable",$e -> getMessage());
        }
        try{
            $this -> obj -> rule(function(){});
        }catch(\InvalidArgumentException $e){
            $this -> assertEquals("rule must be string",$e -> getMessage());
        }
    }
    function testSetAVia()
    {
        $via = "GET";
        $this -> assertEquals($this -> obj -> via(),
            array('GET','POST'));

        $this -> assertEquals($this -> obj -> via($via) 
            -> via(),
            array($via));

        $this -> assertEquals($this -> obj -> viaGet() 
            -> via(),
            array('GET'));

        $this -> assertEquals($this -> obj -> viaPost() 
            -> via(),
            array('POST'));

        try{
            $this -> obj -> via('test');
        }catch(\InvalidArgumentException $e){
            $this -> assertEquals("via must be GET or POST",$e -> getMessage());
        }
    }

    function testCallback()
    {
        $callback = function () {};
        $this -> assertEquals($callback,$this -> obj -> callback($callback) -> callback());
        try{
            $this -> obj -> callback('test');
        }catch(\InvalidArgumentException $e){
            $this -> assertEquals("callback must be callable",$e -> getMessage());
        }
    }
    function testNoSubmatches()
    {
        $noSubmatches = array('foo','bar');
        $this -> assertEquals($noSubmatches,$this -> obj -> noSubmatches($noSubmatches)->noSubmatches());
        try{
            $this -> obj -> noSubmatches('test');
        }catch(\InvalidArgumentException $e){
            $this -> assertEquals("noSubmatches must be array",$e -> getMessage());
        }
    }
    function testNodecode()
    {
        $noDecodes = array('foo','bar');
        $this -> assertEquals($noDecodes,$this -> obj -> noDecodes($noDecodes)
              -> noDecodes());
        try{
            $this -> obj -> noDecodes('test');
        }catch(\InvalidArgumentException $e){
            $this -> assertEquals("noDecodes must be array",$e -> getMessage());
        }
    }
    function testBeMadeRegexForMatches()
    {
        $rule = "/yamaki/:ichiban/:niban";
        $this -> assertEquals('/yamaki/(?P<ichiban>[^/]+)/(?P<niban>[^/]+)/?',$this -> obj -> rule($rule)
              -> matchesRegex());
    }
    function testBeMadeRegexForSplittedRules()
    {
        $matches = array(
            ':ichiban',
            'ichiban'
        );
        $this -> assertEquals('(?P<ichiban>[^/]+)',$this -> obj -> matchesRegexMaker($matches));
    }
    function testGetAsParams()
    {
        $rule = "/yamaki/:ichiban/:niban";
        $this -> assertTrue($this -> obj -> rule($rule)
              -> matches('/yamaki/1/2'));
        $this -> assertEquals('1',$this -> obj -> param('ichiban'));
        $this -> assertEquals('2',$this -> obj -> param('niban'));

        $this -> assertTrue($this -> obj -> rule($rule)
              -> matches('/yamaki/aaa/bbb'));
        $this -> assertEquals('aaa',$this -> obj -> param('ichiban'));
        $this -> assertEquals('bbb',$this -> obj -> param('niban'));

        $this -> assertTrue($this -> obj -> rule($rule)
              -> matches('/yamaki/aaa/bbb/'));
        $this -> assertEquals('aaa',$this -> obj -> param('ichiban'));
        $this -> assertEquals('bbb',$this -> obj -> param('niban'));
        $this -> assertNull($this -> obj -> param('ccc'));

        $this -> assertTrue($this -> obj -> rule($rule)
              -> matches('/yamaki/%E3%82%84%E3%81%BE%E3%81%8D/%E6%97%85%E9%A4%A8'));
        $this -> assertEquals('やまき',$this -> obj -> param('ichiban'));
        $this -> assertEquals('旅館',$this -> obj -> param('niban'));


        $this -> assertTrue($this -> obj -> rule($rule)
              -> matches('/yamaki/aaa.m=mval.c=cval/t=tval.u=uval.bbb'));
        $this -> assertEquals(array(
            'aaa',
            'm' => 'mval',
            'c' => 'cval'),
            $this -> obj -> param('ichiban'));
        $this -> assertEquals(array(
            't' => 'tval',
            'u' => 'uval',
            'bbb'),
            $this -> obj -> param('niban'));

        $this -> assertTrue($this -> obj -> rule($rule) 
              -> noSubmatches(array('niban'))
              -> matches('/yamaki/aaa.m=mval.c=cval/t=tval.u=uval.bbb'));
        $this -> assertEquals(array(
            'aaa',
            'm' => 'mval',
            'c' => 'cval'),
            $this -> obj -> param('ichiban'));
        $this -> assertEquals('t=tval.u=uval.bbb',
            $this -> obj -> param('niban'));
    }
    function testGetAsSubParams()
    {
        $this -> assertEquals($this -> obj -> subMatches('aaa.b=bval.c=cval'),array(
            'aaa',
            'b' => 'bval',
            'c' => 'cval'
        ));

        $this -> assertEquals($this -> obj -> subMatches('aaa.b=bval.c='),array(
            'aaa',
            'b' => 'bval',
            'c' => ''
        ));

        $this -> assertEquals($this -> obj -> subMatches('aaa.b=bval.=cval'),array(
            'aaa',
            'b' => 'bval'
        ));

        $this -> assertEquals($this -> obj -> subMatches('aaa.b=bval...c=cval'),array(
            'aaa',
            'b' => 'bval',
            'c' => 'cval'
        ));

        $this -> assertEquals($this -> obj -> subMatches('aaa.b=bval.=.c=cval'),array(
            'aaa',
            'b' => 'bval',
            'c' => 'cval'
        ));

        $this -> assertEquals($this -> obj -> subMatches('.aaa.'),array(
            'aaa'
        ));

        $this -> assertEquals($this -> obj -> subMatches('aaa'),
            'aaa'
        );
    }
    function testRun()
    {
        $rule = "/yamaki/:ichiban/:niban";
        $this -> assertTrue($this -> obj -> rule($rule)
              -> callback(function($route){
                     throw new Exception(
                     $route -> param('ichiban').','.
                     $route -> param('niban'));
                })
              -> matches('/yamaki/1/2'));
        try {
            $this -> obj -> run(null);
        } catch (\Exception $e){
            $this -> assertEquals('1,2',$e -> getMessage());
        }
    }
    function testNotDecode()
    {
        $rule = "/yamaki/:ichiban/:niban";
        $this -> assertTrue($this -> obj -> rule($rule)
              -> noDecodes(array('niban'))
              -> matches('/yamaki/%E3%82%84%E3%81%BE%E3%81%8D/%E6%97%85%E9%A4%A8'));
        $this -> assertEquals($this -> obj -> param('ichiban') ,'やまき');
        $this -> assertEquals($this -> obj -> param('niban')   ,'%E6%97%85%E9%A4%A8');
    }
}
