<?php

namespace Twix\Dto\Processor;

/**
 * Class TickerDto
 *
 * DTO для курса валютной пары
 */
class TickerDto
{
    /**
     * @var string
     */
    private $ticker;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string|null
     */
    private $from;

    /**
     * @var string|null
     */
    private $to;

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        $dto->ticker = $data['ticker'] ?? '';
        $dto->value = $data['value'] ?? '0';

        if ($dto->ticker) {
            $parts = explode('_', $dto->ticker);
            if (count($parts) === 2) {
                $dto->from = $parts[0];
                $dto->to = $parts[1];
            }
        }

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
            if (is_array($item) && isset($item['ticker'])) {
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
            'ticker' => $this->ticker,
            'value' => $this->value,
        ];
    }

    // Геттеры
    public function getTicker(): string { return $this->ticker; }
    public function getValue(): string { return $this->value; }
    public function getFrom(): ?string { return $this->from; }
    public function getTo(): ?string { return $this->to; }
}
