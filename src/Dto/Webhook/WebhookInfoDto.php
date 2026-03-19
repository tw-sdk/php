<?php

namespace Twix\Dto\Webhook;

/**
 * Class WebhookInfoDto
 *
 * DTO для информации об одном типе вебхука
 */
class WebhookInfoDto
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $event;

    /**
     * @var string
     */
    private $dataStructure;

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        $dto->type = $data['type'] ?? '';
        $dto->description = $data['description'] ?? '';
        $dto->event = $data['event'] ?? '';
        $dto->dataStructure = $data['data_structure'] ?? '';

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
            'type' => $this->type,
            'description' => $this->description,
            'event' => $this->event,
            'data_structure' => $this->dataStructure,
        ];
    }

    // Геттеры
    public function getType(): string { return $this->type; }
    public function getDescription(): string { return $this->description; }
    public function getEvent(): string { return $this->event; }
    public function getDataStructure(): string { return $this->dataStructure; }
}
