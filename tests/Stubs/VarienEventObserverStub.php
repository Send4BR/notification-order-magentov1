<?php

class VarienEventObserverStub
{
    private $orderData;

    public function __construct($orderData)
    {
        $this->orderData = $orderData;
    }

    public function getOrder()
    {
        return $this->orderData;
    }
}
