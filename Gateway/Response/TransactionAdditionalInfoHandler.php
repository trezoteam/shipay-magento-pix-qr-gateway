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
 */

namespace Shipay\PixQrGateway\Gateway\Response;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order\Payment;

class TransactionAdditionalInfoHandler implements HandlerInterface
{
    /**
     * @var array
     */
    private $responseFields = [
        ResponseFieldsInterface::DEEP_LINK,
        ResponseFieldsInterface::ORDER_ID,
        ResponseFieldsInterface::PIX_PSP,
        ResponseFieldsInterface::QR_CODE,
        ResponseFieldsInterface::QR_CODE_TEXT,
        ResponseFieldsInterface::STATUS,
        ResponseFieldsInterface::WALLET,
    ];

    /**
     * @param array $handlingSubject
     * @param array $response
     */
    public function handle(array $handlingSubject, array $response)
    {
        /** @var PaymentDataObjectInterface $paymentDataObject */
        $paymentDataObject = $handlingSubject['payment'];

        /** @var Payment $payment */
        $payment = $paymentDataObject->getPayment();

        $rawDetails = [];

        foreach ($this->responseFields as $field) {
            if (isset($response[$field])) {
                $rawDetails[$field] = $response[$field];
            }
        }

        $payment->setAdditionalInformation('raw_details_info', $rawDetails);
    }
}
