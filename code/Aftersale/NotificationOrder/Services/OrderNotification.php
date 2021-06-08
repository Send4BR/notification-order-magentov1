<?php

require __DIR__ . "/WebhookServer.php";
require __DIR__ . "/SendEmail.php";


class OrderNotification
{
    public function send($data)
    {
        try {

            $url = Mage::getStoreConfig('configs/webhook/url_webhook');

            if (empty($url)) {
                throw new Exception('Url Webhook not found');
            }

            $headers = $this->getHeader();
            $payload = $this->createPayload($data);
            $webhookServer = new WebhookServer($payload, $headers, $url);
            $webhookServer->make();
        } catch (\Exception $exception) {
            Mage::log($exception->getMessage(), null, 'webhookError.log');
            $this->dispatchEmail($exception->getMessage());
        }
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
