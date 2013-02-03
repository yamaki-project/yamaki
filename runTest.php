#!/usr/bin/env php
<?php
//
//coverage -text maker start
//
$coverageOutput = tempnam(".","coverage");
unlink($coverageOutput);
$coverageOutput .= ".txt";
ob_start();
system("./test/bin/phpunit --verbose --coverage-text=$coverageOutput test/Yamaki",$returnCode);
$output = ob_get_clean();
if(0 !== $returnCode){
    unlink($coverageOutput);
    die($output);
}
if(!is_file($coverageOutput)){
    die(__FILE__.":".__LINE__);
}
//
//coverage -text maker end
//

$summary = array();
//
//coverage-text parser start
//
$inSummary = false;
$fd = fopen($coverageOutput,'r');
while(!feof($fd)){
    $line = fgets($fd);
    if(preg_match("/^Yamaki/",$line)){
        $inSummary = false;
    }

    if($inSummary && preg_match("/[0-9a-zA-Z]/",$line)){
        $keyValue = array();
        foreach(explode(" ",$line) as $value){
            if(preg_match("/[0-9a-zA-Z]/",$value)){
                $keyValue[] = $value;
            }
        }
        $summary[$keyValue[0]] = $keyValue[1];
    }
    if(preg_match("/Summary:/",$line)){
        $inSummary = true;
    }
}
fclose($fd);
unlink($coverageOutput);
//
//coverage-text parser start
//

foreach($summary as $key => $percent){
    if("100.00%" !== $percent){
        print_r($summary);
        die($key." is not 100.00%!!");
    }
}
exit;
