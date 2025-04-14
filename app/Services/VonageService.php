<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS as VonageSMS;

class VonageService
{
    protected $vonage;

    public function __construct()
    {
        $basic  = new Basic(
            config('services.vonage.key'),
            config('services.vonage.secret')
        );

        $this->vonage = new Client($basic);
    }

    public function sendSms($to, $message)
    {
        $sms = new VonageSMS($to, config('services.vonage.from'), $message);
        return $this->vonage->sms()->send($sms);
    }
}