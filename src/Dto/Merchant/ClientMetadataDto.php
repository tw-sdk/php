<?php

namespace Twix\Dto\Merchant;

/**
 * Class ClientMetadataDto
 */
class ClientMetadataDto
{
    /**
     * @var int|null
     */
    private $clientBankId;

    /**
     * @var int|null
     */
    private $clientOrdersCount;

    /**
     * @var string|null
     */
    private $userName;

    /**
     * @param int|null $clientBankId
     * @param int|null $clientOrdersCount
     * @param string|null $userName
     */
    public function __construct(
        ?int $clientBankId = null,
        ?int $clientOrdersCount = null,
        ?string $userName = null
    ) {
        $this->clientBankId = $clientBankId;
        $this->clientOrdersCount = $clientOrdersCount;
        $this->userName = $userName;
    }

    /**
     * @return int|null
     */
    public function getClientBankId(): ?int
    {
        return $this->clientBankId;
    }

    /**
     * @return int|null
     */
    public function getClientOrdersCount(): ?int
    {
        return $this->clientOrdersCount;
    }

    /**
     * @return string|null
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->clientBankId !== null) {
            $data['client_bank_id'] = $this->clientBankId;
        }

        if ($this->clientOrdersCount !== null) {
            $data['client_orders_count'] = $this->clientOrdersCount;
        }

        if ($this->userName !== null) {
            $data['user_name'] = $this->userName;
        }

        return $data;
    }

    /**
     * @param array $data
     * @return self|null
     */
    public static function fromArray(?array $data): ?self
    {
        if ($data === null) {
            return null;
        }

        return new self(
            $data['client_bank_id'] ?? null,
            $data['client_orders_count'] ?? null,
            $data['user_name'] ?? null
        );
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->clientBankId === null
            && $this->clientOrdersCount === null
            && $this->userName === null;
    }
}