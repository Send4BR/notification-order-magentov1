<?php

require __DIR__ . "/../Services/OrderNotification.php";
require __DIR__ . "/../Services/WebhookServer.php";

class Aftersale_NotificationOrder_Model_OrderObserver
{
    private $orderNotificationService;
    private $order;

    public function __construct()
    {
        $webhook = new WebhookServer();
        $this->orderNotificationService = new OrderNotification($webhook);
    }

    public function orderCreateOrUpdate($observer)
    {
        try {
            $this->order = $observer->getOrder()->getData();
            $this->sendOrder($this->order);
        } catch (\Exception $exception) {
            $this->registerError($exception);
        }
    }

    public function orderShipmentCreateOrUpdate($observer)
    {
        try {
            $this->order = $observer->getEvent()->getShipment()->getOrder()->getData();
            $this->sendOrder();
        } catch (\Exception $exception) {
            $this->registerError($exception);
        }
    }

    private function sendOrder()
    {
        $this->orderNotificationService->send($this->order);
    }

    private function registerError($exception)
    {
        Mage::log('------Error------', null, 'webhookError.log');
        Mage::log('OrderId: ' . $this->order['increment_id'], null, 'webhookError.log');
        Mage::log($exception->getMessage(), null, 'webhookError.log');
        Mage::log($exception->getTraceAsString(), null, 'webhookError.log');
    }
}
