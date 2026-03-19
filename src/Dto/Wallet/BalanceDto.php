<?php

namespace Twix\Dto\Wallet;

/**
 * Class BalanceDto
 *
 * DTO для баланса по одной валюте
 */
class BalanceDto
{
    /**
     * @var string
     */
    private $currency;

    /**
     * @var float
     */
    private $value;

    /**
     * @var float
     */
    private $valueHold;

    /**
     * @var float
     */
    private $total;

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        $dto->currency = $data['currency'] ?? '';
        $dto->value = (float) ($data['value'] ?? 0);
        $dto->valueHold = (float) ($data['value_hold'] ?? 0);
        $dto->total = (float) ($data['total'] ?? 0);

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
            'currency' => $this->currency,
            'value' => $this->value,
            'value_hold' => $this->valueHold,
            'total' => $this->total,
        ];
    }
    
    public function getCurrency(): string { return $this->currency; }
    public function getValue(): float { return $this->value; }
    public function getValueHold(): float { return $this->valueHold; }
    public function getTotal(): float { return $this->total; }
}