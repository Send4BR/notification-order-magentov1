<?php

require __DIR__ . "/../Services/OrderNotification.php";

class Aftersale_NotificationOrder_Model_OrderObserver
{
    public function orderCreateOrUpdate($observer)
    {
        $content = $observer->getOrder()->getData();

        $orderNotificationService = new OrderNotification();

        $orderNotificationService->send($content);
    }
}
