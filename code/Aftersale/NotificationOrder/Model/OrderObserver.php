<?php

require __DIR__ . "/../Services/OrderNotification.php";
require __DIR__ . "/../Services/WebhookServer.php";

class Aftersale_NotificationOrder_Model_OrderObserver
{

    private $orderNotificationService;

    public function __construct()
    {
        $webhook = new WebhookServer();
        $this->orderNotificationService = new OrderNotification($webhook);
    }

    public function orderCreateOrUpdate($observer)
    {
        try {

            $content = $observer->getOrder()->getData();

            $this->orderNotificationService->send($content);
        } catch (\Exception $exception) {
            Mage::log($exception->getMessage(), null, 'webhookError.log');
            $this->orderNotificationService->dispatchEmail($exception->getMessage());
        }
    }
}
