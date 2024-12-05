<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class SmsService
{
    protected Client $client;


    public function __construct()
    {
        $this->client = new Client(
            config('twilio.sid'),
            config('twilio.token')
        );
    }

    public function sendSms(string $receiver, string $message): void
    {
        try {
            $this->client->messages->create(
                $receiver,
                [
                    'from' => config('twilio.sender_number'),
                    'body' => $message,
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
        }
    }
}
