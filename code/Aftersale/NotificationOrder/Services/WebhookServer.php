<?php


class WebhookServer
{
    private $data;
    private $headers;
    private $url;
    public function __construct($data, $headers, $url)
    {
       $this->data = $data;
       $this->headers = $headers;
       $this->url = $url;
    }

    public function make()
    {
        $handle = curl_init($this->url);

        $encodeData = json_encode($this->data);

        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $encodeData);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $this->headers);

        $result = json_decode(curl_exec($handle));

        curl_close($handle);

        if (!$result->success) {
            throw new Exception($result->message);
        }

        return $result;
    }
}
