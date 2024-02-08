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

namespace Shipay\PixQrGateway\Block;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template\Context;
use Magento\Payment\Block\ConfigurableInfo;
use Magento\Payment\Gateway\ConfigInterface;
use Shipay\PixQrGateway\Gateway\Enums\PaymentStatus;
use Shipay\PixQrGateway\Gateway\Http\GetPaymentTransaction;
use Shipay\PixQrGateway\Gateway\Response\ResponseFieldsInterface;
use Shipay\PixQrGateway\Model\Traits\PaymentTrait;
use Shipay\PixQrGateway\Model\Wallet;

class PaymentInfo extends ConfigurableInfo
{
    use PaymentTrait;
    protected $_template = 'Shipay_PixQrGateway::payment/info.phtml';
    /**
     * @var Wallet
     */
    private $wallet;

    /**
     * @var GetPaymentTransaction
     */
    private $getPaymentTransaction;

    /**
     * PaymentInfo constructor.
     * @param Context $context
     * @param ConfigInterface $config
     * @param Wallet $wallet
     * @param GetPaymentTransaction $getPaymentTransaction
     * @param array $data
     */
    public function __construct(
        Context $context,
        ConfigInterface $config,
        Wallet $wallet,
        GetPaymentTransaction $getPaymentTransaction,
        array $data = []
    ) {
        parent::__construct($context, $config, $data);
        $this->wallet = $wallet;
        $this->getPaymentTransaction = $getPaymentTransaction;
    }

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        parent::_construct();
    }

    /**
     * @throws LocalizedException
     * @return void
     */
    private function checkPaymentStatus()
    {
        $additionalInformation = $this->getPaymentAdditionalInformation();

        if (!$additionalInformation ||
            !isset($additionalInformation[ResponseFieldsInterface::STATUS]) ||
            $additionalInformation[ResponseFieldsInterface::STATUS] !== PaymentStatus::PENDING_PAYMENT
        ) {
            return;
        }

        $paymentTransaction = $this->getPaymentTransaction
            ->placeRequest($additionalInformation[ResponseFieldsInterface::ORDER_ID]);

        if (!isset($paymentTransaction[ResponseFieldsInterface::STATUS]) ||
            $paymentTransaction[ResponseFieldsInterface::STATUS] === PaymentStatus::PENDING_PAYMENT
        ) {
            return;
        }

        $payment = $this->getInfo();

        $payment->setAdditionalInformation(
            ResponseFieldsInterface::STATUS,
            $paymentTransaction[ResponseFieldsInterface::STATUS]
        )->save();
    }

    /**
     * @return mixed|null
     * @throws LocalizedException
     */
    public function getPaymentAdditionalInformation()
    {
        $payment = $this->getInfo();

        if (!$payment) {
            return null;
        }

        return $payment->getAdditionalInformation();
    }

    /**
     * @return bool
     * @throws LocalizedException
     */
    public function isAcceptedPayment()
    {
        $additionalInformation = $this->getPaymentAdditionalInformation();
        return isset($additionalInformation[ResponseFieldsInterface::STATUS]) &&
            $additionalInformation[ResponseFieldsInterface::STATUS] === PaymentStatus::PAID;
    }

    /**
     * @return bool
     * @throws LocalizedException
     */
    public function isExpiredPayment()
    {
        $this->checkPaymentStatus();

        $additionalInformation = $this->getPaymentAdditionalInformation();
        return isset($additionalInformation[ResponseFieldsInterface::STATUS]) &&
            $additionalInformation[ResponseFieldsInterface::STATUS] === PaymentStatus::EXPIRED ||
            $additionalInformation[ResponseFieldsInterface::STATUS] === PaymentStatus::CANCELLED;
    }

    /**
     * @return bool
     * @throws LocalizedException
     */
    public function isPendingPayment()
    {
        $additionalInformation = $this->getPaymentAdditionalInformation();
        return isset($additionalInformation[ResponseFieldsInterface::STATUS]) &&
            $additionalInformation[ResponseFieldsInterface::STATUS] === PaymentStatus::PENDING_PAYMENT;
    }
}
