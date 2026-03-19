<?php

namespace Twix\Dto\Wallet;

/**
 * Class DepositAddressDto
 *
 * DTO для депозитного адреса по одной валюте
 */
class DepositAddressDto
{
    /**
     * @var string
     */
    private $currency;

    /**
     * @var string|null
     */
    private $address;

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        $dto->currency = $data['currency'] ?? '';
        $dto->address = $data['address'] ?? null;

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
            'address' => $this->address,
        ];
    }

    public function getCurrency(): string { return $this->currency; }
    public function getAddress(): ?string { return $this->address; }
}