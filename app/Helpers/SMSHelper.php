<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class SMSHelper
{
    protected Client $client;

    protected string $apiUrl;

    protected string $apiSecret;

    protected string $device;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = 'https://hahu.io/api/send/sms'; // SMS API URL
        $this->apiSecret = '47844c0925213f1c1f97c4a9528ee1370dbcc45a'; // Replace with your SMS API secret
        $this->device = '00000000-0000-0000-5e6c-76a63bfcfab1'; // Replace with your device id
    }

    public function send($phoneNumber, $message, $mode = 'devices', $sim = 1, $priority = 1)
    {
        try {
            $response = $this->client->request('POST', $this->apiUrl, [
                'form_params' => [
                    'secret' => $this->apiSecret,
                    'mode' => $mode,
                    'device' => $this->device,
                    'sim' => $sim,
                    'priority' => $priority,
                    'phone' => $phoneNumber,
                    'message' => $message,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            // Log the error or handle it in a way that's best for your application
            return false;
        }
    }
}
