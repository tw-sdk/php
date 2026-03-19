<?php

namespace Twix\Dto\Processor;

/**
 * Class PoolDto
 *
 * DTO для ордера из пула процессора (доступные для взятия заказы)
 */
class PoolDto
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $fiatCurrency;

    /**
     * @var float
     */
    private $fiatAmount;

    /**
     * @var string
     */
    private $paymentSystem;

    /**
     * @var string|null
     */
    private $bankName;

    /**
     * @var string|null
     */
    private $apiType;

    /**
     * @var bool
     */
    private $isCis;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string
     */
    private $invalidAt;

    /**
     * @var string|null
     */
    private $clientId;

    /**
     * @var bool
     */
    private $monobank;

    /**
     * @var float|null
     */
    private $cryptoAmountUsdt;

    /**
     * @var float|null
     */
    private $cryptoAmountBtc;

    /**
     * @var string|null
     */
    private $paymentRequirements;

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        $dto->id = $data['id'] ?? '';
        $dto->fiatCurrency = $data['fiat_currency'] ?? '';
        $dto->fiatAmount = (float) ($data['fiat_amount'] ?? 0);
        $dto->paymentSystem = $data['payment_system'] ?? '';
        $dto->bankName = $data['bank_name'] ?? null;
        $dto->apiType = $data['api_type'] ?? null;
        $dto->isCis = (bool) ($data['is_cis'] ?? false);
        $dto->createdAt = $data['created_at'] ?? '';
        $dto->invalidAt = $data['invalid_at'] ?? '';
        $dto->clientId = $data['client_id'] ?? null;
        $dto->monobank = (bool) ($data['monobank'] ?? false);
        $dto->cryptoAmountUsdt = isset($data['crypto_amount_usdt']) ? (float) $data['crypto_amount_usdt'] : null;
        $dto->cryptoAmountBtc = isset($data['crypto_amount_btc']) ? (float) $data['crypto_amount_btc'] : null;
        $dto->paymentRequirements = $data['payment_requirements'] ?? null;

        return $dto;
    }

    /**
     * @param array $items
     * @return self[]
     */
    public static function arrayFrom(array $items): array
    {
        $result = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                $result[] = self::fromArray($item);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fiat_currency' => $this->fiatCurrency,
            'fiat_amount' => $this->fiatAmount,
            'payment_system' => $this->paymentSystem,
            'bank_name' => $this->bankName,
            'api_type' => $this->apiType,
            'is_cis' => $this->isCis,
            'created_at' => $this->createdAt,
            'invalid_at' => $this->invalidAt,
            'client_id' => $this->clientId,
            'monobank' => $this->monobank,
            'crypto_amount_usdt' => $this->cryptoAmountUsdt,
            'crypto_amount_btc' => $this->cryptoAmountBtc,
            'payment_requirements' => $this->paymentRequirements,
        ];
    }

    public function getId(): string { return $this->id; }
    public function getFiatCurrency(): string { return $this->fiatCurrency; }
    public function getFiatAmount(): float { return $this->fiatAmount; }
    public function getPaymentSystem(): string { return $this->paymentSystem; }
    public function getBankName(): ?string { return $this->bankName; }
    public function getApiType(): ?string { return $this->apiType; }
    public function isCis(): bool { return $this->isCis; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function getInvalidAt(): string { return $this->invalidAt; }
    public function getClientId(): ?string { return $this->clientId; }
    public function isMonobank(): bool { return $this->monobank; }
    public function getCryptoAmountUsdt(): ?float { return $this->cryptoAmountUsdt; }
    public function getCryptoAmountBtc(): ?float { return $this->cryptoAmountBtc; }
    public function getPaymentRequirements(): ?string { return $this->paymentRequirements; }

    /**
     * Check if order is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        $invalidAt = \DateTime::createFromFormat(\DateTime::ISO8601, $this->invalidAt);
        if (!$invalidAt) {
            $invalidAt = new \DateTime($this->invalidAt);
        }

        return $invalidAt < new \DateTime();
    }
}
