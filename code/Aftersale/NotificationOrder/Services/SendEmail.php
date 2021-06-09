<?php


class SendEmail
{
    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function make()
    {
        $emailTo = Mage::getStoreConfig('configs/webhook/email');
        $body = $this->getBody();

        $mail = Mage::getModel('core/email');
        $mail->setToName('Ti');
        $mail->setToEmail($emailTo);
        $mail->setBody($body);
        $mail->setSubject('Informativo referente ao seu webhook');
        $mail->setType('html');
        $mail->send();
    }

    public function getBody()
    {
        $store = Mage::app()->getStore()->getName();

        $ecommerceUIID = Mage::getStoreConfig('configs/webhook/ecommerce_uuid');

        $url = Mage::getStoreConfig('configs/webhook/url_webhook');

        return "<p> Oi, TI! </p>
           <p> Estamos recebendo erros na tentativa de entregar eventos em seu webhook. Mais detalhes abaixo:</p>
           <p> <b>Ecommerce:</b>  $store </p>
           <p> <b>Webhook URL:</b>  $url </p>
           <p> <b>Ecommerce uiid:</b>  $ecommerceUIID </p>
           <p> <b>Error:</b>  $this->message </p>
            ";
    }
}
