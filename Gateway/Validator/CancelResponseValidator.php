<?php

declare(strict_types=1);

/**
 * DISCLAIMER
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category Shipay
 * @package Shipay_PixQrGateway
 * @copyright Copyright (c) 2024 Shipay
 * @author Shipay <ajuda@shipay.com.br>
 */

namespace Shipay\PixQrGateway\Gateway\Validator;

use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterfaceFactory;
use Psr\Log\LoggerInterface;

class CancelResponseValidator extends AbstractValidator
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * GeneralReponseValidator constructor.
     * @param ResultInterfaceFactory $resultFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResultInterfaceFactory $resultFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($resultFactory);
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function validate(array $validationSubject)
    {
        $response = $validationSubject['response'];

        $isValid = true;
        $errorMessages = [];

        if (!isset($response['status'])) {
            $isValid = false;
        }

        return $this->createResult($isValid, $errorMessages);
    }
}
