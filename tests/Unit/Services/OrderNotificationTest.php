<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . "/../../../code/Aftersale/NotificationOrder/Services/OrderNotification.php";

class OrderNotificationTest extends JSiefer\MageMock\PHPUnit\TestCase
{
    private $orderNotification;

    protected function setUp()
    {
        parent::setUp();

        $webhookServer = $this->getMockBuilder(WebhookServer::class)
            ->setMethods(['make'])
            ->disableOriginalConstructor()
            ->getMock();

        $webhookServer->expects($this->any())
            ->method('make')
            ->will($this->returnValue('test'));

        $this->orderNotification = new OrderNotification($webhookServer);
    }

    /**
     * @test
     */
    public function ShouldReturnPayloadCorrectWhenScopeIsCreate()
    {
        $data = [
            'increment_id' => '12231',
            'status' => 'pending',
            'updated_at' => '2021-06-02 18:56:10',
        ];

        $result = $this->orderNotification->createPayload($data);

        $this->assertEquals($result['data']['order_id'], $data['increment_id']);
        $this->assertEquals($result['data']['status'], $data['status']);
        $this->assertEquals($result['data']['updated_at'], $data['updated_at']);
        $this->assertEquals($result['data']['scope'], 'create');
    }

    /**
     * @test
     */
    public function ShouldReturnPayloadCorrectWhenScopeIsUpdate()
    {
        $data = [
            'edit_increment' => '1',
            'increment_id' => '1',
            'original_increment_id' => '12231',
            'status' => 'pending',
            'updated_at' => '2021-06-02 18:56:10',
        ];

        $result = $this->orderNotification->createPayload($data);

        $this->assertEquals($result['data']['order_id'], $data['original_increment_id']);
        $this->assertEquals($result['data']['status'], $data['status']);
        $this->assertEquals($result['data']['updated_at'], $data['updated_at']);
        $this->assertEquals($result['data']['scope'], 'update');
    }

    /**
     * @test
     */
    public function shouldGetHeaderWithoutEcommerceUiid()
    {

        $mage = $this->getMage();
        $mage->expects($this->once())->method('getStoreConfig')->willReturn(null);

        $result = $this->orderNotification->getHeader();

        $this->assertEquals('Content-Type: application/json', $result[0]);
        $this->assertEquals(1, count($result));
    }

    /**
     * @test
     */
    public function shouldGetHeaderWithEcommerceUiid()
    {

        $ecommerceUiid = '12324545';

        $mage = $this->getMage();
        $mage->expects($this->once())
            ->method('getStoreConfig')
            ->willReturn($ecommerceUiid);

        $result = $this->orderNotification->getHeader();

        $this->assertEquals('Content-Type: application/json', $result[0]);
        $this->assertEquals('X-Connector-Client-Id: 12324545', $result[1]);
        $this->assertEquals(2, count($result));
    }

    /**
     * @test
     */
    public function shouldGenerateErroWhenWebhookUrlNotFound()
    {
        $data = [
            'increment_id' => '12231',
            'status' => 'pending',
            'updated_at' => '2021-06-02 18:56:10',
        ];

        $this->setExpectedException(Exception::class);

        $this->orderNotification->send($data);
    }

    /**
     * @test
     */
    public function shouldSendWebhookAndReturnTrue()
    {

        $data = [
            'increment_id' => '12231',
            'status' => 'pending',
            'updated_at' => '2021-06-02 18:56:10',
        ];

        $url = 'localhost';

        $mage = $this->getMage();
        $mage->method('getStoreConfig')
            ->willReturn($url);

        $result = $this->orderNotification->send($data);

        $this->assertTrue($result);
    }
}
