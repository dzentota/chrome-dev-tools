<?php
require 'vendor/autoload.php';

use ChromeDevTools\Chrome;

$chrome = new Chrome();

$chrome->Page->enable();
$chrome->Network->enable();

$startTime = microtime(true);
$chrome->Page->navigate(['url' => 'http://www.google.com/']);
$chrome->waitEvent("Page.loadEventFired", 10);
$endTime=microtime(true);

echo "Page Loading Time:" . ($endTime - $startTime), PHP_EOL;
