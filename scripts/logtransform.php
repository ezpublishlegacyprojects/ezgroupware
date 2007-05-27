<?php


$document = new DOMDocument();
$document->load('../ezg_testlog/testlog.xml');

$xsl = new DOMDocument();
$xsl->load('UnitTest/xslt/phpunit2-noframes.xsl');

$proc = new XSLTProcessor();
$proc->importStyleSheet($xsl);

file_put_contents( '../ezg_testlog/testlog.html',$proc->transformToXML($document));

