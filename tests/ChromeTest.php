<?php
namespace ChromeDevTools\Test;

use ChromeDevTools\Chrome;
use WebSocket\Client;

class ChromeTest extends \PHPUnit_Framework_TestCase
{
    public function testAutoConnectOnInstantiate()
    {
        $chrome = $this->getMockBuilder(Chrome::class)
            ->disableOriginalConstructor()
            ->getMock();

        $chrome->expects($this->once())
            ->method('connect')
            ->with($this->equalTo(0));

        // now call the constructor
        $reflectedClass = new \ReflectionClass(Chrome::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($chrome);
    }

    public function testNoAutoConnectOnInstantiate()
    {
        $chrome = $this->getMockBuilder(Chrome::class)
            ->disableOriginalConstructor()
            ->getMock();
        $chrome->expects($this->never())
            ->method('connect');

        // now call the constructor
        $reflectedClass = new \ReflectionClass(Chrome::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($chrome, 'localhost', 9222, 0, 1, false);
    }

    public function timeoutDataProvider()
    {
        return [
            [1],
            [4],
            [7]
        ];
    }

    /**
     * @dataProvider timeoutDataProvider
     * @param $timeout
     */
    public function testSetTimeoutToWsClientOnConnect($timeout)
    {
        $chrome = $this->getMockBuilder(Chrome::class)
            ->setMethods(['getWsClient', 'getTabs'])
            ->disableOriginalConstructor()
            ->getMock();


        $wsClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $wsClient->expects($this->once())
            ->method('setTimeout')
            ->with($this->equalTo($timeout));

        $chrome->method('getWsClient')
            ->will($this->returnValue($wsClient));

        // now call the constructor
        $reflectedClass = new \ReflectionClass(Chrome::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($chrome, 'localhost', 9222, 0, $timeout, false);
        $chrome->connect();
    }

    public function testGetsTabsOnConnect()
    {
        $chrome = $this->getMockBuilder(Chrome::class)
            ->setMethods(['getWsClient', 'getTabs'])
            ->disableOriginalConstructor()
            ->getMock();


        $wsClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $chrome->method('getWsClient')
            ->will($this->returnValue($wsClient));

        $chrome->expects($this->once())
            ->method('getTabs');

        // now call the constructor
        $reflectedClass = new \ReflectionClass(Chrome::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($chrome, 'localhost', 9222, 0, 1, false);
        $chrome->connect();
    }

    public function testCloseOpenedSocketOnConnect()
    {
        $chrome = $this->getMockBuilder(Chrome::class)
            ->setMethods(['getWsClient', 'getTabs'])
            ->disableOriginalConstructor()
            ->getMock();

        $wsClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $wsClient->expects($this->once())
            ->method('close');

        $chrome->method('getWsClient')
            ->will($this->returnValue($wsClient));

        // now call the constructor
        $reflectedClass = new \ReflectionClass(Chrome::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($chrome, 'localhost', 9222, 0, 1, false);
        $chrome->connect();
        $chrome->connect();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowsExceptionOnInvalidDomain()
    {
        $chrome = new Chrome('localhost', 9222, 0, 1, false);
        $chrome->unknown;
    }

    public function testReturnSelfOnAccessingValidDomain()
    {
        $chrome = new Chrome('localhost', 9222, 0, 1, false);
        $network = $chrome->Network;
        $this->assertSame($chrome, $network);
    }

    public function testSendMessageAndWaitForResultOnMethodCall()
    {
        $chrome = $this->getMockBuilder(Chrome::class)
            ->setMethods(['getWsClient', 'getTabs', 'waitResult'])
            ->disableOriginalConstructor()
            ->getMock();

        $wsClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $messageId = 0;
        $wsClient->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo(json_encode([
                    'method' => 'Page.navigate',
                    'id' => $messageId,
                    'params' => ['url' => 'localhost']
                ]))
            )
        ;

        $chrome->method('getWsClient')
            ->will($this->returnValue($wsClient));
        $chrome->expects($this->once())
            ->method('waitResult')
            ->with($this->equalTo($messageId))
        ;

        // now call the constructor
        $reflectedClass = new \ReflectionClass(Chrome::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($chrome);
        $chrome->Page->navigate(['url'=> 'localhost']);
    }

    public function testWaitMessage()
    {

    }
}
