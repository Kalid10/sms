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
        $this->apiUrl = env('SMS_API_URL');
        $this->apiSecret = env('SMS_API_SECRET');
        $this->device = env('SMS_DEVICE_ID');
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
            return false;
        }
    }
}
