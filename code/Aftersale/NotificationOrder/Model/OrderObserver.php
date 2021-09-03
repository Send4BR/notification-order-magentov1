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
            $order = $observer->getOrder()->getData();

            $this->orderNotificationService->send($order);
        } catch (\Exception $exception) {
            Mage::log($exception->getMessage(), null, 'webhookError.log');
            $this->orderNotificationService->dispatchEmail($exception->getMessage());
        }
    }

    public function orderShipmentCreateOrUpdate($observer)
    {
        try {
            $order = $observer->getEvent()->getShipment()->getOrder()->getData();

            $this->orderNotificationService->send($order);
        } catch (\Exception $exception) {
            Mage::log($exception->getMessage(), null, 'webhookError.log');
            $this->orderNotificationService->dispatchEmail($exception->getMessage());
        }
    }
}
