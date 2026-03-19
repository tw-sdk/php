<?php

namespace Twix\Dto\Processor;

use Twix\Enum\PaymentSystem;

/**
 * Class TakeOrderDto
 */
class TakeOrderDto
{
    /**
     * @var string
     */
    private $orderId;

    /**
     * @var string
     */
    private $paymentDetails;

    /**
     * @var PaymentSystem
     */
    private $paymentSystem;

    /**
     * @var string
     */
    private $paymentBank;

    /**
     * @var string|null
     */
    private $paymentFio;

    /**
     * @var string|null
     */
    private $mobileOperator;

    /**
     * @param string $orderId
     * @param string $paymentDetails
     * @param PaymentSystem $paymentSystem
     * @param string $paymentBank
     * @param string|null $paymentFio
     * @param string|null $mobileOperator
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $orderId,
        string $paymentDetails,
        PaymentSystem $paymentSystem,
        string $paymentBank,
        ?string $paymentFio = null,
        ?string $mobileOperator = null
    ) {
        // Валидация для SIM
        if ($paymentSystem->getValue() === PaymentSystem::SIM && $mobileOperator === null) {
            throw new \InvalidArgumentException('Mobile operator is required for SIM payment system');
        }

        // Валидация формата paymentDetails в зависимости от системы
        $this->validatePaymentDetails($paymentDetails, $paymentSystem);

        $this->orderId = $orderId;
        $this->paymentDetails = $paymentDetails;
        $this->paymentSystem = $paymentSystem;
        $this->paymentBank = $paymentBank;
        $this->paymentFio = $paymentFio;
        $this->mobileOperator = $mobileOperator;
    }

    /**
     * Validate payment details format.
     *
     * @param string $details
     * @param PaymentSystem $system
     * @throws \InvalidArgumentException
     */
    private function validatePaymentDetails(string $details, PaymentSystem $system): void
    {
        $value = $system->getValue();

        if (in_array($value, [PaymentSystem::CCARD, PaymentSystem::SBP, PaymentSystem::SIM])) {
            if (!ctype_digit($details)) {
                throw new \InvalidArgumentException("Payment details for {$value} must contain only digits");
            }
        }

        // Можно добавить проверки длины для разных систем
        if ($value === PaymentSystem::CCARD && strlen($details) !== 16) {
            // throw new \InvalidArgumentException('Card number must be 16 digits');
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'order_id' => $this->orderId,
            'payment_details' => $this->paymentDetails,
            'payment_system' => $this->paymentSystem->getValue(),
            'payment_bank' => $this->paymentBank,
        ];

        if ($this->paymentFio !== null) {
            $data['payment_fio'] = $this->paymentFio;
        }

        if ($this->mobileOperator !== null) {
            $data['mobile_operator'] = $this->mobileOperator;
        }

        return $data;
    }

    // Геттеры
    public function getOrderId(): string { return $this->orderId; }
    public function getPaymentDetails(): string { return $this->paymentDetails; }
    public function getPaymentSystem(): PaymentSystem { return $this->paymentSystem; }
    public function getPaymentBank(): string { return $this->paymentBank; }
    public function getPaymentFio(): ?string { return $this->paymentFio; }
    public function getMobileOperator(): ?string { return $this->mobileOperator; }
}