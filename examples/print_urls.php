<?php
require 'vendor/autoload.php';

use ChromeDevTools\Chrome;

$chrome = new Chrome();

$chrome->Page->enable();
$chrome->Network->enable();

$chrome->Page->navigate(['url' => 'http://www.facebook.com']);
$result = $chrome->waitEvent('Page.frameStoppedLoading', 10);

foreach ($result['messages'] as $i => $message) {
    if (isset($message['method']) && $message['method'] === 'Network.responseReceived') {
        $url = $message['params']['response']['url'];
        echo $url, PHP_EOL;
    }
}
