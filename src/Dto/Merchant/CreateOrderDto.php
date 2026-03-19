<?php

namespace Twix\Dto\Merchant;

use Twix\Enum\CryptoCurrency;
use Twix\Enum\PaymentSystem;

/**
 * Class CreateOrderDto
 */
class CreateOrderDto
{
    /**
     * @var string
     */
    private $customId;

    /**
     * @var float
     */
    private $fiatAmount;

    /**
     * @var CryptoCurrency
     */
    private $currencyTo;

    /**
     * @var PaymentSystem|null
     */
    private $paymentSystem;

    /**
     * @var string|null
     */
    private $currencyFrom;

    /**
     * @var bool|null
     */
    private $isCis;

    /**
     * @var int|null
     */
    private $timeout;

    /**
     * @var ClientMetadataDto|null
     */
    private $metadata;

    /**
     * @param string $customId
     * @param float $fiatAmount
     * @param CryptoCurrency $currencyTo
     * @param PaymentSystem|null $paymentSystem
     * @param string|null $currencyFrom
     * @param bool|null $isCis
     * @param int|null $timeout
     * @param ClientMetadataDto|null $metadata
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $customId,
        float $fiatAmount,
        CryptoCurrency $currencyTo,
        ?PaymentSystem $paymentSystem = null,
        ?string $currencyFrom = null,
        ?bool $isCis = null,
        ?int $timeout = null,
        ?ClientMetadataDto $metadata = null
    ) {
        if ($timeout !== null && ($timeout < 30 || $timeout > 60)) {
            throw new \InvalidArgumentException('Timeout must be between 30 and 60 minutes');
        }

        $this->customId = $customId;
        $this->fiatAmount = $fiatAmount;
        $this->currencyTo = $currencyTo;
        $this->paymentSystem = $paymentSystem;
        $this->currencyFrom = $currencyFrom;
        $this->isCis = $isCis;
        $this->timeout = $timeout;
        $this->metadata = $metadata;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'custom_id' => $this->customId,
            'fiat_amount' => $this->fiatAmount,
            'currency_to' => $this->currencyTo->getValue(),
        ];

        if ($this->paymentSystem !== null) {
            $data['payment_system'] = $this->paymentSystem->getValue();
        }

        if ($this->currencyFrom !== null) {
            $data['currency_from'] = $this->currencyFrom;
        }

        if ($this->isCis !== null) {
            $data['is_cis'] = $this->isCis;
        }

        if ($this->timeout !== null) {
            $data['timeout'] = $this->timeout;
        }

        if ($this->metadata !== null && !$this->metadata->isEmpty()) {
            $data['metadata'] = $this->metadata->toArray();
        }

        return $data;
    }

    // Геттеры
    public function getCustomId(): string
    {
        return $this->customId;
    }

    public function getFiatAmount(): float
    {
        return $this->fiatAmount;
    }

    public function getCurrencyTo(): CryptoCurrency
    {
        return $this->currencyTo;
    }

    public function getPaymentSystem(): ?PaymentSystem
    {
        return $this->paymentSystem;
    }

    public function getCurrencyFrom(): ?string
    {
        return $this->currencyFrom;
    }

    public function getIsCis(): ?bool
    {
        return $this->isCis;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    public function getMetadata(): ?ClientMetadataDto
    {
        return $this->metadata;
    }
}