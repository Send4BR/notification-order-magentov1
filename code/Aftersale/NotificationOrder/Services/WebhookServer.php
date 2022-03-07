<?php


class WebhookServer
{

    public function make($data, $headers, $url)
    {
        $encodeData = json_encode($data);

        $options = [
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $encodeData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FAILONERROR => true,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_TIMEOUT => 20
        ];

        $request = curl_init($url);
        curl_setopt_array($request, $options);

        $content = curl_exec($request);
        $erro = curl_errno($request);
        $erromsg = curl_error($request);

        curl_close($request);

        $response = json_decode($content);

        if ($erro) {
            throw new Exception($erromsg);
        }

        if (!$response->success) {
            throw new Exception($response->message);
        }

        return $response;
    }
}
