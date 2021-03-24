<?php

declare(strict_types=1);

/**
 * DISCLAIMER
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category Shipay
 * @package Shipay_PixQrGateway
 * @copyright Copyright (c) 2021 Shipay
 * @author Shipay <ajuda@shipay.com.br>
 *
 * See LICENSE for license details.
 */

namespace Shipay\PixQrGateway\Api;

use Shipay\PixQrGateway\Api\Data\WebhookDataInterface;

interface WebhookInterface
{
    const SHIPAY_SECRET_KEY_HEADER = 'x-shipay-secret-key';
    const SHIPAY_SECRETKEY_HEADER = 'x-shipay-secretkey';
    const SHIPAY_SECRET_KEY_HEADER_CAMEL = 'X-Shipay-Secret-Key';
    const SHIPAY_SECRETKEY_HEADER_CAMEL = 'X-Shipay-Secretkey';

    /**
     * @param string $order_id
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function processNotification($order_id);
}
