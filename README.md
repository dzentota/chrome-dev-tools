# chrome-dev-tools

# 1. Description
This is a PHP lib that allows one to interact with Google Chrome using [Chrome DevTools Protocol](https://chromedevtools.github.io/devtools-protocol/) within a PHP script.
To use this tool, you must run an instance of Google Chrome with the `remote-debugging` option, like in the following example.
```
google-chrome --remote-debugging-port=9222
```
You may want to enable further Chrome benchmarking capabilities using the `--enable-benchmarking` and `--enable-net-benchmarking` options. You can run Chrome in headless mode using the option `--headless`.

# 2. Prerequisites
An updated Google-Chrome version 

# 3. Operation
## 3.1 Init
In your php script, as first, you must create a Chrome object, like in the following:
```php
$chrome = new ChromeDevTools\Chrome()
```
You can specify the host and the port of Chrome manually writing:
```php
$chrome = ChromeDevTools\Chrome($host="1.1.1.1", $port=1234);
```
By default it uses `localhost:9222`.

## 3.1 Run commands
To send a command to Chrome, just invoke the corresponding method on the Chrome object, and pass the desired parameters.
For example, to visit a page write:
```php
$chrome->Page->navigate(['url' => 'http://example.com/');
```
The return value of the command is passed as return value of the function, already interpreted as JSON.

## 3.1 Receive Events
Chrome sends back messages for particular events in the browser.
You can get them in two ways; they are returned already interpreted as JSON.
All unread events are erased before any new command is run.

a) You can pop one message from the queue of received ones writing:
```php
$message = $chrome->waitMessage()
```
The method accepts an optional parameter `timeout` which is the value in seconds after which it gives up and returns `null`.
Default is 1.

b) You can wait for a specific event writing:
```php
$result = $chrome->waitEvent('event_name');
$matchingEvent = $result['matching_message'];
$messages = $result['messages'];
```
It waits until an event with the name `event_name` arrives, or a timeout elapses.
`$matchingEvent` contains the first found event that has `event_name`, while `$messages` contains all messages arrived before.
Timeout value can be configured as in the previous method.

# 4. Examples
## @See `examples` folder
