<?php
require 'vendor/autoload.php';

use ChromeDevTools\Chrome;

$chrome = new Chrome();

$chrome->Page->enable();
$chrome->Network->enable();

$chrome->Page->navigate(['url' => 'http://www.nytimes.com/']);
$chrome->waitEvent("Page.frameStoppedLoading", 10);
#Wait last objects to load
sleep(5);

$cookies = $chrome->Network->getCookies();
foreach ($cookies['result']['cookies'] as $cookie) {
    echo ("Cookie:");
    echo ("\tDomain:". $cookie["domain"]);
    echo ("\tKey:". $cookie["name"]);
    echo ("\tValue:". $cookie["value"]);
    echo ("\n");
}
