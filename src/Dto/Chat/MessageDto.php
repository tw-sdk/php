<?php

namespace Twix\Dto\Chat;

/**
 * Class MessageDto
 */
class MessageDto
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $sender;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @var string
     */
    private $created;

    /**
     * @var ChatFileDto|null
     */
    private $file;

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        $dto->id = (int) ($data['id'] ?? 0);
        $dto->sender = $data['sender'] ?? '';

        $dto->message = isset($data['message']) && $data['message'] !== ''
            ? $data['message']
            : null;

        $dto->created = $data['created'] ?? '';

        // Файл может быть null или отсутствовать
        $dto->file = isset($data['file']) && is_array($data['file'])
            ? ChatFileDto::fromArray($data['file']['file'])
            : null;

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
        $data = [
            'id' => $this->id,
            'sender' => $this->sender,
            'created' => $this->created,
        ];

        if ($this->message !== null) {
            $data['message'] = $this->message;
        } else {
            $data['message'] = '';
        }

        if ($this->file !== null) {
            $data['file'] = $this->file;
        }

        return $data;
    }

    public function getId(): int { return $this->id; }
    public function getSender(): string { return $this->sender; }
    public function getMessage(): ?string { return $this->message; }
    public function getCreated(): string { return $this->created; }
    public function getFile(): ?array { return $this->file; }
}
