<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(array $params)
    {
        try {
            return Snap::createTransaction($params);
        } catch (\Exception $e) {
            logger()->error('Midtrans Error: ' . $e->getMessage());
            return null;
        }
    }

    public function generateSnapToken(array $params): ?string
    {
        $response = $this->createTransaction($params);
        return $response->token ?? null;
    }

    public function getSnapRedirectUrl(array $params): ?string
    {
        $response = $this->createTransaction($params);
        return $response->redirect_url ?? null;
    }
}