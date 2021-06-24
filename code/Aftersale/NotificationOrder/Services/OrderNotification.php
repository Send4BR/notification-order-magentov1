<?php

require __DIR__ . "/SendEmail.php";


class OrderNotification
{
    private $webhookServer;

    public function __construct($webhookServer)
    {
        $this->webhookServer = $webhookServer;
    }

    public function send($data)
    {
        $url = Mage::getStoreConfig('configs/webhook/url_webhook');

        if (empty($url)) {
            throw new Exception('Url Webhook not found');
        }

        $headers = $this->getHeader();
        $payload = $this->createPayload($data);

        $this->webhookServer->make($payload, $headers, $url);

        return true;
    }

    public function dispatchEmail($message)
    {
        $email = Mage::getStoreConfig('configs/webhook/email');

        if (!empty($email)) {
            $sendEmail = new SendEmail($message);
            $sendEmail->make();
        }
    }

    public function getHeader()
    {
        $ecommerceUIID = Mage::getStoreConfig('configs/webhook/ecommerce_uuid');

        $headers = ['Content-Type: application/json'];

        if (!empty($ecommerceUIID)) {
            array_push($headers, 'X-Connector-Client-Id: ' . $ecommerceUIID);
        }

        return $headers;
    }

    public function createPayload($content)
    {
        $scope = 'create';

        $orderId = $content['increment_id'];

        $editIncrementIsntEmpty = !empty($content['edit_increment']);

        if ($editIncrementIsntEmpty) {
            $orderId = $content['original_increment_id'];
            $scope = 'update';
        }

        $data = [
            'data' => [
                'order_id' => $orderId,
                'status' => $content['status'],
                'scope' => $scope,
                'updated_at' => $content['updated_at'],
            ],
            'success' => true
        ];

        return $data;
    }
}
