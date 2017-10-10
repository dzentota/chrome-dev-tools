<?php
require 'vendor/autoload.php';

use ChromeDevTools\Chrome;

$chrome = new Chrome();

$chrome->Page->enable();
$chrome->Network->enable();
$chrome->DOM->enable();
$chrome->Page->navigate(['url' => 'https://github.com']);
$events = $chrome->waitEvent("Page.loadEventFired", 5);
$chrome->DOM->getDocument();
$result = $chrome->DOM->getOuterHTML(['nodeId' => 1]);
$html = $result['result']['outerHTML'];
echo $html;