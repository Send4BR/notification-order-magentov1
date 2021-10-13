<?php


class WebhookServer
{

    public function make($data, $headers, $url)
    {
        $handle = curl_init($url);

        $encodeData = json_encode($data);

        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $encodeData);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

        $result = json_decode(curl_exec($handle));

        curl_close($handle);

        if (!$result->success) {
            throw new Exception($result->message);
        }

        return $result;
    }
}
