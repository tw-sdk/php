<?php

namespace Twix\Dto\Global;

/**
 * Class BankDto
 */
class BankDto
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var int|null
     */
    private $xId;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->id = isset($data['id']) ? (int) $data['id'] : null;
        $dto->xId = isset($data['x_id']) ? (int) $data['x_id'] : null;
        $dto->name = $data['name'] ?? null;
        return $dto;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'x_id' => $this->xId,
            'name' => $this->name,
        ], function ($value) {
            return $value !== null;
        });
    }
    
    public function getId(): ?int { return $this->id; }
    public function getXId(): ?int { return $this->xId; }
    public function getName(): ?string { return $this->name; }
}