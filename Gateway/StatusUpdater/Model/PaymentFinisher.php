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

namespace Shipay\PixQrGateway\Gateway\StatusUpdater\Model;

use Magento\Sales\Api\OrderPaymentRepositoryInterface;
use Shipay\PixQrGateway\Gateway\Response\ResponseFieldsInterface;

class PaymentFinisher
{
    const IS_SHIPAY_STATUS_CLOSED = 'is_shipay_status_closed';

    /**
     * @var OrderPaymentRepositoryInterface
     */
    private $paymentRepository;

    /**
     * PaymentFinisher constructor.
     * @param OrderPaymentRepositoryInterface $paymentRepository
     */
    public function __construct(OrderPaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param int $paymentId
     * @param string $paymentStatus
     */
    public function closePayment($paymentId, $paymentStatus)
    {
        $payment = $this->paymentRepository->get($paymentId);
        $payment->setAdditionalInformation(ResponseFieldsInterface::STATUS, $paymentStatus);
        $payment->setData(self::IS_SHIPAY_STATUS_CLOSED, 1);
        $this->paymentRepository->save($payment);
    }
}
